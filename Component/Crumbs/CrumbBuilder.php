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

namespace CCDNForum\ForumBundle\Component\Crumbs;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;

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
class CrumbBuilder extends BaseCrumbBuilder
{
	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminIndex()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
		;
	}

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageForumsIndex()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-forums.index', 'ccdn_forum_admin_forum_list')
		;
	}

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageForumsCreate()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-forums.index', 'ccdn_forum_admin_forum_list')
			->add('crumbs.admin.manage-forums.create', 'ccdn_forum_admin_forum_create')
		;
	}

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Forum $forum
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageForumsEdit(Forum $forum)
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-forums.index', 'ccdn_forum_admin_forum_list')
			->add(
				array(
					'label' => 'crumbs.admin.manage-forums.edit',
					'params' => array(
						'%forum_name%' => $forum->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_admin_forum_edit',
					'params' => array(
						'forumId' => $forum->getId()
					)
				)
			)
		;
	}

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Forum $forum
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageForumsDelete(Forum $forum)
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-forums.index', 'ccdn_forum_admin_forum_list')
			->add(
				array(
					'label' => 'crumbs.admin.manage-forums.delete',
					'params' => array(
						'%forum_name%' => $forum->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_admin_forum_delete',
					'params' => array(
						'forumId' => $forum->getId()
					)
				)
			)
		;
	}

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageCategoriesIndex()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-categories.index', 'ccdn_forum_admin_category_list')
		;
	}

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageCategoriesCreate()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-categories.index', 'ccdn_forum_admin_category_list')
			->add('crumbs.admin.manage-categories.create', 'ccdn_forum_admin_category_create')
		;
	}

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Category $category
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageCategoriesEdit(Category $category)
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-categories.index', 'ccdn_forum_admin_category_list')
			->add(
				array(
					'label' => 'crumbs.admin.manage-categories.edit',
					'params' => array(
						'%category_name%' => $category->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_admin_category_edit',
					'params' => array(
						'categoryId' => $category->getId()
					)
				)
			)
		;
	}

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Category $category
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageCategoriesDelete(Category $category)
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-categories.index', 'ccdn_forum_admin_category_list')
			->add(
				array(
					'label' => 'crumbs.admin.manage-categories.delete',
					'params' => array(
						'%category_name%' => $category->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_admin_category_delete',
					'params' => array(
						'categoryId' => $category->getId()
					)
				)
			)
		;
	}

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageBoardsIndex()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-boards.index', 'ccdn_forum_admin_board_list')
		;
	}

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageBoardsCreate()
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-boards.index', 'ccdn_forum_admin_board_list')
			->add('crumbs.admin.manage-boards.create', 'ccdn_forum_admin_board_create')
		;
	}

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageBoardsEdit(Board $board)
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-boards.index', 'ccdn_forum_admin_board_list')
			->add(
				array(
					'label' => 'crumbs.admin.manage-boards.edit',
					'params' => array(
						'%board_name%' => $board->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_admin_board_edit',
					'params' => array(
						'boardId' => $board->getId()
					)
				)
			)
		;
	}

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addAdminManageBoardsDelete(Board $board)
	{
        return $this->createCrumbTrail()
            ->add('crumbs.admin.index', 'ccdn_forum_admin_index')
			->add('crumbs.admin.manage-boards.index', 'ccdn_forum_admin_board_list')
			->add(
				array(
					'label' => 'crumbs.admin.manage-boards.delete',
					'params' => array(
						'%board_name%' => $board->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_admin_board_delete',
					'params' => array(
						'boardId' => $board->getId()
					)
				)
			)
		;
	}
	
	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Forum                         $forum
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addUserCategoryIndex(Forum $forum)
	{
        return $this->createCrumbTrail()
			->add(
				array(
					'label' => 'crumbs.user.category.index',
					'params' => array(
						'%forum_name%' => $forum->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_user_category_index',
					'params' => array(
						'forumName' => $forum->getName()
					)
				)
			)
		;
	}
	
	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Forum                         $forum
	 * @param \CCDNForum\ForumBundle\Entity\Category                      $category
	 * @return \CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbTrail
	 */
	public function addUserCategoryShow(Forum $forum, Category $category)
	{
        return $this->createCrumbTrail()
			->add(
				array(
					'label' => 'crumbs.user.category.index',
					'params' => array(
						'%forum_name%' => $forum->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_user_category_index',
					'params' => array(
						'forumName' => $forum->getName()
					)
				)
			)
			->add(
				array(
					'label' => 'crumbs.user.category.show',
					'params' => array(
						'%category_name%' => $category->getName()
					)
				),
				array(
					'route' => 'ccdn_forum_user_category_show',
					'params' => array(
						'categoryId' => $category->getId()
					)
				)
			)
		;
	}
}