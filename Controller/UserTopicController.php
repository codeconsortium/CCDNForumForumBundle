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
        $this->isAuthorisedToViewTopic($topic);

        // Get posts for topic paginated.
		$page = $this->getQuery('page', 1);
		$postsPager = $this->getPostModel()->findAllPaginatedByTopicId($topicId, $page);

        // get the topic subscriptions.
        $subscription = $this->getSubscriptionModel()->findSubscriptionForTopicById($topicId);
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
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $this->isAuthorised('ROLE_USER');

        $board = $this->getBoardModel()->findOneByIdWithCategory($boardId);
        $this->isFound($board);
        //$this->isAuthorisedToCreateTopic($board);

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
		
		$this->dispatch(ForumEvents::USER_TOPIC_CREATE_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $formHandler->getForm()->getData()->getTopic(), $response));
		
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
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $this->isAuthorised('ROLE_USER');

        $board = $this->getBoardModel()->findOneByIdWithCategory($boardId);
        $this->isFound($board);
        //$this->isAuthorisedToCreateTopic($board);

        $formHandler = $this->getFormHandlerToCreateTopic($forum, $board);

        // Flood Control.
        if (! $this->getFloodControl()->isFlooded()) {
            if ($formHandler->process()) {
                $this->getFloodControl()->incrementCounter();

				$topic = $formHandler->getForm()->getData()->getTopic();
				
				$this->dispatch(ForumEvents::USER_TOPIC_CREATE_COMPLETE, new UserTopicEvent($this->getRequest(), $topic));

                $response = $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $topic->getId() )));
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
		
		$this->dispatch(ForumEvents::USER_TOPIC_CREATE_RESPONSE, new UserTopicResponseEvent($this->getRequest(), $formHandler->getForm()->getData()->getTopic(), $response));
		
		return $response;
    }

    /**
     *
     * @access public
     * @param  int                             $topicId, int $quoteId, int $draftId
     * @return RedirectResponse|RenderResponse
     */
    public function replyAction($topicId, $quoteId, $draftId)
    {
        $this->isAuthorised('ROLE_USER');

        $topic = $this->getTopicManager()->findOneByIdWithPostsByTopicId($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToReplyToTopic($topic);

        $formHandler = $this->getFormHandlerToReplyToTopic($topic, $draftId, $quoteId);

        // Flood Control.
        if ( ! $this->getFloodControl()->isFlooded()) {
            if ($formHandler->process($this->getRequest())) {
                $this->getFloodControl()->incrementCounter();

                // Page of the last post.
                $page = $this->getTopicModel()->getPageForPostOnTopic($topic, $topic->getLastPost());

                $this->setFlash('success', $this->trans('flash.topic.reply.success', array('%topic_title%' => $topic->getTitle())));

                return $this->redirectResponse($this->path('ccdn_forum_user_topic_show_paginated_anchored', array('topicId' => $topicId, 'page' => $page, 'postId' => $topic->getLastPost()->getId()) ));
            }
        } else {
            $this->setFlash('warning', $this->trans('flash.topic.flood_control'));
        }

        // setup crumb trail.
        $board = $topic->getBoard();
        $category = $board->getCategory();

        //$crumbs = $this->getCrumbs()
        //    ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_user_category_index'))
        //    ->add($category->getName(), $this->path('ccdn_forum_user_category_show', array('categoryId' => $category->getId())))
        //    ->add($board->getName(), $this->path('ccdn_forum_user_board_show', array('boardId' => $board->getId())))
        //    ->add($topic->getTitle(), $this->path('ccdn_forum_user_topic_show', array('topicId' => $topic->getId())))
        //    ->add($this->trans('crumbs.topic.reply'), $this->path('ccdn_forum_user_topic_reply', array('topicId' => $topic->getId())));

        return $this->renderResponse('CCDNForumForumBundle:Topic:reply.html.', array(
        //    'crumbs' => $crumbs,
            'topic' => $topic,
            //'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
    }
}
