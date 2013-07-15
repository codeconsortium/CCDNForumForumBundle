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

namespace CCDNForum\ForumBundle\Model\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

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
class CategoryManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category              $category
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function saveNewCategory(Category $category)
    {
        $categoryCount = $this->model->getCategoryCount();

        $category->setListOrderPriority(++$categoryCount['categoryCount']);

        // insert a new row
        $this->persist($category)->flush();

		$this->refresh($category);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category              $category
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateCategory(Category $category)
    {
        // update a record
        $this->persist($category);

        return $this;
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
        $categoryCount = count($categories);
        for ($index = 0, $priority = 1, $align = false; $index < $categoryCount; $index++, $priority++) {
            if ($categories[$index]->getId() == $categoryId) {
                if ($align == false) { // if aligning then other indices priorities are being corrected
                    // **************
                    // **** DOWN ****
                    // **************
                    if ($direction == 'down') {
                        if ($index < ($categoryCount - 1)) { // <-- must be lower because we need to alter an offset of the next index.
                            $categories[$index]->setListOrderPriority($priority+1); // move this down the page
                            $categories[$index+1]->setListOrderPriority($priority); // move this up the page
                            $index+=1; $priority++; // the next index has already been changed
                        } else {
                            $categories[$index]->setListOrderPriority(1); // move to the top of the page
                            $index = -1; $priority = 1; // alter offsets for alignment of all other priorities
                        }
                    // **************
                    // ***** UP *****
                    // **************
                    } else {
                        if ($index > 0) {
                            $categories[$index]->setListOrderPriority($priority-1); // move this up the page
                            $categories[$index-1]->setListOrderPriority($priority); // move this down the page
                            $index+=1; $priority++;
                        } else {
                            $categories[$index]->setListOrderPriority($categoryCount); // move to the bottom of the page
                            $index = -1; $priority = -1; // alter offsets for alignment of all other priorities
                        }
                    } // end down / up direction
                    $align = true; continue;
                }// end align
            } else {
                $categories[$index]->setListOrderPriority($priority);
            } // end category id match
        } // end loop

        foreach ($categories as $category) { $this->persist($category); }

        $this->flush();

        return $this;
    }
	
	public function deleteCategory(Category $category)
	{
		// If we do not refresh the category, AND we have reassigned the boards to null, 
		// then its lazy-loaded boards are dirty, as the boards in memory will still
		// have the old category id set. Removing the category will cascade into deleting
		// boards aswell, even though in the db the relation has been set to null.
		$this->refresh($category);
		
		$this->remove($category)->flush();
		
		return $this;
	}

	public function reassignBoardsToCategory(ArrayCollection $boards, Category $category = null)
	{
		foreach ($boards as $board) {
			$board->setCategory(null);
			
			$this->persist($board);
		}

		$this->flush();
		
		return $this;
	}
}
