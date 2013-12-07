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
 * BoardRepository
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 */
class BoardRepository extends BaseRepository implements RepositoryInterface
{
    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllBoards()
    {
        $qb = $this->createSelectQuery(array('b'));

        $qb->addOrderBy('b.listOrderPriority', 'ASC');

        return $this->gateway->findBoards($qb);
    }

    /**
     *
     * @access public
     * @param  int                                          $categoryId
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllBoardsForCategoryById($categoryId)
    {
        $params = array();

        $qb = $this->createSelectQuery(array('b'));

        if ($categoryId == null) {
            $qb->where('b.category IS NULL');
        } else {
            $params[':categoryId'] = $categoryId;
            $qb->where('b.category = :categoryId');
        }

        $qb->addOrderBy('b.listOrderPriority', 'ASC');

        return $this->gateway->findBoards($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int                                          $forumId
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllBoardsForForumById($forumId)
    {
        $params = array();

        $qb = $this->createSelectQuery(array('b'));

        $params[':forumId'] = $forumId;

        $qb
            ->leftJoin('b.category', 'c')
            ->leftJoin('c.forum', 'f')
            ->where('f.id = :forumId')
        ;

        $qb->addOrderBy('b.listOrderPriority', 'ASC');

        return $this->gateway->findBoards($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int                                 $boardId
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function findOneBoardById($boardId)
    {
        if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
            throw new \Exception('Board id "' . $boardId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('b'));

        $qb->where('b.id = :boardId');

        return $this->gateway->findBoard($qb, array(':boardId' => $boardId));
    }

    /**
     *
     * @access public
     * @param  int                                 $boardId
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function findOneBoardByIdWithCategory($boardId)
    {
        if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
            throw new \Exception('Board id "' . $boardId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('b', 'c'));

        $qb
            ->leftJoin('b.category', 'c')
            ->where('b.id = :boardId')
        ;

        return $this->gateway->findBoard($qb, array(':boardId' => $boardId));
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function getBoardCount()
    {
        $qb = $this->createCountQuery();

        $qb
            ->select('COUNT(DISTINCT b.id) AS boardCount')
        ;

        try {
            $num = $qb->getQuery()->getSingleResult();

            return $num['boardCount'];
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
