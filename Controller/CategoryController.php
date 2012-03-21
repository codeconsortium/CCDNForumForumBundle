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
class CategoryController extends ContainerAware
{


	/**
	 *
	 * @access public
	 * @return RedirectResponse|RenderResponse
	 */	
    public function indexAction()
    {
		$categories = $this->container->get('ccdn_forum_forum.category.repository')->findAllJoinedToBoard();
		
		$topics_per_page = $this->container->getParameter('ccdn_forum_forum.board.topics_per_page');
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Category:index.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'crumbs' => $crumb_trail,
			'categories' => $categories,
			'topics_per_page' => $topics_per_page,
			));
    }


	/**
	 *
	 * @access public
	 * @param $category_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function showAction($category_id)
	{
			
		$category = $this->container->get('ccdn_forum_forum.category.repository')->findOneByIdJoinedToBoard($category_id);
		
		if ( ! $category) {
			throw NotFoundhttpException('No such category exists!');
		}
		
		$topics_per_page = $this->container->getParameter('ccdn_forum_forum.board.topics_per_page');
		
		$crumb_trail = $this->container->get('ccdn_component_crumb_trail.crumb_trail')
			->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('cc_forum_category_index'), "home")
			->add($category->getName(), $this->container->get('router')->generate('cc_forum_category_show', array('category_id' => $category_id)), "category");
		
		return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Category:show.html.' . $this->getEngine(), array(
			'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
			'crumbs' => $crumb_trail,
			'category' => $category,
			'topics_per_page' => $topics_per_page,
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