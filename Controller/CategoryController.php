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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CategoryController extends BaseController
{
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function indexAction()
    {
		$categories = $this->getCategoryManager()->findAllWithBoards();
			
        $topicsPerPage = $this->container->getParameter('ccdn_forum_forum.board.show.topics_per_page');

        $crumbs = $this->getCrumbs()
            ->add($this->trans('ccdn_forum_forum.crumbs.forum_index'), $this->path('ccdn_forum_forum_category_index'), "home");

        return $this->renderResponse('CCDNForumForumBundle:Category:index.html.', array(
            'crumbs' => $crumbs,
            'categories' => $categories,
            'topics_per_page' => $topicsPerPage,
        ));
    }

    /**
     *
     * @access public
     * @param int $categoryId
     * @return RenderResponse
     */
    public function showAction($categoryId)
    {
		$category = $this->getCategoryManager()->findOneByIdWithBoards($categoryId);
		
		$this->isFound($category);

        $topicsPerPage = $this->container->getParameter('ccdn_forum_forum.board.show.topics_per_page');

        $crumbs = $this->getCrumbs()
            ->add($this->trans('ccdn_forum_forum.crumbs.forum_index'), $this->path('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(), $this->path('ccdn_forum_forum_category_show', array('categoryId' => $categoryId)), "category");

        return $this->renderResponse('CCDNForumForumBundle:Category:show.html.', array(
            'crumbs' => $crumbs,
			'category' => $category,
            'categories' => array($category),
            'topics_per_page' => $topicsPerPage,
        ));
    }
}
