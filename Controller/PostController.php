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

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostController extends ContainerAware
{

    /**
     *
     * @access public
     * @param Int $postId
     * @return RenderResponse
     */
    public function showAction($postId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = $this->container->get('ccdn_forum_forum.post.repository')->find($postId);

        if (! $post) {
            throw new NotFoundHttpException('No such post exists!');
        }

        // If this topics first post is deleted, and no other
        // posts exist then throw an NotFoundHttpException!
        if (($post->getIsDeleted() || $post->getTopic()->getIsDeleted())
        && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
        {
            throw new NotFoundHttpException('No such post exists!');
        }

        //
        // Get post counts for users.
        //
        if ($post->getCreatedBy()) {
            $registryUserIds = array($post->getCreatedBy()->getId());
        } else {
            $registryUserIds = array();
        }

        $registries = $this->container->get('ccdn_forum_forum.registry.manager')->getRegistriesForUsersAsArray($registryUserIds);

        //
        // Get the topic subscriptions.
        //
        if ($this->container->get('security.context')->isGranted('ROLE_USER') && $post->getTopic()) {
            $subscription = $this->container->get('ccdn_forum_forum.subscription.repository')->findTopicSubscriptionByTopicAndUserId($post->getTopic()->getId(), $user->getId());
        } else {
            $subscription = null;
        }

        $subscriberCount = $this->container->get('ccdn_forum_forum.subscription.repository')->getSubscriberCountForTopicById($post->getTopic()->getId());

        // setup crumb trail.
        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(), $this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
            ->add('#' . $post->getId(), $this->container->get('router')->generate('ccdn_forum_forum_post_show', array('postId' => $post->getId())), "comment");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:show.html.' . $this->getEngine(), array(
            'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
            'user'	=> $user,
            'crumbs' => $crumbs,
            'topic' => $topic,
            'post' => $post,
            'registries' => $registries,
            'subscription' => $subscription,
            'subscription_count' => $subscriberCount,
        ));
    }

    /**
     *
     * @access public
     * @param Int $postId
     * @return RedirectResponse|RenderResponse
     */
    public function editAction($postId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = $this->container->get('ccdn_forum_forum.post.repository')->findPostForEditing($postId);

        if (! $post) {
            throw new NotFoundHttpException('No such post exists!');
        }

        // if this topics first post is deleted, and no
        // other posts exist then throw an NotFoundHttpException!
        if (($post->getIsDeleted() || $post->getTopic()->getIsDeleted())
        && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
        {
            throw new NotFoundHttpException('No such post exists!');
        }

        // you cannot reply/edit/delete/flag a post if the topic is closed
        if ($post->getTopic()->getIsClosed() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('This topic has been closed!');
        }

        // you cannot reply/edit/delete/flag a post if it is locked
        if ($post->getIsLocked() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
        }

        //
        //	Invalidate this action / redirect if user should not have access to it
        //
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            if ($post->getCreatedBy()) {
                // if user does not own post, or is not a mod
                if ($post->getCreatedBy()->getId() != $user->getId())
                    throw new AccessDeniedException('You do not have permission to edit this post!');
            } else {
                throw new AccessDeniedException('You do not have permission to edit this post!');
            }
        }

        if ($post->getTopic()->getFirstPost()->getId() == $post->getId()) {	// if post is the very first post of the topic then use a topic handler so user can change topic title
            $formHandler = $this->container->get('ccdn_forum_forum.topic.update.form.handler')->setDefaultValues(array('post' => $post, 'user' => $user));

            if ($this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
                $formHandler->setDefaultValues(array('board' => $post->getTopic()->getBoard()));
            }
        } else {
            $formHandler = $this->container->get('ccdn_forum_forum.post.update.form.handler')->setDefaultValues(array('post' => $post, 'user' => $user));
        }

        if (isset($_POST['submit_preview'])) {
            $formHandler->setMode($formHandler::PREVIEW);
        }

        if (isset($_POST['submit_post'])) {
            if ($formHandler->process()) {	// get posts for determining the page of the edited post
                $topic = $post->getTopic();

                // scan for matching post in order and find its index to divide by items per page

                // Reece Fowell.
                // The loop below, could be better written by adding a query to the repo to retrieve
                // posts but only the id column and created date, then sorting by date and hydrating
                // as array instead of collection. Then find array entry via id without a loop, possible
                // php function, maybe array_walk?? This should return the index in the array.

                foreach ($topic->getPosts() as $index => $postTest) {					// <------------- move this shit to the Post or TopicEntityManager?
                    if ($post->getId() == $postTest->getId()) {
                        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');
                        $page = ceil($index / $postsPerPage);
                        break;
                    }
                }

                $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('ccdn_forum_forum.flash.post.edit.success', array('%post_id%' => $postId, '%topic_title%' => $post->getTopic()->getTitle()), 'CCDNForumForumBundle'));

                // redirect user on successful edit.
                return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show_paginated_anchored',
                    array('topicId' => $topic->getId(), 'page' => $page, 'postId' => $post->getId() ) ));
            }
        }

        // setup crumb trail.
        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(),	$this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.post.edit', array(), 'CCDNForumForumBundle') . $post->getId(), $this->container->get('router')->generate('ccdn_forum_forum_topic_reply', array('topicId' => $topic->getId())), "edit");

        if ($post->getTopic()->getFirstPost()->getId() == $post->getId()) {	// render edit_topic if first post
            $template = 'CCDNForumForumBundle:Post:edit_topic.html.' . $this->getEngine();
        } else {
            // render edit_post if not first post
            $template = 'CCDNForumForumBundle:Post:edit_post.html.' . $this->getEngine();
        }

        return $this->container->get('templating')->renderResponse($template, array(
            'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
            'user' => $user,
            'board' => $board,
            'topic' => $topic,
            'post' => $post,
            'crumbs' => $crumbs,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
    }

    /**
     *
     * @access public
     * @param Int $postId
     * @return RedirectResponse|RenderResponse
     */
    public function deleteAction($postId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = $this->container->get('ccdn_forum_forum.post.repository')->findPostForEditing($postId);

        if (! $post) {
            throw new NotFoundHttpException('No such post exists!');
        }

        // if this topics first post is deleted, and no
        // other posts exist then throw an NotFoundHttpException!
        if (($post->getIsDeleted() || $post->getTopic()->getIsDeleted())
        && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
        {
            throw new NotFoundHttpException('No such post exists!');
        }

        if ($post->getTopic()->getIsClosed() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {	// you cannot reply/edit/delete/flag a post if the topic is closed
            throw new AccessDeniedException('This topic has been closed!');
        }

        if ($post->getIsLocked() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {	// you cannot reply/edit/delete/flag a post if it is locked
            throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
        }

        // Invalidate this action / redirect if user should not have access to it
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            // if user does not own post, or is not a mod
            if ($post->getCreatedBy()) {
                if ($post->getCreatedBy()->getId() != $user->getId()) {
                    throw new AccessDeniedException('You do not have permission to use this resource!');
                }
            } else {
                throw new AccessDeniedException('You do not have permission to use this resource!');
            }
        }

        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

        if ($post->getTopic()->getFirstPost()->getId() == $post->getId() && $post->getTopic()->getCachedReplyCount() == 0) {	// if post is the very first post of the topic then use a topic handler so user can change topic title
            $confirmationMessage = 'ccdn_forum_forum.topic.delete_topic_question';
            $crumbDelete = $this->container->get('translator')->trans('ccdn_forum_forum.crumbs.topic.delete', array(), 'CCDNForumForumBundle');
            $pageTitle = $this->container->get('translator')->trans('ccdn_forum_forum.title.topic.delete', array('%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle');
        } else {
            $confirmationMessage = 'ccdn_forum_forum.post.delete_post_question';
            $crumbDelete = $this->container->get('translator')->trans('ccdn_forum_forum.crumbs.post.delete', array(), 'CCDNForumForumBundle') . $post->getId();
            $pageTitle = $this->container->get('translator')->trans('ccdn_forum_forum.title.post.delete', array('%post_id%' => $post->getId(), '%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle');
        }

        // setup crumb trail.
        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(),	$this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
            ->add($crumbDelete, $this->container->get('router')->generate('ccdn_forum_forum_topic_reply', array('topicId' => $topic->getId())), "trash");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:delete_post.html.' . $this->getEngine(), array(
            'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
            'page_title' => $pageTitle,
            'confirmation_message' => $confirmationMessage,
            'topic' => $topic,
            'post' => $post,
            'crumbs' => $crumbs,
        ));
    }

    /**
     *
     * @access public
     * @param Int $postId
     * @return RedirectResponse
     */
    public function deleteConfirmedAction($postId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = $this->container->get('ccdn_forum_forum.post.repository')->findPostForEditing($postId);

        if (! $post) {
            throw new NotFoundHttpException('No such post exists!');
        }

        // if this topics first post is deleted, and no
        // other posts exist then throw an NotFoundHttpException!
        if (($post->getIsDeleted() || $post->getTopic()->getIsDeleted())
        && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
        {
            throw new NotFoundHttpException('No such post exists!');
        }

        // you cannot reply/edit/delete/flag a post if the topic is closed
        if ($post->getTopic()->getIsClosed() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('This topic has been closed!');
        }

        // you cannot reply/edit/delete/flag a post if it is locked
        if ($post->getIsLocked() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
        }

        // Invalidate this action / redirect if user should not have access to it
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            // if user does not own post, or is not a mod
            if ($post->getCreatedBy()) {
                if ($post->getCreatedBy()->getId() != $user->getId()) {
                    throw new AccessDeniedException('You do not have permission to use this resource!');
                }
            } else {
                throw new AccessDeniedException('You do not have permission to use this resource!');
            }
        }

        $this->container->get('ccdn_forum_forum.post.manager')->softDelete($post, $user)->flush();

        // set flash message
        $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('ccdn_forum_forum.flash.post.delete.success', array('%post_id%' => $postId), 'CCDNForumForumBundle'));

        // forward user
        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $post->getTopic()->getId()) ));
    }

    /**
     *
     * @access public
     * @param Int $postId
     * @return RedirectResponse|RenderResponse
     */
    public function flagAction($postId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to flag posts!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = $this->container->get('ccdn_forum_forum.post.repository')->find($postId);

        if (! $post) {
            throw new NotFoundHttpException('No such post exists!');
        }

        // if this topics first post is deleted, and no
        // other posts exist then throw an NotFoundHttpException!
        if (($post->getIsDeleted() || $post->getTopic()->getIsDeleted())
        && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
        {
            throw new NotFoundHttpException('No such post exists!');
        }

        if ($post->getTopic()->getIsClosed() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {	// you cannot reply/edit/delete/flag a post if the topic is closed
            throw new AccessDeniedException('This topic has been closed!');
        }

        if ($post->getIsLocked() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {	// you cannot reply/edit/delete/flag a post if it is locked
            throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
        }

        if ($post->getCreatedBy()) {
            if ($post->getCreatedBy()->getId() == $user->getId()) {
                throw new AccessDeniedException('You cannot flag your own posts!');
            }
        }

        $formHandler = $this->container->get('ccdn_forum_forum.flag.form.handler')->setDefaultValues(array('post' => $post, 'user' => $user));

        if ($formHandler->process()) {
            $this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_forum.flash.post.flagged.success', array('%post_id%' => $postId, '%topic_title%' => $post->getTopic()->getTitle()), 'CCDNForumForumBundle'));

            return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show_paginated_anchored',
                array('topicId' => $post->getTopic()->getId(), 'page' => 1, 'postId' => $postId) ));
        }

        // setup crumb trail.
        $topic = $post->getTopic();
        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(),	$this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.post.flag', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_post_flag', array('postId' => $postId)), "flag");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:flag.html.' . $this->getEngine(), array(
            'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
            'user' => $user,
            'topic' => $topic,
            'post' => $post,
            'crumbs' => $crumbs,
            'form' => $formHandler->getForm()->createView(),
        ));
    }

    /**
     *
     * @access protected
     * @return String
     */
    protected function getEngine()
    {
        return $this->container->getParameter('ccdn_forum_forum.template.engine');
    }

}
