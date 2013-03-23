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

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

/**
 * RegistryRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @deprecated (use managers instead)
 */
class RegistryRepository extends EntityRepository
{
//    /**
//     *
//     * @access public
//     * @param int $userId
//     */
//    public function findRegistryRecordForUser($userId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT r
//                FROM CCDNForum\ForumBundle\Entity\Registry r
//                WHERE r.ownedBy = :id
//                ')
//            ->setParameter('id', $userId);
//
//        try {
//            return $query->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return;
//        }
//    }
//
//    /**
//     *
//     * @access public
//	 * @param array $registryUserIds
//     */
//    public function getPostCountsForUsers($registryUserIds)
//    {
//        $qb = $this->getEntityManager()->createQueryBuilder();
//        $query = $qb
//            ->add('select', 'r')
//            ->from('CCDNForum\ForumBundle\Entity\Registry', 'r')
//            ->where($qb->expr()->in('r.ownedBy', '?1'))
//            ->setParameters(array('1' => array_values($registryUserIds)))
//            ->getQuery();
//
//        try {
//            return $query->getResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
}