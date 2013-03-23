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
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * TopicRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @deprecated (use managers instead)
 */
class TopicRepository extends EntityRepository
{
//    /**
//     *
//     * @access public
//     * @param int $topicId
//     */
//    public function findTopicsForBoardById($boardId, $includeDeleted)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT t, fp, lp FROM CCDNForum\ForumBundle\Entity\Topic t
//                LEFT JOIN t.lastPost lp
//                LEFT JOIN lp.createdBy lpu
//                LEFT JOIN t.firstPost fp
//                LEFT JOIN fp.createdBy fpu
//                WHERE t.board = :id' .
//                    (($includeDeleted) ? null : ' AND t.isDeleted = FALSE') .
//                ' AND t.isSticky = FALSE
//                GROUP BY t.id
//                ORDER BY lp.createdDate DESC')
//            ->setParameter('id', $boardId);
//
//        try {
//            return new Pagerfanta(new DoctrineORMAdapter($query));
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * @access public
//     * @param int $topicId
//     */
//    public function findStickyTopicsForBoardById($boardId, $includeDeleted)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT t, fp, lp FROM CCDNForum\ForumBundle\Entity\Topic t
//                LEFT JOIN t.lastPost lp
//                LEFT JOIN lp.createdBy lpu
//                LEFT JOIN t.firstPost fp
//                LEFT JOIN fp.createdBy fpu
//                WHERE t.board = :id' .
//                    (($includeDeleted) ? null : ' AND t.isDeleted = FALSE') .
//                ' AND t.isSticky = TRUE
//                GROUP BY t.id
//                ORDER BY lp.createdDate DESC')
//            ->setParameter('id', $boardId);
//
//        try {
//            return $query->getResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * @access public
//     * @param int $topicId
//     */
//    public function findByIdWithBoardAndCategory($topicId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT t, b, c FROM CCDNForum\ForumBundle\Entity\Topic t
//                LEFT JOIN t.board b
//                LEFT JOIN b.category c
//                WHERE t.id = :id')
//            ->setParameter('id', $topicId);
//
//        try {
//            return $query->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * @access public
//     * @param int $topicId
//     */
//    public function findOneByIdJoinedToPosts($topicId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT t, p	FROM CCDNForum\ForumBundle\Entity\Topic t
//                INNER JOIN t.posts p
//                LEFT JOIN p.createdBy u
//                WHERE t.id = :id
//                GROUP BY p.id
//                ORDER BY p.createdDate ASC')
//            ->setParameter('id', $topicId);
//
//        try {
//            return $query->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * for moderator
//     *
//     *
//     * @access public
//     */
//    public function findClosedTopicsForModeratorsPaginated()
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT t, fp, lp, b
//                FROM CCDNForum\ForumBundle\Entity\Topic t
//                LEFT JOIN t.lastPost lp
//                LEFT JOIN t.firstPost fp
//                LEFT JOIN t.board b
//                WHERE t.isClosed = TRUE OR t.isDeleted = TRUE
//                GROUP BY t.id
//                ORDER BY lp.createdDate DESC');
//
//        try {
//            return new Pagerfanta(new DoctrineORMAdapter($query));
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * for moderator
//     *
//     *
//     * @access public
//     */
//    public function findTheseTopicsByIdForModeration($topicIds)
//    {
//        $qb = $this->getEntityManager()->createQueryBuilder();
//        $query = $qb->add('select', 't')
//            ->from('CCDNForum\ForumBundle\Entity\Topic', 't')
//            ->where($qb->expr()->in('t.id', '?1'))
//            ->setParameters(array('1' => array_values($topicIds)))
//            ->getQuery();
//
//        try {
//            return $query->getResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * @access public
//     * @param int $topicId
//     */
//    public function getFirstPostForTopic($topicId)
//    {
//        $qb = $this->getEntityManager()->createQueryBuilder();
//
//        $query = $qb
//            ->add('select', 'p')
//            ->add('from', 'CCDNForum\ForumBundle\Entity\Post p')
//            ->add('where', 'p.topic = ?1')
//            ->add('orderBy', 'p.createdDate ASC')
//            ->setMaxResults(1)
//            ->setParameter(1, $topicId)
//            ->getQuery();
//
//        try {
//            return $query->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * @access public
//     * @param int $topicId
//     */
//    public function getLastPostForTopic($topicId)
//    {
//        $qb = $this->getEntityManager()->createQueryBuilder();
//
//        $query = $qb
//            ->add('select', 'p')
//            ->add('from', 'CCDNForum\ForumBundle\Entity\Post p')
//            ->add('where', 'p.topic = ?1')
//            ->add('orderBy', 'p.createdDate DESC')
//            ->setMaxResults(1)
//            ->setParameter(1, $topicId)
//            ->getQuery();
//
//        try {
//            return $query->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
//            return null;
//        }
//    }
}