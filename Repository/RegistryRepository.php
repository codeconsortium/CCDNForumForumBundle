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
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RegistryRepository extends EntityRepository
{

    /**
     *
     * @access public
     */
    public function getTableIntegrityStatus()
    {
        $queryOrphanedRegistryCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT r.id) AS orphanedRegistryCount
                FROM CCDNForumForumBundle:Registry r
                WHERE r.ownedBy IS NULL
            ');
        $queryUnsetCachedPostCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT r.id) AS unsetCachedPostCount
                FROM CCDNForumForumBundle:Registry r
                WHERE r.cachedPostCount IS NULL
            ');
        $queryUnsetCachedKarmaPositiveCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT r.id) AS unsetCachedKarmaPositiveCount
                FROM CCDNForumForumBundle:Registry r
                WHERE r.cachedKarmaPositiveCount IS NULL
            ');
        $queryUnsetCachedKarmaNegativeCount = $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(DISTINCT r.id) AS unsetCachedKarmaNegativeCount
                FROM CCDNForumForumBundle:Registry r
                WHERE r.cachedKarmaNegativeCount IS NULL
            ');

        try {
            $result['orphanedRegistryCount'] = $queryOrphanedRegistryCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['orphanedRegistryCount'] = '?';
        }

        try {
            $result['unsetCachedPostCount'] = $queryUnsetCachedPostCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetCachedPostCount'] = '?';
        }

        try {
            $result['unsetCachedKarmaPositiveCount'] = $queryUnsetCachedKarmaPositiveCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetCachedKarmaPositiveCount'] = '?';
        }

        try {
            $result['unsetCachedKarmaNegativeCount'] = $queryUnsetCachedKarmaNegativeCount->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result['unsetCachedKarmaNegativeCount'] = '?';
        }

        return $result;
    }

    /**
     *
     * @access public
     * @param int $userId
     */
    public function findRegistryRecordForUser($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT r
                FROM CCDNForumForumBundle:Registry r
                WHERE r.ownedBy = :id
                ')
            ->setParameter('id', $userId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return;
        }
    }

    /**
     *
     * @access public
	 * @param array $registryUserIds
     */
    public function getPostCountsForUsers($registryUserIds)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->add('select', 'r')
            ->from('CCDNForumForumBundle:Registry', 'r')
            ->where($qb->expr()->in('r.ownedBy', '?1'))
            ->setParameters(array('1' => array_values($registryUserIds)))
            ->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
