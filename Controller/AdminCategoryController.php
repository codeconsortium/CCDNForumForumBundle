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
class AdminCategoryController extends AdminCategoryBaseController
{
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function listAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		$forumFilter = $this->getQuery('forum_filter', null);

		$forums = $this->getForumModel()->findAllForums();

		$categories = $this->getCategoryModel()->findAllCategoriesForForum($forumFilter);
		
		return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/list.html.', 
			array(
				'forums' => $forums,
				'forum_filter' => $forumFilter,
				'categories' => $categories,
	        )
		);
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createAction()
    {
        $this->isAuthorised('ROLE_ADMIN');
		
		$forumFilter = $this->getQuery('forum_filter', null);
		
		$formHandler = $this->getFormHandlerToCreateCategory($forumFilter);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum_filter' => $forumFilter
	        )
		);
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createProcessAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		$forumFilter = $this->getQuery('forum_filter', null);

		$formHandler = $this->getFormHandlerToCreateCategory($forumFilter);
		
		if ($formHandler->process($this->getRequest())) {
			
			$params = $this->getFilterQueryStrings($formHandler->getForm()->getData());
			
			return $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum_filter' => $forumFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editAction($categoryId)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$formHandler = $this->getFormHandlerToUpdateCategory($category);
		
		$forumFilter = $this->getQuery('forum_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category,
				'forum_filter' => $forumFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editProcessAction($categoryId)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$formHandler = $this->getFormHandlerToUpdateCategory($category);

		if ($formHandler->process($this->getRequest())) {
			
			$params = $this->getFilterQueryStrings($category);
			
			return $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
		}
		
		$forumFilter = $this->getQuery('forum_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category,
				'forum_filter' => $forumFilter
	        )
		);
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function deleteAction($categoryId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$formHandler = $this->getFormHandlerToDeleteCategory($category);

		$forumFilter = $this->getQuery('forum_filter', null);

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category,
				'forum_filter' => $forumFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function deleteProcessAction($categoryId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$formHandler = $this->getFormHandlerToDeleteCategory($category);

		if ($formHandler->process($this->getRequest())) {
			
			$params = $this->getFilterQueryStrings($category);
			
			return $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
		}
		
		$forumFilter = $this->getQuery('forum_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category,
				'forum_filter' => $forumFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function reorderAction($categoryId, $direction)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$params = array();
		
		// We do not re-order categories not set to a forum.
		if ($category->getForum()) {
			$forumFilter = $category->getForum()->getId();
			
			$params['forum_filter'] = $forumFilter;
		
			$categories = $this->getCategoryModel()->findAllCategoriesForForum($forumFilter);
			
			$this->getCategoryModel()->reorderCategories($categories, $category, $direction);
		}
	
        return $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
    }
}