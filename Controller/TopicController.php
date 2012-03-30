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


use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Draft;

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
		
		$topic = $this->container->get('ccdn_forum_forum.topic.repository')->findByIdWithBoardAndCategory($topic_id);
		$posts_paginated = $this->container->get('ccdn_forum_forum.post.repository')->findPostsForTopicByIdPaginated($topic_id);
		
		$posts_per_page = $this->container->getParameter('ccdn_forum_forum.topic.posts_per_page');
		$posts_paginated->setMaxPerPage($posts_per_page);
		$posts_paginated->setCurrentPage($page, false, true);		

		if ( ! $topic || ! $posts_paginated->getCurrentPageResults())
		{
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
		$this->container->get('ccdn_forum_forum.topic.repository')->incrementViewCounter($topic);
		
		// setup crumb trail.
		$board = $topic->getBoard();
		$category = $board->getCategory();
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')	
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
			->add($topic->getTitle(), $this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:show.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'user'	=> $user,
			'crumbs' => $crumb_trail,
			'board' => $board,
			'topic' => $topic,
			'pager' => $posts_paginated,
		));
	}


	/**
	 *
	 * @access public
	 * @param $board_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function createAction($board_id, $draftId)
	{
		//
		//	Invalidate this action / redirect if user should not have access to it
		//
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$board = $this->container->get('ccdn_forum_forum.board.repository')->find($board_id);
		
		if ( ! $board) {
			throw new NotFoundHttpException('No such board exists!');
		}

		//
		// Set the form handler options
		//
		$options = array('board' => $board,	'user' => $user);
		
		//
		// Publishing drafts
		//
		if ($draftId != 0)
		{
			$draft = $this->container->get('ccdn_forum_forum.draft.manager')->getDraft($draftId);

			if (array_key_exists('post', $draft) && array_key_exists('topic', $draft))
			{
				if (is_object($draft['topic']) && $draft['topic'] instanceof Topic && is_object($draft['post']) && $draft['post'] instanceof Post)
				{
					$options['topic'] = $draft['topic'];
					$options['post'] = $draft['post'];
				} else {
					if (is_object($draft) && $draft instanceof Post)
					{
						return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_reply_from_draft', array('topic_id' => $draft->getTopic()->getId(), 'draftId' => $draft->getId()) ));
					}
				}
			}
			
		}
		
		$formHandler = $this->container->get('ccdn_forum_forum.topic.insert.form.handler')->setOptions($options);
		
		if (isset($_POST['submit_draft']))
		{
			$formHandler->setMode($formHandler::DRAFT);
			
			if ($formHandler->process())	
			{
				$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.draft.save.success', array(), 'CCDNForumForumBundle'));
			
				return new RedirectResponse($this->container->get('router')->generate('cc_forum_drafts_list'));
			}
		}
		
		if (isset($_POST['submit_preview']))
		{
			$formHandler->setMode($formHandler::PREVIEW);
		}

		if (isset($_POST['submit_post']))
		{
			$formHandler->setMode($formHandler::NORMAL);
			
			if ($formHandler->process())	
			{	
				$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.topic.create.success', array('%topic_title%' => $formHandler->getForm()->getData()->getTopic()->getTitle()), 'CCDNForumForumBundle'));
										
				return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $formHandler->getForm()->getData()->getTopic()->getId() )));
			}
		}
		
		// setup crumb trail.
		$category = $board->getCategory();
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
			->add($this->container->get('translator')->trans('crumbs.topic.create', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_topic_create', array('board_id' => $board->getId())), "edit");

		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:create.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'user' => $user,
			'crumbs' => $crumb_trail,
			'board' => $board,
			'preview' => $formHandler->getForm()->getData(),
			'form' => $formHandler->getForm()->createView(),
		));
	}
	
	
	/**
	 *
	 * @access public
	 * @param $topic_id, $quote_id
	 * @return RedirectResponse|RenderResponse
	 */	
	public function replyAction($topic_id, $quote_id, $draftId)
	{
		//
		// 	Invalidate this action / redirect if user should not have access to it
		//
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$topic = $this->container->get('ccdn_forum_forum.topic.repository')->findOneByIdJoinedToPosts($topic_id);
		
		if ( ! $topic) {
			throw new NotFoundHttpException('No such topic exists!');
		}
		
		if ($topic->getClosedBy() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			throw new AccessDeniedException('This topic has been closed!');
		}
		
		
		//
		// Set the form handler options
		//
		if ( ! empty($quote_id))
		{
			$quote = $this->container->get('ccdn_forum_forum.post.repository')->find($quote_id);		
		}

		$options = array('topic' => $topic,	'user' => $user, 'quote' => (empty($quote) ? null : $quote));
		
		//
		// Publishing drafts
		//
		if ($draftId != 0)
		{
			$draft = $this->container->get('ccdn_forum_forum.draft.manager')->getDraft($draftId);

			if (is_object($draft) && $draft instanceof Post)
			{
				$options['post'] = $draft;
			} else {
				if (is_object($draft) && $draft instanceof Topic)
				{
					return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_create_from_draft', array('board_id' => $draft->getBoard()->getId(), 'draftId' => $draft->getId()) ));
				}
			}
		}
		
		
		$formHandler = $this->container->get('ccdn_forum_forum.post.insert.form.handler')->setOptions($options);
		
		if (isset($_POST['submit_draft']))
		{
			$formHandler->setMode($formHandler::DRAFT);
			
			if ($formHandler->process())	
			{
				$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.draft.save.success', array(), 'CCDNForumForumBundle'));
			
				return new RedirectResponse($this->container->get('router')->generate('cc_forum_drafts_list'));
			}
		}
		
		if (isset($_POST['submit_preview']))
		{
			$formHandler->setMode($formHandler::PREVIEW);
		}

		if (isset($_POST['submit_post']))
		{	

			if ($formHandler->process())	
			{				
				// page of the last post
				$posts_per_topic_page = $this->container->getParameter('ccdn_forum_forum.topic.posts_per_page');
				$counters = $formHandler->getCounters();
				$page = ceil(++$counters['replyCount'] / $posts_per_topic_page);

				$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.topic.reply.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle'));
				
				return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_show_paginated_anchored', 
					array('topic_id' => $topic_id, 'page' => $page, 'post_id' => $topic->getLastPost()->getId()) ));
			}
		}
		
		// setup crumb trail.
		$board = $topic->getBoard();
		$category = $board->getCategory();
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board->getId())), "board")
			->add($topic->getTitle(), $this->container->get('router')->generate('cc_forum_topic_show', array('topic_id' => $topic->getId())), "communication")
			->add($this->container->get('translator')->trans('crumbs.topic.reply', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_topic_reply', array('topic_id' => $topic->getId())), "edit");
			
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:reply.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'user' => $user,
			'crumbs' => $crumb_trail,
			'topic' => $topic,
			'preview' => $formHandler->getForm()->getData(),
			'form' => $formHandler->getForm()->createView(),
		));
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