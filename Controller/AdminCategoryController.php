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
class AdminCategoryController extends BaseController
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

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.', 
			array(

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

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.', 
			array(

	        )
		);
		
		return $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.', 
			array(

	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editProcessAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.', 
			array(

	        )
		);
		
		return $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function deleteAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/delete.html.', 
			array(

	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function deleteProcessAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		return $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
    }
}