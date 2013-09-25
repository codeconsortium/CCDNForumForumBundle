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

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Model\Repository\BaseRepository;
use CCDNForum\ForumBundle\Model\Repository\BaseRepositoryInterface;

use CCDNForum\ForumBundle\Entity\Board;

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
class BoardRepository extends BaseRepository implements BaseRepositoryInterface
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

    /**
     *
     * @access public
     * @param  int   $boardId
     * @return Array
     */
    public function getTopicAndPostCountForBoardById($boardId)
    {
        if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
            throw new \Exception('Board id "' . $boardId . '" is invalid!');
        }

        $topicEntityClass = $this->model->getModelBag()->getTopicModel()->getRepository()->getGateway()->getEntityClass();

        $qb = $this->getQueryBuilder();

        $qb
            ->select('COUNT(DISTINCT t.id) AS topicCount, COUNT(DISTINCT p.id) AS postCount')
            ->from($topicEntityClass, 't')
            ->leftJoin('t.posts', 'p')
            ->where('t.board = :boardId')
            ->andWhere('t.isDeleted = FALSE')
            ->setParameter(':boardId', $boardId)
        ;

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return array('topicCount' => null, 'postCount' => null);
        } catch (\Exception $e) {
            return array('topicCount' => null, 'postCount' => null);
        }
    }
}
