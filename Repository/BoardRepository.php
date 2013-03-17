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
    public function findAllBoardsGroupedByCategoryHydratedAsArray()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c, b
                FROM CCDNForum\ForumBundle\Entity\Category c
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
     * @param int $boardId
     */
    public function findOneByIdWithCategory($boardId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT b, c FROM CCDNForum\ForumBundle\Entity\Board b
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
     * @param int $boardId
     */
    public function getTopicAndPostCountsForBoard($boardId)
    {
        // get topic / post count
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS topicCount, COUNT(DISTINCT p.id) AS postCount
                FROM CCDNForum\ForumBundle\Entity\Topic t
                LEFT JOIN t.posts p
                WHERE t.board = :id AND t.isDeleted = FALSE AND p.isDeleted = FALSE
                GROUP BY t.board')
            ->setParameter('id', $boardId);

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
     * @param int $categoryId
     */
    public function findBoardsOrderedByPriorityInCategory($categoryId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT b
                FROM CCDNForum\ForumBundle\Entity\Board b
                WHERE b.category = :id
                ORDER BY b.listOrderPriority ASC
                ')
            ->setParameter('id', $categoryId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param int $boardId
     */
    public function findLastTopicForBoard($boardId)
    {
        $lastPost_query = $this->getEntityManager()
            ->createQuery('
                SELECT t, lp
                FROM CCDNForum\ForumBundle\Entity\Topic t
                LEFT JOIN t.lastPost lp
                WHERE t.board = :id AND t.isDeleted = FALSE
                GROUP BY t.id
                ORDER BY lp.createdDate DESC
                ')
            ->setParameter('id', $boardId)
            ->setMaxResults(1);

        try {
            return $lastPost_query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param int $boardId
     */
    public function countBoardsForCategory($categoryId)
    {
        $boardCountQuery = $this->getEntityManager()
			->createQuery('
	            SELECT COUNT(b.id)
	            FROM CCDNForum\ForumBundle\Entity\Board b
	            WHERE b.category = :id
            ')
            ->setParameter('id', $categoryId);

        try {
            return $boardCountQuery->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
			return 0;
        }
    }
}