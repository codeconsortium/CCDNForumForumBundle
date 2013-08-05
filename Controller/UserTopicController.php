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
        $board = $topic->getBoard();
        $category = $board->getCategory();

		$crumbs = $this->getCrumbs()->addUserTopicShow($forum, $topic);

        return $this->renderResponse('CCDNForumForumBundle:User:Topic/show.html.', array(
            'crumbs' => $crumbs,
			'forum' => $forum,
            'board' => $board,
            'topic' => $topic,
            'pager' => $postsPager,
            'subscription' => $subscription,
            'subscription_count' => $subscriberCount,
        ));
    }

    /**
     *
     * @access public
     * @param  int                             $boardId, int $draftId
     * @return RedirectResponse|RenderResponse
     */
    public function createAction($boardId, $draftId)
    {
        $this->isAuthorised('ROLE_USER');

        $board = $this->getBoardModel()->findOneByIdWithCategory($boardId);
        $this->isFound($board);
        $this->isAuthorisedToCreateTopic($board);

        $formHandler = $this->getFormHandlerToCreateTopic($board, $draftId);

        // Flood Control.
        if (! $this->getFloodControl()->isFlooded()) {
            if ($formHandler->process($this->getRequest())) {
                $this->getFloodControl()->incrementCounter();

                $this->setFlash('success', $this->trans('flash.topic.create.success', array('%topic_title%' => $formHandler->getForm()->getData()->getTopic()->getTitle())));

                return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $formHandler->getForm()->getData()->getTopic()->getId() )));
            }
        } else {
            $this->setFlash('warning', $this->trans('flash.topic.flood_control'));
        }

        // setup crumb trail.
        $category = $board->getCategory();

        //$crumbs = $this->getCrumbs()
        //    ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_user_category_index'))
        //    ->add($category->getName(), $this->path('ccdn_forum_user_category_show', array('categoryId' => $category->getId())))
        //    ->add($board->getName(), $this->path('ccdn_forum_user_board_show', array('boardId' => $board->getId())))
        //    ->add($this->trans('crumbs.topic.create'), $this->path('ccdn_forum_user_topic_create', array('boardId' => $board->getId())));

        return $this->renderResponse('CCDNForumForumBundle:Topic:create.html.', array(
        //    'crumbs' => $crumbs,
            'board' => $board,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
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
