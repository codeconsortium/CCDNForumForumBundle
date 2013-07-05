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
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneById($categoryId)
    {
        if (null == $categoryId || ! is_numeric($categoryId) || $categoryId == 0) {
            throw new \Exception('Category id "' . $categoryId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('c'));

        $qb->where('c.id = :categoryId');

        $category = $this->gateway->findCategory($qb, array(':categoryId' => $categoryId));

        $categories = $this->filterViewableCategoriesAndBoards($category);

        if (count($categories)) {
            return $categories[0];
        } else {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneByIdWithBoards($categoryId)
    {
        if (null == $categoryId || ! is_numeric($categoryId) || $categoryId == 0) {
            throw new \Exception('Category id "' . $categoryId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('c', 'b', 'lp', 't', 'lp_author'));

        $qb = $this->joinToQueryBoardsAndLastPost($qb);

        $qb
            ->where('c.id = :categoryId')
            ->addOrderBy('b.listOrderPriority', 'ASC');
            ;

        $category = $this->gateway->findCategory($qb, array(':categoryId' => $categoryId));

        $categories = $this->filterViewableCategoriesAndBoards($category);

        if (count($categories)) {
            return $categories[0];
        } else {
            return null;
        }
    }

    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllCategories()
    {
        $qb = $this->createSelectQuery(array('c'));

        $qb
            ->addOrderBy('c.listOrderPriority', 'ASC')
        ;

        return $this->gateway->findCategories($qb);
    }

    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllWithBoards()
    {
        $qb = $this->createSelectQuery(array('c', 'b', 'lp', 't', 'lp_author'));

        $qb = $this->joinToQueryBoardsAndLastPost($qb);

        $qb->addOrderBy('b.listOrderPriority', 'ASC');

        $categories = $this->gateway->findCategories($qb);

        return $this->filterViewableCategoriesAndBoards($categories);
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function findAllBoardsGroupedByCategory()
    {
        $qb = $this->createSelectQuery(array('c', 'b'));

        $qb
            ->leftJoin('c.boards', 'b')
            ->addOrderBy('b.listOrderPriority', 'ASC');
        ;

        $categories = $this->gateway->findCategories($qb);

        return $this->filterViewableCategoriesAndBoards($categories);
    }

    /**
     *
     * @access protected
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function joinToQueryBoardsAndLastPost(QueryBuilder $qb)
    {
        $qb
            ->leftjoin('c.boards', 'b')
            ->leftJoin('b.lastPost', 'lp')
            ->leftJoin('lp.topic', 't')
            ->leftJoin('lp.createdBy', 'lp_author')
        ;

        return $qb;
    }

    /**
     *
     * @access public
     * @param  Array $categories
     * @return Array
     */
    public function filterViewableCategoriesAndBoards($categories)
    {
        if (! is_array($categories)) {
            if (! is_object($categories) || ! $categories instanceof Category) {
                throw new \Exception('$categories must be type of Array containing instances of \CCDNForum\ForumBundle\Entity\Category');
            }

            $categories = array($categories);
        }

        foreach ($categories as $categoryKey => $category) {
            $boards = $category->getBoards();

            foreach ($boards as $board) {
                if (! $board->isAuthorisedToRead($this->securityContext)) {
                    $categories[$categoryKey]->removeBoard($board);
                }
            }
        }

        return $categories;
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function getCategoryCount()
    {
        $qb = $this->createCountQuery();

        $qb
            ->select('COUNT(DISTINCT c.id) AS categoryCount')
        ;

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return array('categoryCount' => null);
        } catch (\Exception $e) {
            return array('categoryCount' => null);
        }
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Category              $category
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function saveNewCategory(Category $category)
    {
        $categoryCount = $this->getCategoryCount();

        $category->setListOrderPriority(++$categoryCount['categoryCount']);

        // insert a new row
        $this->persist($category);

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
}
