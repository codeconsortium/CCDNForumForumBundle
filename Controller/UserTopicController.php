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

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicResponseEvent;

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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canShowTopic($topic, $forum));
        $postsPager = $this->getPostModel()->findAllPostsPaginatedByTopicId($topicId, $this->getQuery('page', 1), $this->getPageHelper()->getPostsPerPageOnTopics(), true);

        if ($this->isGranted('ROLE_USER')) {
            if ($subscription = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topicId, $this->getUser()->getId())) {
                $this->getSubscriptionModel()->markAsRead($subscription);
            }
        } else {
            $subscription = null;
        }

        $subscriberCount = $this->getSubscriptionModel()->countSubscriptionsForTopicById($topicId);
        $this->getTopicModel()->incrementViewCounter($topic);
        $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/show.html.', array(
            'crumbs' => $this->getCrumbs()->addUserTopicShow($forum, $topic), 'forum' => $forum, 'topic' => $topic,
            'forumName' => $forumName,
            'pager' => $postsPager, 'subscription' => $subscription, 'subscription_count' => $subscriberCount,
        ));

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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($board = $this->getBoardModel()->findOneBoardByIdWithCategory($boardId));
        $this->isAuthorised($this->getAuthorizer()->canCreateTopicOnBoard($board, $forum));
        $formHandler = $this->getFormHandlerToCreateTopic($forum, $board);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/create.html.', array(
            'crumbs' => $this->getCrumbs()->addUserTopicCreate($forum, $board),
            'forum' => $forum,
            'forumName' => $forumName,
            'board' => $board,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($board = $this->getBoardModel()->findOneBoardByIdWithCategory($boardId));
        $this->isAuthorised($this->getAuthorizer()->canCreateTopicOnBoard($board, $forum));
        $formHandler = $this->getFormHandlerToCreateTopic($forum, $board);

        if ($formHandler->process()) {
            $response = $this->redirectResponseForTopicOnPageFromPost($forumName, $formHandler->getForm()->getData()->getTopic(), $formHandler->getForm()->getData());
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/create.html.', array(
                'crumbs' => $this->getCrumbs()->addUserTopicCreate($forum, $board), 'forum' => $forum, 'board' => $board,
                'forumName' => $forumName,
                'preview' => $formHandler->getForm()->getData(), 'form' => $formHandler->getForm()->createView(),
            ));
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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithPosts($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canReplyToTopic($topic, $forum));
        $formHandler = $this->getFormHandlerToReplyToTopic($topic);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/reply.html.', array(
            'crumbs' => $this->getCrumbs()->addUserTopicReply($forum, $topic),
            'forum' => $forum,
            'forumName' => $forumName,
            'topic' => $topic,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithPosts($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canReplyToTopic($topic, $forum));
        $formHandler = $this->getFormHandlerToReplyToTopic($topic);

        if ($formHandler->process()) {
            $response = $this->redirectResponseForTopicOnPageFromPost($forumName, $topic, $formHandler->getForm()->getData());
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:User:Topic/reply.html.', array(
                'crumbs' => $this->getCrumbs()->addUserTopicReply($forum, $topic), 'forum' => $forum, 'topic' => $topic,
                'forumName' => $forumName,
                'preview' => $formHandler->getForm()->getData(), 'form' => $formHandler->getForm()->createView(),
            ));
        }
        $this->dispatch(ForumEvents::USER_TOPIC_REPLY_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()->getTopic()));

        return $response;
    }
}
