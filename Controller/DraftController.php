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
		$draftsPerPage = $this->container->getParameter('ccdn_forum_forum.board.topics_per_page');
		$draftsPaginated->setMaxPerPage($draftsPerPage);
		$draftsPaginated->setCurrentPage($page, false, true);
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.drafts_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_drafts_list'), "home");
//			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
//			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board_id)), "board");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Draft:list.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'crumbs' => $crumb_trail,
//			'board' => $board,
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
		
		$draft = $this->container->get('ccdn_forum_forum.draft.repository')->findOneById($draftId);

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
	public function showAction($draftId)
	{

        
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