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

namespace CCDNForum\ForumBundle\Model\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\BaseModelInterface;

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
class CategoryModel extends BaseModel implements BaseModelInterface
{
    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllCategories()
    {
        return $this->getRepository()->findAllCategories();
    }

    /**
     *
     * @access public
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneCategoryById($categoryId)
    {
        return $this->getRepository()->findOneCategoryById($categoryId);
    }

    /**
     *
     * @access public
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneById($categoryId)
    {
        return $this->getRepository()->findOneById($categoryId);
    }

    /**
     *
     * @access public
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneByIdWithBoards($categoryId)
    {
        return $this->getRepository()->findOneByIdWithBoards($categoryId);
    }

    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllWithBoards()
    {
        return $this->getRepository()->findAllWithBoards();
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function findAllBoardsGroupedByCategory()
    {
        return $this->getRepository()->findallBoardsGroupedByCategory();
    }

    /**
     *
     * @access public
     * @param  Array $categories
     * @return Array
     */
    public function filterViewableCategoriesAndBoards($categories)
    {
        return $this->getRepository()->filterViewableCategoriesAndBoards($categories);
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function getCategoryCount()
    {
        return $this->getRepository()->getCategoryCount();
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category              $category
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function saveNewCategory(Category $category)
    {
        return $this->getManager()->saveNewCategory($category);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category              $category
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateCategory(Category $category)
    {
        return $this->getManager()->updateCategory($category);
    }

    /**
     *
     * @access public
     * @param  Array                                               $categories
     * @param  int                                                 $categoryId
     * @param  string                                              $direction
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reorder($categories, $categoryId, $direction)
    {
        return $this->getManager()->reorder($categories, $categoryId, $direction);
    }
	
	public function deleteCategory(Category $category)
	{
		return $this->getManager()->deleteForum($category);
	}
}