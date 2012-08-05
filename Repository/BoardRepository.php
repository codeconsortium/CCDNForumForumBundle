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

namespace CCDNForum\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BoardRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BoardRepository extends EntityRepository
{

    /**
     *
     * @access public
     */
    public function getTableIntegrityStatus()
    {
        $queryOrphanedBoardCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT b.id) AS orphanedBoardCount
                FROM CCDNForumForumBundle:Board b
                WHERE b.category IS NULL
            ');
        $queryUnlinkedLastPostCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT b.id) AS unlinkedLastPostCount
                FROM CCDNForumForumBundle:Board b
                WHERE b.lastPost IS NULL AND b.cachedTopicCount > 0
            ');
        $queryUnsetTopicCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT b.id) AS unsetTopicCount
                FROM CCDNForumForumBundle:Board b
                WHERE b.cachedTopicCount IS NULL
            ');
        $queryUnsetPostCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT b.id) AS unsetPostCount
                FROM CCDNForumForumBundle:Board b
                WHERE b.cachedPostCount IS NULL
            ');

        try {
            $result['orphanedBoardCount'] = $queryOrphanedBoardCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['orphanedBoardCount'] = '?';
        }

        try {
            $result['unlinkedLastPostCount'] = $queryUnlinkedLastPostCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unlinkedLastPostCount'] = '?';
        }

        try {
            $result['unsetTopicCount'] = $queryUnsetTopicCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetTopicCount'] = '?';
        }

        try {
            $result['unsetPostCount'] = $queryUnsetPostCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetPostCount'] = '?';
        }

        return $result;
    }

    /**
     *
     * @access public
     */
    public function findAllBoardsGroupedByCategoryHydratedAsArray()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c, b
                FROM CCDNForumForumBundle:Category c
                LEFT JOIN c.boards b
                ORDER BY c.listOrderPriority, b.listOrderPriority
            ');

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param int $board_id
     */
    public function findOneByIdWithCategory($boardId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT b, c FROM CCDNForumForumBundle:Board b
                LEFT JOIN b.category c
                WHERE b.id = :id')
            ->setParameter('id', $boardId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * When a new topic is created we need to get the TopicCounts and
     * PostCounts for board so that the TopicManager can update the
     * board counters.
     *
     *
     * @access public
     * @param int $board_id
     */
    public function getTopicAndPostCountsForBoard($board_id)
    {
        // get topic / post count
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS topicCount, COUNT(DISTINCT p.id) AS postCount
                FROM CCDNForumForumBundle:Topic t
                LEFT JOIN t.posts p
                WHERE t.board = :id AND t.isDeleted = FALSE AND p.isDeleted = FALSE
                GROUP BY t.board')
            ->setParameter('id', $board_id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return;
        }
    }

    /**
     *
     * for adminBundle
     *
     * @access public
     * @param int $category_id
     */
    public function findBoardsOrderedByPriorityInCategory($category_id)
    {

        $boards_query = $this->getEntityManager()
            ->createQuery('
                SELECT b
                FROM CCDNForumForumBundle:Board b
                WHERE b.category = :id
                ORDER BY b.listOrderPriority ASC
                ')
            ->setParameter('id', $category_id);

        try {
            return $boards_query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param int $board_id
     */
    public function findLastTopicForBoard($board_id)
    {
        $lastPost_query = $this->getEntityManager()
            ->createQuery('
                SELECT t, lp
                FROM CCDNForumForumBundle:Topic t
                LEFT JOIN t.lastPost lp
                WHERE t.board = :id AND t.isDeleted = FALSE
                GROUP BY t.id
                ORDER BY lp.createdDate DESC
                ')
            ->setParameter('id', $board_id)
            ->setMaxResults(1);

        try {
            return $lastPost_query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
