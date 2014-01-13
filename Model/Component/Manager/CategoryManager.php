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

namespace CCDNForum\ForumBundle\Model\Component\Manager;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Model\Component\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\BaseManager;

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
class CategoryManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function createCategory()
    {
        return $this->gateway->createCategory();
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category          $category
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function saveCategory(Category $category)
    {
        $this->gateway->saveCategory($category);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category          $category
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function updateCategory(Category $category)
    {
        $this->gateway->updateCategory($category);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category          $category
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
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

    /**
     *
     * @access public
     * @param  \Doctrine\Common\Collections\ArrayCollection    $boards
     * @param  \CCDNForum\ForumBundle\Entity\Category          $category
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function reassignBoardsToCategory(ArrayCollection $boards, Category $category = null)
    {
        foreach ($boards as $board) {
            $board->setCategory($category);
            $this->persist($board);
        }

        $this->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                           $categories
     * @param  \CCDNForum\ForumBundle\Entity\Category          $categoryShift
     * @param  int                                             $direction
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function reorderCategories($categories, Category $categoryShift, $direction)
    {
        $categoryCount = (count($categories) - 1);

        // Find category in collection to shift and use list order as array key for easier editing.
        $sorted = array();
        $shiftIndex = null;
        foreach ($categories as $categoryIndex => $category) {
            if ($categories[$categoryIndex]->getId() == $categoryShift->getId()) {
                $shiftIndex = $categoryIndex;
            }

            $sorted[$categoryIndex] = $category;
        }

        $incrementKeysAfterIndex = function ($index, $arr) {
            $hydrated = array();

            foreach ($arr as $key => $el) {
                if ($key > $index) {
                    $hydrated[$key + 1] = $el;
                } else {
                    $hydrated[$key] = $el;
                }
            }

            return $hydrated;
        };

        $decrementKeysBeforeIndex = function ($index, $arr) {
            $hydrated = array();

            foreach ($arr as $key => $el) {
                if ($key < $index) {
                    $hydrated[$key - 1] = $el;
                } else {
                    $hydrated[$key] = $el;
                }
            }

            return $hydrated;
        };

        $shifted = array();

        // First Category needs reordering??
        if ($shiftIndex == 0) {
            if ($direction) { // Down (move down 1)
                $shifted = $sorted;
                $shifted[$shiftIndex] = $sorted[$shiftIndex + 1];
                $shifted[$shiftIndex + 1] = $sorted[$shiftIndex];
            } else { // Up (send to bottom)
                $shifted[$categoryCount] = $sorted[0];
                unset($sorted[0]);
                $shifted = array_merge($decrementKeysBeforeIndex($categoryCount + 1, $sorted), $shifted);
            }
        } else {
            // Last category needs reordering??
            if ($shiftIndex == $categoryCount) {
                if ($direction) { // Down (send to top)
                    $shifted[0] = $sorted[$categoryCount];
                    unset($sorted[$categoryCount]);
                    $shifted = array_merge($shifted, $incrementKeysAfterIndex(-1, $sorted));
                } else { // Up (move up 1)
                    $shifted = $sorted;
                    $shifted[$shiftIndex] = $sorted[$shiftIndex - 1];
                    $shifted[$shiftIndex - 1] = $sorted[$shiftIndex];
                }
            } else {
                // Swap 2 categories not at beginning or end.
                $shifted = $sorted;
                if ($direction) { // Down (move down 1)
                    $shifted[$shiftIndex] = $sorted[$shiftIndex + 1];
                    $shifted[$shiftIndex + 1] = $sorted[$shiftIndex];
                } else { // Up (move up 1)
                    $shifted[$shiftIndex] = $sorted[$shiftIndex - 1];
                    $shifted[$shiftIndex - 1] = $sorted[$shiftIndex];
                }
            }
        }

        // Set the order from the $index arranged prior and persist.
        foreach ($shifted as $index => $category) {
            $category->setListOrderPriority($index);
            $this->persist($category);
        }

        $this->flush();

        return $this;
    }
}
