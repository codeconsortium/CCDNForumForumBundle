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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class TopicController extends ContainerAware
{


	/**
	 *
	 * @access public
	 * @param $topic_id, $page
	 * @return RedirectResponse|RenderResponse
	 */
	public function showAction($topic_id, $page)
	{
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$topic_paginated = $this->container->get('topic.repository')->findOneByIdJoinedToPostsPaginated($topic_id);
		
		$posts_per_topic_page = $this->container->getParameter('ccdn_forum_forum.topic.posts_per_topic_page');
		$topic_paginated->setMaxPerPage($posts_per_topic_page);
		$topic_paginated->setCurrentPage($page, false, true);
		
		$topic_ = $topic_paginated->getCurrentPageResults();
		$topic = $topic_[0];
				
		if ( ! $topic) {
			throw new NotFoundHttpException('No such topic exists!');
		}
		
		// if this topics first post is deleted, and no
		// other posts exist then throw an NotFoundHttpException!
		if ($topic->getDeletedBy()
		&& ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new NotFoundHttpException('No such topic exists!');
		}
		
		// update the view counter because you viewed the topic
		$this->container->get('topic.repository')->incrementViewCounter($topic);
		
		// setup crumb trail.
		$board = $topic->getBoard();
		$category = $board->getCategory();
		
		$crumb_trail = $this->container->get('crumb_trail')	
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), 
				$this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), 
				$this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(),
				$this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
			->add($topic->getTitle(), 
				$this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:show.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'user'	=> $user,
			'crumbs' => $crumb_trail,
			'topic' => $topic,
			'board' => $board,
			'pager' => $topic_paginated,
		));
	}


	/**
	 *
	 * @access public
	 * @param $board_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function createAction($board_id)
	{
		/*
		 *	Invalidate this action / redirect if user should not have access to it
		 */
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$board = $this->container->get('board.repository')->find($board_id);
		
		if ( ! $board) {
			throw new NotFoundHttpException('No such board exists!');
		}
		
		$formHandler = $this->container->get('topic.form.insert.handler')->setOptions(array(
			'board' => $board,
			'user' => $user));
			
		$form = $formHandler->getForm();
		
		if ($formHandler->process())	
		{	
			$this->container->get('session')->setFlash('notice', 
				$this->container->get('translator')->trans('flash.topic.create.success', array('%topic_title%' => $form->getData()->getTopic()->getTitle()), 'CCDNForumForumBundle'));
										
			return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $form->getData()->getTopic()->getId() )));
		}
		else
		{
			// setup crumb trail.
			$category = $board->getCategory();
			
			$crumb_trail = $this->container->get('crumb_trail')
				->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), 
					$this->container->get('router')->generate('cc_forum_category_index'), "home")
				->add($category->getName(), 
					$this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
				->add($board->getName(), 
					$this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
				->add($this->container->get('translator')->trans('crumbs.topic.create', array(), 'CCDNForumForumBundle'), 
					$this->container->get('router')->generate('cc_forum_topic_create', array('board_id' => $board->getId())), "edit");

			return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:create.html.' . $this->getEngine(), array(
				'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
				'board' => $board,
				'crumbs' => $crumb_trail,
				'form' => $form->createView(),
			));
		}	
	}
	
	
	/**
	 *
	 * @access public
	 * @param $topic_id, $quote_id
	 * @return RedirectResponse|RenderResponse
	 */	
	public function replyAction($topic_id, $quote_id)
	{
		/*
		 *	Invalidate this action / redirect if user should not have access to it
		 */
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$topic = $this->container->get('topic.repository')->findOneByIdJoinedToPosts($topic_id);
		
		if ( ! $topic) {
			throw new NotFoundHttpException('No such topic exists!');
		}
		
		if ($topic->getClosedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new AccessDeniedException('This topic has been closed!');
		}
		
		if ( ! empty($quote_id))
		{
			$quote = $this->container->get('post.repository')->find($quote_id);
		} else {
			$quote = "";
		}
		
		$formHandler = $this->container->get('post.form.insert.handler')->setOptions(array('topic' => $topic, 'user' => $user, 'quote' => $quote));
					
		if ($formHandler->process())	
		{				
			// page of the last post
			$posts_per_topic_page = $this->container->getParameter('ccdn_forum_forum.topic.posts_per_topic_page');
			$counters = $formHandler->getCounters();
			$page = ceil(++$counters['replyCount'] / $posts_per_topic_page);

			$this->container->get('session')->setFlash('notice', 
				$this->container->get('translator')->trans('flash.topic.reply.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle'));
				
			return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show_paginated_anchored', 
				array('topic_id' => $topic_id, 'page' => $page, 'post_id' => $topic->getLastPost()->getId()) ));
		}
		else
		{
			$form = $formHandler->getForm();
			
			// setup crumb trail.
			$board = $topic->getBoard();
			$category = $board->getCategory();
			
			$crumb_trail = $this->container->get('crumb_trail')
				->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), 
					$this->container->get('router')->generate('cc_forum_category_index'), "home")
				->add($category->getName(), 
					$this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
				->add($board->getName(), 
					$this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
				->add($topic->getTitle(), 
					$this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication")
				->add($this->container->get('translator')->trans('crumbs.topic.reply', array(), 'CCDNForumForumBundle'), 
					$this->container->get('router')->generate('cc_forum_topic_reply', array('topic_id' => $topic->getId())), "edit");
				
			return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:reply.html.' . $this->getEngine(), array(
				'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
				'topic' => $topic,
				'crumbs' => $crumb_trail,
				'form' => $form->createView(),
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