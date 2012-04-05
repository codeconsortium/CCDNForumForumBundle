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

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Draft;


/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class DraftController extends ContainerAware
{
	
	
	public function listAction($page)
	{
		//
		//	Invalidate this action / redirect if user should not have access to it
		//
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$draftsPaginated = $this->container->get('ccdn_forum_forum.draft.repository')->findDraftsPaginated($user->getId());
		
		// deal with pagination.
		$draftsPerPage = $this->container->getParameter('ccdn_forum_forum.draft.drafts_per_page');
		$draftsPaginated->setMaxPerPage($draftsPerPage);
		$draftsPaginated->setCurrentPage($page, false, true);
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.dashboard', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_dashboard_index'), "sitemap")
			->add($this->container->get('translator')->trans('crumbs.drafts_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_drafts_list'), "home");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Draft:list.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'crumbs' => $crumb_trail,
			'pager' => $draftsPaginated,
		));
	}
	
	public function deleteAction($draftId)
	{
		//
		//	Invalidate this action / redirect if user should not have access to it
		//
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$draft = $this->container->get('ccdn_forum_forum.draft.repository')->findOneByIdForUserById($draftId, $user->getId());

		if ( ! $draft)
		{
			throw new NotFoundHttpException('No such draft exists!');			
		}
		
		if ($draft)
		{
			$this->container->get('ccdn_forum_forum.draft.manager')->remove($draft)->flushNow();
		}
	
		return new RedirectResponse($this->container->get('router')->generate('cc_forum_drafts_list'));
		
	}
	
	/**
	 *
	 * @access public
	 * @param $draftId
	 * @return RedirectResponse|RenderResponse
	 */
	public function publishAction($draftId)
	{
		//
		//	Invalidate this action / redirect if user should not have access to it
		//
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
        
		$user = $this->container->get('security.context')->getToken()->getUser();

		$draft = $this->container->get('ccdn_forum_forum.draft.repository')->findOneByIdForUserById($draftId, $user->getId());

		if ( ! $draft)
		{
			throw new NotFoundHttpException('No such draft exists!');			
		}
		
		//
		// is this a topic?
		//
		if (is_object($draft->getTopic()) && $draft->getTopic() instanceof Topic)
		{
			if ($draft->getTopic()->getId())
			{
				if ($draft->getBoard())
				{
					return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_reply_from_draft', array('topic_id' => $draft->getTopic()->getId(), 'draftId' => $draft->getId()) ));
				} else {
					$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.draft.topic_does_not_exist', array(), 'CCDNForumForumBundle'));				
				}
			} else {
				if ($draft->getBoard())
				{
					return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_create_from_draft', array('board_id' => $draft->getBoard()->getId(), 'draftId' => $draft->getId()) ));
				} else {
					$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.draft.board_does_not_exist', array(), 'CCDNForumForumBundle'));				
				}
			}
		} else {
			if ($draft->getBoard())
			{
				return new RedirectResponse($this->container->get('router')->generate('cc_forum_topic_create_from_draft', array('board_id' => $draft->getBoard()->getId(), 'draftId' => $draft->getId()) ));
			} else {
				$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.draft.board_does_not_exist', array(), 'CCDNForumForumBundle'));				
			}
		}
	
		return new RedirectResponse($this->container->get('router')->generate('cc_forum_drafts_list'));
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