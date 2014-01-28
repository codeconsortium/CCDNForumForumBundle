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

namespace CCDNForum\ForumBundle\Model\Component\Repository;

use CCDNForum\ForumBundle\Model\Component\Repository\Repository;
use CCDNForum\ForumBundle\Model\Component\Repository\RepositoryInterface;

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
class CategoryRepository extends BaseRepository implements RepositoryInterface
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
     * @param  int                                          $forumId
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllCategoriesForForumById($forumId)
    {
        $params = array();

        $qb = $this->createSelectQuery(array('c'));

        if ($forumId == null) {
            $qb->where('c.forum IS NULL');
        } else {
            $params[':forumId'] = $forumId;
            $qb->where('c.forum = :forumId');
        }

        $qb->addOrderBy('c.listOrderPriority', 'ASC');

        return $this->gateway->findCategories($qb, $params);
    }

    /**
     *
     * @access public
     * @param  string                                       $forumName
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllCategoriesWithBoardsForForumByName($forumName)
    {
        $params = array();

        $qb = $this->createSelectQuery(array('c', 'f', 'b', 't', 'lp', 'lp_author'));

        $params[':forumName'] = $forumName;

        $qb
            ->leftJoin('c.forum', 'f')
            ->leftJoin('c.boards', 'b')
            ->leftJoin('b.lastPost', 'lp')
            ->leftJoin('lp.topic', 't')
            ->leftJoin('lp.createdBy', 'lp_author')
            ->where('f.name = :forumName')
            ->addOrderBy('c.listOrderPriority', 'ASC')
        ;

        return $this->gateway->findCategories($qb, $params);
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
    }

    /**
     *
     * @access public
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneCategoryByIdWithBoards($categoryId)
    {
        if (null == $categoryId || ! is_numeric($categoryId) || $categoryId == 0) {
            throw new \Exception('Category id "' . $categoryId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('c', 'b', 'lp', 't', 'lp_author'));

        $qb
            ->leftjoin('c.boards', 'b')
            ->leftJoin('b.lastPost', 'lp')
            ->leftJoin('lp.topic', 't')
            ->leftJoin('lp.createdBy', 'lp_author')
            ->where('c.id = :categoryId')
            ->addOrderBy('b.listOrderPriority', 'ASC');
        ;

        return $this->gateway->findCategory($qb, array(':categoryId' => $categoryId));
    }
}
