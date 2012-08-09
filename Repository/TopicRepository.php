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
 */
class TopicRepository extends EntityRepository
{

    /**
     *
     * @access public
     */
    public function getTableIntegrityStatus()
    {
        $queryOrphanedTopicCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS orphanedTopicCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.board IS NULL
            ');
        $queryUnlinkedFirstPostCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS unlinkedFirstPostCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.firstPost IS NULL AND t.cachedReplyCount > 0
            ');
        $queryUnlinkedLastPostCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS unlinkedLastPostCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.lastPost IS NULL AND t.cachedReplyCount > 0
            ');
        $queryPartialClosureCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS partialClosureCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.isClosed IS NULL OR (t.isClosed = FALSE AND t.closedBy IS NOT NULL)
            ');
        $queryPartialDeletionCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS partialDeletionCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.isDeleted IS NULL OR (t.isDeleted = FALSE AND t.deletedBy IS NOT NULL)
            ');
//		$queryPartialStickyCount = $this->getEntityManager()
//			->createQuery('
//				SELECT COUNT(DISTINCT t.id) AS partialStickyCount
//				FROM CCDNForumForumBundle:Topic t
//				WHERE b.is_sticky IS NULL OR (t.is_sticky = FALSE AND t.stickied_by IS NOT NULL)
//			');
        $queryUnsetReplyCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS unsetReplyCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.cachedReplyCount IS NULL
            ');
        $queryUnsetViewCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT t.id) AS unsetViewCount
                FROM CCDNForumForumBundle:Topic t
                WHERE t.cachedViewCount IS NULL
            ');

        try {
            $result['orphanedTopicCount'] = $queryOrphanedTopicCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['orphanedTopicCount'] = '?';
        }

        try {
            $result['unlinkedFirstPostCount'] = $queryUnlinkedFirstPostCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unlinkedFirstPostCount'] = '?';
        }

        try {
            $result['unlinkedLastPostCount'] = $queryUnlinkedLastPostCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unlinkedLastPostCount'] = '?';
        }

        try {
            $result['partialClosureCount'] = $queryPartialClosureCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['partialClosureCount'] = '?';
        }

        try {
            $result['partialDeletionCount'] = $queryPartialDeletionCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['partialDeletionCount'] = '?';
        }

        try {
            $result['unsetReplyCount'] = $queryUnsetReplyCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetReplyCount'] = '?';
        }

        try {
            $result['unsetViewCount'] = $queryUnsetViewCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetViewCount'] = '?';
        }

        return $result;
    }

    /**
     *
     * @access public
     * @param int $topicId
     */
    public function findTopicsForBoardById($boardId, $includeDeleted)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
                LEFT JOIN t.lastPost lp
                LEFT JOIN lp.createdBy lpu
                LEFT JOIN t.firstPost fp
                LEFT JOIN fp.createdBy fpu
                WHERE t.board = :id' .
                    (($includeDeleted) ? null : ' AND t.isDeleted = FALSE') .
                ' AND t.isSticky = FALSE
                GROUP BY t.id
                ORDER BY lp.createdDate DESC')
            ->setParameter('id', $boardId);

        try {
            return new Pagerfanta(new DoctrineORMAdapter($query));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param int $topicId
     */
    public function findStickyTopicsForBoardById($boardId, $includeDeleted)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
                LEFT JOIN t.lastPost lp
                LEFT JOIN lp.createdBy lpu
                LEFT JOIN t.firstPost fp
                LEFT JOIN fp.createdBy fpu
                WHERE t.board = :id' .
                    (($includeDeleted) ? null : ' AND t.isDeleted = FALSE') .
                ' AND t.isSticky = TRUE
                GROUP BY t.id
                ORDER BY lp.createdDate DESC')
            ->setParameter('id', $boardId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param int $topicId
     */
    public function findByIdWithBoardAndCategory($topicId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, b, c FROM CCDNForumForumBundle:Topic t
                LEFT JOIN t.board b
                LEFT JOIN b.category c
                WHERE t.id = :id')
            ->setParameter('id', $topicId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param Int $topicId
     */
    public function findOneByIdJoinedToPosts($topicId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, p	FROM CCDNForumForumBundle:Topic t
                INNER JOIN t.posts p
                LEFT JOIN p.createdBy u
                WHERE t.id = :id
                GROUP BY p.id
                ORDER BY p.createdDate ASC')
            ->setParameter('id', $topicId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * for moderator
     *
     *
     * @access public
     */
    public function findClosedTopicsForModeratorsPaginated()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, fp, lp, b
                FROM CCDNForumForumBundle:Topic t
                LEFT JOIN t.lastPost lp
                LEFT JOIN t.firstPost fp
                LEFT JOIN t.board b
                WHERE t.isClosed = TRUE OR t.isDeleted = TRUE
                GROUP BY t.id
                ORDER BY lp.createdDate DESC');

        try {
            return new Pagerfanta(new DoctrineORMAdapter($query));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * for moderator
     *
     *
     * @access public
     */
    public function findTheseTopicsByIdForModeration($topicIds)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->add('select', 't')
            ->from('CCDNForumForumBundle:Topic', 't')
            ->where($qb->expr()->in('t.id', '?1'))
            ->setParameters(array('1' => array_values($topicIds)))
            ->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param Int $topicId
     */
    public function getFirstPostForTopic($topicId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb
            ->add('select', 'p')
            ->add('from', 'CCDNForumForumBundle:Post p')
            ->add('where', 'p.topic = ?1')
            ->add('orderBy', 'p.createdDate ASC')
            ->setMaxResults(1)
            ->setParameter(1, $topicId)
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param Int $topicId
     */
    public function getLastPostForTopic($topicId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb
            ->add('select', 'p')
            ->add('from', 'CCDNForumForumBundle:Post p')
            ->add('where', 'p.topic = ?1')
            ->add('orderBy', 'p.createdDate DESC')
            ->setMaxResults(1)
            ->setParameter(1, $topicId)
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
            return null;
        }
    }

}
