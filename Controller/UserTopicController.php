<?php

/*
 * This file is part of the CCDNForum ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\Event;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicResponseEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicFloodEvent;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class UserTopicController extends UserTopicBaseController
{
    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function showAction($forumName, $topicId)
    {
        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        // Get topic.
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true);
        $this->isFound($topic);

        $this->isAuthorised($this->getAuthorizer()->canShowTopic($topic, $forum));

        // Get posts for topic paginated.
        /** @todo ser $itemsPerPage by PaginationConfigHelper */
        $itemsPerPage = 10;
        $page = $this->getQuery('page', 1);
        $postsPager = $this->getPostModel()->findAllPostsPaginatedByTopicId($topicId, $page, $itemsPerPage, true);
		$this->setPagerTemplate($postsPager);

        // get the topic subscriptions.
        if ($this->isGranted('ROLE_USER')) {
            $subscription = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topicId, $this->getUser()->getId());

            if ($subscription) {
                $this->getSubscriptionModel()->markAsRead($subscription);
            }
        } else {
            $subscription = null;
        }

        $subscriberCount = $this->getSubscriptionModel()->countSubscriptionsForTopicById($topicId);

        // Incremenet view counter.
        $this->getTopicModel()->incrementViewCounter($topic);

        // setup crumb trail.
        $crumbs = $this->getCrumbs()->addUserTopicShow($forum, $topic);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/show.html.',
            array(
                'crumbs' => $crumbs,
                'forum' => $forum,
                'topic' => $topic,
                'pager' => $postsPager,
                'subscription' => $subscription,
                'subscription_count' => $subscriberCount,
            )
        );

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $boardId
     * @return RedirectResponse|RenderResponse
     */
    public function createAction($forumName, $boardId)
    {
        $this->isAuthorised('ROLE_USER');

        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $board = $this->getBoardModel()->findOneBoardByIdWithCategory($boardId);
        $this->isFound($board);

        $this->isAuthorised($this->getAuthorizer()->canCreateTopicOnBoard($board, $forum));

        $formHandler = $this->getFormHandlerToCreateTopic($forum, $board);

        // Flood Control.
        if ($this->getFloodControl()->isFlooded()) {
            $this->dispatch(ForumEvents::USER_TOPIC_CREATE_FLOODED, new UserTopicFloodEvent($this->getRequest()));
        }

        // setup crumb trail.
        $crumbs = $this->getCrumbs()->addUserTopicCreate($forum, $board);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/create.html.',
            array(
                'crumbs' => $crumbs,
                'forum' => $forum,
                'board' => $board,
                'preview' => $formHandler->getForm()->getData(),
                'form' => $formHandler->getForm()->createView(),
            )
        );

        $this->dispatch(ForumEvents::USER_TOPIC_CREATE_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()->getTopic()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $boardId
     * @return RedirectResponse|RenderResponse
     */
    public function createProcessAction($forumName, $boardId)
    {
        $this->isAuthorised('ROLE_USER');

        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $board = $this->getBoardModel()->findOneBoardByIdWithCategory($boardId);
        $this->isFound($board);

        $this->isAuthorised($this->getAuthorizer()->canCreateTopicOnBoard($board, $forum));

        $formHandler = $this->getFormHandlerToCreateTopic($forum, $board);

        // Flood Control.
        if (! $this->getFloodControl()->isFlooded()) {
            if ($formHandler->process()) {
                $this->getFloodControl()->incrementCounter();

                $topic = $formHandler->getForm()->getData()->getTopic();

                $this->dispatch(ForumEvents::USER_TOPIC_CREATE_COMPLETE, new UserTopicEvent($this->getRequest(), $topic, $formHandler->didAuthorSubscribe()));

                $response = $this->redirectResponse(
                    $this->path('ccdn_forum_user_topic_show',
                        array(
                            'forumName' => $forum->getName(),
                            'topicId' => $topic->getId()
                        )
                    )
                );
            }
        } else {
            $this->dispatch(ForumEvents::USER_TOPIC_CREATE_FLOODED, new UserTopicFloodEvent($this->getRequest()));
        }

        if (! isset($response)) {
            // setup crumb trail.
            $crumbs = $this->getCrumbs()->addUserTopicCreate($forum, $board);

            $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/create.html.',
                array(
                    'crumbs' => $crumbs,
                    'forum' => $forum,
                    'board' => $board,
                    'preview' => $formHandler->getForm()->getData(),
                    'form' => $formHandler->getForm()->createView(),
                )
            );
        }

        $this->dispatch(ForumEvents::USER_TOPIC_CREATE_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()->getTopic()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function replyAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_USER');

        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $topic = $this->getTopicModel()->findOneTopicByIdWithPosts($topicId, true);
        $this->isFound($topic);

        $this->isAuthorised($this->getAuthorizer()->canReplyToTopic($topic, $forum));

        $formHandler = $this->getFormHandlerToReplyToTopic($topic);

        // Flood Control.
        if ($this->getFloodControl()->isFlooded()) {
            $this->dispatch(ForumEvents::USER_TOPIC_REPLY_FLOODED, new UserTopicFloodEvent($this->getRequest()));
        }

        // setup crumb trail.
        $crumbs = $this->getCrumbs()->addUserTopicReply($forum, $topic);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/reply.html.',
            array(
                'crumbs' => $crumbs,
                'forum' => $forum,
                'topic' => $topic,
                'preview' => $formHandler->getForm()->getData(),
                'form' => $formHandler->getForm()->createView(),
            )
        );

        $this->dispatch(ForumEvents::USER_TOPIC_REPLY_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()->getTopic()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function replyProcessAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_USER');

        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $topic = $this->getTopicModel()->findOneTopicByIdWithPosts($topicId, true);
        $this->isFound($topic);

        $this->isAuthorised($this->getAuthorizer()->canReplyToTopic($topic, $forum));

        $formHandler = $this->getFormHandlerToReplyToTopic($topic);

        // Flood Control.
        if (! $this->getFloodControl()->isFlooded()) {
            if ($formHandler->process()) {
                $this->getFloodControl()->incrementCounter();

                // Page of the last post.
                //$page = $this->getTopicModel()->getPageForPostOnTopic($topic, $topic->getLastPost());

                $this->dispatch(ForumEvents::USER_TOPIC_REPLY_COMPLETE, new UserTopicEvent($this->getRequest(), $topic, $formHandler->didAuthorSubscribe()));

                $response = $this->redirectResponse(
                    $this->path('ccdn_forum_user_topic_show',
                        array(
                            'forumName' => $forum->getName(),
                            'topicId' => $topicId,
                            //'page' => $page
                        )
                    ) // . '#' . $topic->getLastPost()->getId()
                );
            }
        } else {
            $this->dispatch(ForumEvents::USER_TOPIC_REPLY_FLOODED, new UserTopicFloodEvent($this->getRequest()));
        }

        if (! isset($response)) {
            // setup crumb trail.
            $crumbs = $this->getCrumbs()->addUserTopicReply($forum, $topic);

            $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/reply.html.',
                array(
                    'crumbs' => $crumbs,
                    'forum' => $forum,
                    'topic' => $topic,
                    'preview' => $formHandler->getForm()->getData(),
                    'form' => $formHandler->getForm()->createView(),
                )
            );
        }

        $this->dispatch(ForumEvents::USER_TOPIC_REPLY_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()->getTopic()));

        return $response;
    }
}
