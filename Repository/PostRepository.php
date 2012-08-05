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
 * PostRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostRepository extends EntityRepository
{

    /**
     *
     * @access public
     */
    public function getTableIntegrityStatus()
    {
        $queryOrphanedPostCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT p.id) AS orphanedPostCount
                FROM CCDNForumForumBundle:Post p
                WHERE p.topic IS NULL
            ');
        $queryPartialLockCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT p.id) AS partialLockCount
                FROM CCDNForumForumBundle:Post p
                WHERE p.isLocked IS NULL OR (p.isLocked = FALSE AND p.lockedBy IS NOT NULL)
            ');
        $queryPartialDeletionCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT p.id) AS partialDeletionCount
                FROM CCDNForumForumBundle:Post p
                WHERE p.isDeleted IS NULL OR (p.isDeleted = FALSE AND p.deletedBy IS NOT NULL)
            ');

        try {
            $result['orphanedPostCount'] = $queryOrphanedPostCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['orphanedPostCount'] = '?';
        }

        try {
            $result['partialLockCount'] = $queryPartialLockCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['partialLockCount'] = '?';
        }

        try {
            $result['partialDeletionCount'] = $queryPartialDeletionCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['partialDeletionCount'] = '?';
        }

        return $result;
    }

    /**
     *
     * @access public
     * @param int $topic_id
     */
    public function findPostsForTopicByIdPaginated($topicId)
    {

        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, t FROM CCDNForumForumBundle:Post p
                LEFT JOIN p.topic t
                LEFT JOIN p.createdBy u
                LEFT JOIN p.editedBy eu
                LEFT JOIN p.deletedBy du
                LEFT JOIN p.lockedBy lu
                LEFT JOIN p.attachment pa
                WHERE p.topic = :id
                GROUP BY p.id
                ORDER BY p.createdDate ASC
            ')
            ->setParameter('id', $topicId);

        try {
            return new Pagerfanta(new DoctrineORMAdapter($query));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * Finds the post requested for editing joined to its
     * topic and also the post referenced as first post. This
     * is so that if first_post matches the request post by ID
     * then we know the post being edited is the topic/post, if
     * not then we know we are only editing the post alone.
     *
     * By joining in this way we avoid doing 2 queries instead of
     * just the one, as one would be needed to check if it was
     * the first post after retrieval, which is a waste.
     *
     *
     * @access public
     * @param int $post_id
     */
    public function findPostForEditing($post_id)
    {

        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, t, fp
                FROM CCDNForumForumBundle:Post p
                LEFT JOIN p.topic t
                LEFT JOIN t.firstPost fp
                WHERE p.id = :id')
            ->setParameter('id', $post_id);

        try {
            return $query->getsingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * for moderator
     *
     * @access public
     */
    public function findDeletedPostsForAdminsPaginated()
    {

        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, t
                FROM CCDNForumForumBundle:Post p
                LEFT JOIN p.topic t
                WHERE p.isDeleted = TRUE');

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
     * @access public
     */
    public function findLockedPostsForModeratorsPaginated()
    {

        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, t
                FROM CCDNForumForumBundle:Post p
                LEFT JOIN p.topic t
                WHERE p.isLocked = TRUE or t.isDeleted = TRUE
                ORDER BY p.createdDate DESC');

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
    public function findThesePostsByIdForModeration($postIds)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->add('select', 'p')
            ->from('CCDNForumForumBundle:Post', 'p')
            ->where($qb->expr()->in('p.id', '?1'))
            ->setParameters(array('1' => array_values($postIds)))
            ->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     *
     */
    public function getPostCountForUserById($user_id)
    {

        $query = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(p.id) AS postCount
                FROM CCDNForumForumBundle:Post p
                LEFT JOIN p.topic t
                WHERE p.createdBy = :id AND p.isDeleted = FALSE')
            ->setParameter('id', $user_id);

        try {
            return $query->getsingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }

    }

    /**
     *
     * @access public
     * @param int $topic_id
     */
    public function getPostCountForTopicById($topic_id)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb
            ->add('select', 'count(p.id)')
            ->add('from', 'CCDNForumForumBundle:Post p')
            ->add('where', 'p.topic = ?1')
            ->setParameter(1, $topic_id)
            ->getQuery();

        try {
            return $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
