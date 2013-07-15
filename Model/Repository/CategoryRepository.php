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

namespace CCDNForum\ForumBundle\Model\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Repository\BaseRepository;
use CCDNForum\ForumBundle\Model\Repository\BaseRepositoryInterface;

use CCDNForum\ForumBundle\Entity\Category;

/**
 * CategoryRepository
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 */
class CategoryRepository extends BaseRepository implements BaseRepositoryInterface
{
    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllCategories()
    {
        $qb = $this->createSelectQuery(array('c'));

        $qb->addOrderBy('c.listOrderPriority', 'ASC');

        return $this->gateway->findCategories($qb);
    }

    /**
     *
     * @access public
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneCategoryById($categoryId)
    {
        if (null == $categoryId || ! is_numeric($categoryId) || $categoryId == 0) {
            throw new \Exception('Category id "' . $categoryId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('c'));

        $qb->where('c.id = :categoryId');

        return $this->gateway->findCategory($qb, array(':categoryId' => $categoryId));

        //$categories = $this->filterViewableCategoriesAndBoards($category);
        //
        //if (count($categories)) {
        //    return $categories[0];
        //} else {
        //    return null;
        //}
    }







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
}
