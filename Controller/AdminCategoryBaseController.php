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

use CCDNForum\ForumBundle\Entity\Category;

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
class AdminCategoryBaseController extends BaseController
{
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\CategoryCreateFormHandler
	 */
	public function getFormHandlerToCreateCategory()
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.category_create');

	    return $formHandler;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\CategoryUpdateFormHandler
	 */
	public function getFormHandlerToUpdateCategory(Category $category)
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.category_update');

		$formHandler->setCategory($category);
		
	    return $formHandler;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\CategoryDeleteFormHandler
	 */
	public function getFormHandlerToDeleteCategory(Category $category)
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.category_delete');

		$formHandler->setCategory($category);
		
	    return $formHandler;
	}
}
