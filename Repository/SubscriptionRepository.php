<?php

namespace CCDNForum\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * SubscriptionRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @deprecated (use managers instead)
 */
class SubscriptionRepository extends EntityRepository
{
//    /**
//     *
//     * @access public
//     * @param int $status_code
//     */
//    public function findForUserById($userId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT s, t, fp, lp, b, c FROM CCDNForum\ForumBundle\Entity\Subscription s
//                LEFT JOIN s.topic t
//                LEFT JOIN t.lastPost lp
//                LEFT JOIN lp.createdBy lpu
//                LEFT JOIN t.firstPost fp
//                LEFT JOIN fp.createdBy fpu
//                LEFT JOIN t.board b
//                LEFT JOIN b.category c
//                WHERE s.ownedBy = :userId AND s.isSubscribed = true AND t.isDeleted != TRUE
//                GROUP BY t.id
//                ORDER BY t.id ASC')
//            ->setParameter('userId', $userId);
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
//     * @param int $topicId, int $userId
//     */
//    public function findTopicSubscriptionByTopicAndUserId($topicId, $userId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT s, t FROM CCDNForum\ForumBundle\Entity\Subscription s
//                LEFT JOIN s.topic t
//                WHERE s.topic = :topicId AND s.ownedBy = :userId')
//            ->setParameters(array('topicId' => $topicId, 'userId' => $userId));
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
//     *
//     */
//    public function getSubscriberCountForTopicById($topicId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT COUNT(s.id)
//                FROM CCDNForum\ForumBundle\Entity\Subscription s
//                WHERE s.topic = :id AND s.isSubscribed = TRUE')
//            ->setParameter('id', $topicId);
//
//        try {
//            return $query->getSingleScalarResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
}