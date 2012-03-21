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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class BoardController extends ContainerAware
{
	
	
	/**
	 *
	 * @access public
	 * @param $board_id, $page
	 * @return RedirectResponse|RenderResponse
	 */
	public function showAction($board_id, $page)
	{
		
		$board = $this->container->get('ccdn_forum_forum.board.repository')->findOneByIdWithCategory($board_id);

		if ($this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
		{
			$topics_paginated = $this->container->get('ccdn_forum_forum.topic.repository')->findTopicsForBoardByIdForModerators($board_id);
		} else {
			$topics_paginated = $this->container->get('ccdn_forum_forum.topic.repository')->findTopicsForBoardById($board_id);		
		}

		// deal with pagination.
		$topics_per_page = $this->container->getParameter('ccdn_forum_forum.board.topics_per_page');
		$topics_paginated->setMaxPerPage($topics_per_page);
		$topics_paginated->setCurrentPage($page, false, true);

		// check board exists.
		if ( ! $board || ! $topics_paginated->getCurrentPageResults())
		{
			throw new NotFoundHttpException('No such board exists!');
		}
		
		// this is necessary for working out the last page for each topic.
		$posts_per_page = $this->container->getParameter('ccdn_forum_forum.topic.posts_per_page');
		
		// setup bread crumbs.
		$category = $board->getCategory();
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category->getId())), "category")
			->add($board->getName(), $this->container->get('router')->generate('cc_forum_board_show', array('board_id' => $board_id)), "board");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Board:show.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'crumbs' => $crumb_trail,
			'board' => $board,
			'pager' => $topics_paginated,
			'posts_per_page' => $posts_per_page,
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