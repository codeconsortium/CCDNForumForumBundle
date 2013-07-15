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

		$categories = $this->getCategoryModel()->findAllCategories();
		
		return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/list.html.', 
			array(
				'categories' => $categories
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
		
		$formHandler = $this->getFormHandlerToCreateCategory();
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView()
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

		$formHandler = $this->getFormHandlerToCreateCategory();
		
		if ($formHandler->process($this->getRequest())) {
			return $this->redirectResponse($this->path('ccdn_forum_admin_category_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView()
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
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category
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
			return $this->redirectResponse($this->path('ccdn_forum_admin_category_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category
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
        $this->isAuthorised('ROLE_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$formHandler = $this->getFormHandlerToDeleteCategory($category);

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category
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
        $this->isAuthorised('ROLE_ADMIN');

		$category = $this->getCategoryModel()->findOneCategoryById($categoryId);
	
		$this->isFound($category);
		
		$formHandler = $this->getFormHandlerToDeleteCategory($category);

		if ($formHandler->process($this->getRequest())) {
			return $this->redirectResponse($this->path('ccdn_forum_admin_category_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Category/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'category' => $category
	        )
		);
    }
}