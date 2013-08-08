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
class UserPostController extends UserPostBaseController
{
    /**
     *
     * @access public
     * @param  string         $forumName
     * @param  int            $postId
     * @return RenderResponse
     */
    public function showAction($forumName, $postId)
    {
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        // Get post by id.
        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);
        //$this->isAuthorisedToViewPost($post);

        // Get the topic subscriptions.
        $subscription = $this->getSubscriptionModel()->findSubscriptionForTopicById($post->getTopic()->getId());
        $subscriberCount = $this->getSubscriptionModel()->countSubscriptionsForTopicById($post->getTopic()->getId());

        // Setup crumb trail.
        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

		$crumbs = $this->getCrumbs()->addUserPostShow($forum, $post);

        return $this->renderResponse('CCDNForumForumBundle:User:Post/show.html.',
			array(
	            'crumbs' => $crumbs,
				'forum' => $forum,
	            'topic' => $topic,
	            'post' => $post,
	            'subscription' => $subscription,
	            'subscription_count' => $subscriberCount,
	        )
		);
    }

    /**
     *
     * @access public
     * @param  int                             $postId
     * @return RedirectResponse|RenderResponse
     */
    public function editAction($postId)
    {
        $this->isAuthorised('ROLE_USER');

        $user = $this->getUser();

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId);
        $this->isFound($post);
        $this->isAuthorisedToViewPost($post);
        $this->isAuthorisedToEditPost($post);

        // If post is the very first post of the topic then use a topic handler so user can change topic title.
        if ($post->getTopic()->getFirstPost()->getId() == $post->getId()) {
            $formHandler = $this->getFormHandlerToEditTopic($post);
        } else {
            $formHandler = $this->getFormHandlerToEditPost($post);
        }

        if ($formHandler->process($this->getRequest())) {
            // get posts for determining the page of the edited post
            $topic = $post->getTopic();

            $page = $this->getModelManager()->getPageForPostOnTopic($topic, $post);

            $this->setFlash('success', $this->trans('flash.post.edit.success', array('%post_id%' => $postId, '%topic_title%' => $post->getTopic()->getTitle())));

            return $this->redirectResponse($this->path('ccdn_forum_user_topic_show_paginated_anchored', array('topicId' => $topic->getId(), 'page' => $page, 'postId' => $post->getId() ) ));
        }

        // Setup crumb trail.
        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

        //$crumbs = $this->getCrumbs()
        //    ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_user_category_index'))
        //    ->add($category->getName(),	$this->path('ccdn_forum_user_category_show', array('categoryId' => $category->getId())))
        //    ->add($board->getName(), $this->path('ccdn_forum_user_board_show', array('boardId' => $board->getId())))
        //    ->add($topic->getTitle(), $this->path('ccdn_forum_user_topic_show', array('topicId' => $topic->getId())))
        //    ->add($this->trans('crumbs.post.edit') . $post->getId(), $this->path('ccdn_forum_user_topic_reply', array('topicId' => $topic->getId())));

        if ($post->getTopic()->getFirstPost()->getId() == $post->getId()) {
            // render edit_topic if first post
            $template = 'CCDNForumForumBundle:Post:edit_topic.html.';
        } else {
            // render edit_post if reply post
            $template = 'CCDNForumForumBundle:Post:edit_post.html.';
        }

        return $this->renderResponse($template, array(
            'board' => $board,
            'topic' => $topic,
            'post' => $post,
        //    'crumbs' => $crumbs,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
    }

    /**
     *
     * @access public
     * @param  int                             $postId
     * @return RedirectResponse|RenderResponse
     */
    public function deleteAction($postId)
    {
        $this->isAuthorised('ROLE_USER');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId);
        $this->isFound($post);
        $this->isAuthorisedToViewPost($post);
        $this->isAuthorisedToDeletePost($post);

        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

        if ($post->getTopic()->getFirstPost()->getId() == $post->getId() && $post->getTopic()->getCachedReplyCount() == 0) {
            // if post is the very first post of the topic then use a topic handler so user can change topic title
            $confirmationMessage = 'ccdn_forum_forum.topic.delete_topic_question';
            $crumbDelete = $this->trans('ccdn_forum_forum.crumbs.topic.delete');
            $pageTitle = $this->trans('ccdn_forum_forum.title.topic.delete', array('%topic_title%' => $topic->getTitle()));
        } else {
            $confirmationMessage = 'ccdn_forum_forum.post.delete_post_question';
            $crumbDelete = $this->trans('ccdn_forum_forum.crumbs.post.delete') . $post->getId();
            $pageTitle = $this->trans('ccdn_forum_forum.title.post.delete', array('%post_id%' => $post->getId(), '%topic_title%' => $topic->getTitle()));
        }

        // setup crumb trail.
        $crumbs = $this->getCrumbs()
            ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_user_category_index'))
            ->add($category->getName(),	$this->path('ccdn_forum_user_category_show', array('categoryId' => $category->getId())))
            ->add($board->getName(), $this->path('ccdn_forum_user_board_show', array('boardId' => $board->getId())))
            ->add($topic->getTitle(), $this->path('ccdn_forum_user_topic_show', array('topicId' => $topic->getId())))
            ->add($crumbDelete, $this->path('ccdn_forum_user_topic_reply', array('topicId' => $topic->getId())));

        return $this->renderResponse('CCDNForumForumBundle:Post:delete_post.html.', array(
            'page_title' => $pageTitle,
            'confirmation_message' => $confirmationMessage,
            'topic' => $topic,
            'post' => $post,
        //    'crumbs' => $crumbs,
        ));
    }

    /**
     *
     * @access public
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function deleteConfirmedAction($postId)
    {
        $this->isAuthorised('ROLE_USER');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId);
        $this->isFound($post);
        $this->isAuthorisedToViewPost($post);
        $this->isAuthorisedToDeletePost($post);

        $this->getPostModel()->softDelete($post, $this->getUser())->flush();

        // set flash message
        $this->setFlash('notice', $this->trans('flash.post.success.delete', array('%post_id%' => $postId)));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $post->getTopic()->getId()) ));
    }
}
