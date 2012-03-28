<?php

/*
 * This file is part of the CCDN ForumBundle
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
	 * @param $post_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function showAction($post_id)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$post = $this->container->get('ccdn_forum_forum.post.repository')->find($post_id);
	
		if ( ! $post) {
			throw new NotFoundHttpException('No such post exists!');
		}
		
		// if this topics first post is deleted, and no
		// other posts exist then throw an NotFoundHttpException!
		if (($post->getDeletedBy() || $post->getTopic()->getDeletedBy())
		&& ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new NotFoundHttpException('No such post exists!');
		}
		
		// setup crumb trail.
		$topic = $post->getTopic();
		$board = $topic->getBoard();
		$category = $board->getCategory();
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
			->add($topic->getTitle(), $this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication")
			->add('#' . $post->getId(), $this->container->get('router')->generate('cc_forum_post_show', array('post_id' => $post->getId())), "comment");
			
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:show.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'user'	=> $user,
			'crumbs' => $crumb_trail,
			'topic' => $topic,
			'post' => $post));
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function editAction($post_id)
	{		
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}

		$user = $this->container->get('security.context')->getToken()->getUser();
			
		$post = $this->container->get('ccdn_forum_forum.post.repository')->findPostForEditing($post_id);
		
		if ( ! $post) {
			throw new NotFoundHttpException('No such post exists!');
		}

		// if this topics first post is deleted, and no
		// other posts exist then throw an NotFoundHttpException!
		if (($post->getDeletedBy() || $post->getTopic()->getDeletedBy())
		&& ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new NotFoundHttpException('No such post exists!');
		}
				
		// you cannot reply/edit/delete/flag a post if the topic is closed
		if ($post->getTopic()->getClosedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new AccessDeniedException('This topic has been closed!');
		}
		
		// you cannot reply/edit/delete/flag a post if it is locked
		if ($post->getLockedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
		}
		
		/*
		 *	Invalidate this action / redirect if user should not have access to it
		 */
		if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
			if ($post->getCreatedBy()) {	
				// if user does not own post, or is not a mod
				if ($post->getCreatedBy()->getId() != $user->getId())
					throw new AccessDeniedException('You do not have permission to edit this post!');
			} else {
				throw new AccessDeniedException('You do not have permission to edit this post!');
			}
		}	
		
		if ($post->getTopic()->getFirstPost()->getId() == $post->getId())
		{	// if post is the very first post of the topic then use a topic handler so user can change topic title
			$formHandler = $this->container->get('ccdn_forum_forum.topic.update.form.handler')->setOptions(array('post' => $post, 'user' => $user));
		} else {
			$formHandler = $this->container->get('ccdn_forum_forum.post.update.form.handler')->setOptions(array('post' => $post, 'user' => $user));
		}

		if ($formHandler->process())
		{	// get posts for determining the page of the edited post
			$topic = $post->getTopic();						

			// scan for matching post in order and find its index to divide by items per page
			/*
			Reece Fowell.
			The loop below, could be better written by adding a query to the repo to retrieve
			posts but only the id column and created date, then sorting by date and hydrating
			as array instead of collection. Then find array entry via id without a loop, possible
			php function, maybe array_walk?? This should return the index in the array.
			*/
			
			foreach ($topic->getPosts() as $index => $postTest)					// <------------- move this shit to the Post or TopicEntityManager?
			{
				if ($post->getId() == $postTest->getId())
				{
					$posts_per_page = $this->container->getParameter('ccdn_forum_forum.topic.posts_per_page');
					$page = ceil($index / $posts_per_page);
					break;
				}
			}
			
			$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.post.edit.success', array('%post_id%' => $post_id, '%topic_title%' => $post->getTopic()->getTitle()), 'CCDNForumForumBundle'));
				
			// redirect user on successful edit.
			return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show_paginated_anchored', 
				array('topic_id' => $topic->getId(), 'page' => $page, 'post_id' => $post->getId() ) ));
		} else {
			// setup crumb trail.
			$topic = $post->getTopic();
			$board = $topic->getBoard();
			$category = $board->getCategory();

			$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
				->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
				->add($category->getName(),	$this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
				->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
				->add($topic->getTitle(), $this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication")
				->add($this->container->get('translator')->trans('crumbs.post.edit', array(), 'CCDNForumForumBundle') . $post->getId(), $this->container->get('router')->generate('cc_forum_topic_reply', array('topic_id' => $topic->getId())), "edit");

			if ($post->getTopic()->getFirstPost()->getId() == $post->getId())
			{	// render edit_topic if first post
				return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:edit_topic.html.' . $this->getEngine(), array(
					'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
					'board' => $board,
					'topic' => $topic,
					'post' => $post,
					'crumbs' => $crumb_trail,
					'form' => $formHandler->getForm()->createView(),
				));
			} else {
				// render edit_post if not first post
				return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:edit_post.html.' . $this->getEngine(), array(
					'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
					'board' => $board,
					'topic' => $topic,
					'post' => $post,
					'crumbs' => $crumb_trail,
					'form' => $formHandler->getForm()->createView(),
				));
			}
		}
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function deleteAction($post_id)
	{
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}

		$user = $this->container->get('security.context')->getToken()->getUser();
			
		$post = $this->container->get('ccdn_forum_forum.post.repository')->findPostForEditing($post_id);
	
		if ( ! $post) {
			throw new NotFoundHttpException('No such post exists!');
		}
		
		// if this topics first post is deleted, and no
		// other posts exist then throw an NotFoundHttpException!
		if (($post->getDeletedBy() || $post->getTopic()->getDeletedBy())
		&& ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new NotFoundHttpException('No such post exists!');
		}
		
		if ($post->getTopic()->getClosedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{	// you cannot reply/edit/delete/flag a post if the topic is closed
			throw new AccessDeniedException('This topic has been closed!');
		}
		
		if ($post->getLockedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{	// you cannot reply/edit/delete/flag a post if it is locked
			throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
		}		
		
		// Invalidate this action / redirect if user should not have access to it
		if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
			// if user does not own post, or is not a mod
			if ($post->getCreatedBy()) {
				if ($post->getCreatedBy()->getId() != $user->getId())
				{
					throw new AccessDeniedException('You do not have permission to use this resource!');
				}
			} else {
				throw new AccessDeniedException('You do not have permission to use this resource!');
			}
		}	

		$topic = $post->getTopic();
		$board = $topic->getBoard();
		$category = $board->getCategory();
		
		if ($post->getTopic()->getFirstPost()->getId() == $post->getId()
		&& $post->getTopic()->getReplyCount() == 0)
		{	// if post is the very first post of the topic then use a topic handler so user can change topic title
			$confirmationMessage = 'topic.delete_topic_question';
			$crumbDelete = $this->container->get('translator')->trans('crumbs.topic.delete', array(), 'CCDNForumForumBundle');
			$pageTitle = $this->container->get('translator')->trans('title.topic.delete', array('%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle');
		} else {
			$confirmationMessage = 'post.delete_post_question';
			$crumbDelete = $this->container->get('translator')->trans('crumbs.post.delete', array(), 'CCDNForumForumBundle') . $post->getId();
			$pageTitle = $this->container->get('translator')->trans('title.post.delete', array('%post_id%' => $post->getId(), '%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle');
		}
		
		// setup crumb trail.
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(),	$this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
			->add($topic->getTitle(), $this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication")
			->add($crumbDelete, $this->container->get('router')->generate('cc_forum_topic_reply', array('topic_id' => $topic->getId())), "trash");

		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:delete_post.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'page_title' => $pageTitle,
			'confirmation_message' => $confirmationMessage,
			'topic' => $topic,
			'post' => $post,
			'crumbs' => $crumb_trail,
		));
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function deleteConfirmedAction($post_id)
	{
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();

		$post = $this->container->get('ccdn_forum_forum.post.repository')->findPostForEditing($post_id);

		if ( ! $post) {
			throw new NotFoundHttpException('No such post exists!');
		}
		
		// if this topics first post is deleted, and no
		// other posts exist then throw an NotFoundHttpException!
		if (($post->getDeletedBy() || $post->getTopic()->getDeletedBy())
		&& ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new NotFoundHttpException('No such post exists!');
		}
		
		if ($post->getTopic()->getClosedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{	// you cannot reply/edit/delete/flag a post if the topic is closed
			throw new AccessDeniedException('This topic has been closed!');
		}
		
		if ($post->getLockedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{	// you cannot reply/edit/delete/flag a post if it is locked
			throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
		}		
		
		// Invalidate this action / redirect if user should not have access to it
		if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
			// if user does not own post, or is not a mod
			if ($post->getCreatedBy()) {
				if ($post->getCreatedBy()->getId() != $user->getId())
				{
					throw new AccessDeniedException('You do not have permission to use this resource!');
				}
			} else {
				throw new AccessDeniedException('You do not have permission to use this resource!');
			}
		}	

		$this->container->get('ccdn_forum_forum.post.manager')->softDelete($post, $user)->flushNow();

		// set flash message		
		$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.post.delete.success', array('%post_id%' => $post_id), 'CCDNForumForumBundle'));
			
		// forward user
		return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $post->getTopic()->getId()) ));
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function flagAction($post_id)
	{
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to flag posts!');
		}

		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$post = $this->container->get('ccdn_forum_forum.post.repository')->find($post_id);
		
		if ( ! $post) {
			throw new NotFoundHttpException('No such post exists!');
		}
		
		// if this topics first post is deleted, and no
		// other posts exist then throw an NotFoundHttpException!
		if (($post->getDeletedBy() || $post->getTopic()->getDeletedBy())
		&& ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new NotFoundHttpException('No such post exists!');
		}
		
		if ($post->getTopic()->getClosedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{	// you cannot reply/edit/delete/flag a post if the topic is closed
			throw new AccessDeniedException('This topic has been closed!');
		}
		
		if ($post->getLockedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{	// you cannot reply/edit/delete/flag a post if it is locked
			throw new AccessDeniedException('This post has been locked and cannot be edited or deleted!');
		}
		
		$formHandler = $this->container->get('ccdn_forum_forum.flag.form.insert.handler')->setOptions(array('post' => $post, 'user' => $user));
					
		if ($formHandler->process())
		{	
			$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.post.flagged.success', array('%post_id%' => $post_id, '%topic_title%' => $post->getTopic()->getTitle()), 'CCDNForumForumBundle'));
							
			return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show_paginated_anchored', 
				array('topic_id' => $post->getTopic()->getId(), 'page' => 1, 'post_id' => $post_id) ));
		}
		else
		{		
			// setup crumb trail.
			$topic = $post->getTopic();
			$board = $topic->getBoard();
			$category = $board->getCategory();
			
			$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
				->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
				->add($category->getName(),	$this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
				->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
				->add($topic->getTitle(), $this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication")
				->add($this->container->get('translator')->trans('crumbs.post.flag', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_post_flag', array('post_id' => $post_id)), "flag");
				
			return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Post:flag.html.' . $this->getEngine(), array(
				'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
				'user' => $user,
				'topic' => $topic,
				'post' => $post,
				'crumbs' => $crumb_trail,
				'form' => $formHandler->getForm()->createView(),
			));
		}
		
	}
	
	
	/**
	 *
	 * @access protected
	 * @return string
	 */
	protected function getEngine()
    {
        return $this->container->getParameter('ccdn_forum_forum.template.engine');
    }
	
}