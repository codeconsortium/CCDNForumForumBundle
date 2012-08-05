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
 * FlagRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class FlagRepository extends EntityRepository
{

    /**
     *
     * @access public
     * @param int $status_code
     */
    public function findForModeratorsByStatusPaginated($status_code)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM CCDNForumForumBundle:Post p
                WHERE p.id
                IN (SELECT p1.id FROM CCDNForumForumBundle:Flag f JOIN f.post p1 WHERE f.status = :status)
            ')
            ->setParameter('status', $status_code);

        try {
            return new Pagerfanta(new DoctrineORMAdapter($query));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param Array
     */
    public function findTheseFlagsByIdForModeration($flagIds)
    {
         $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->add('select', 'f')
            ->from('CCDNForumForumBundle:Flag', 'f')
            ->where($qb->expr()->in('f.id', '?2'))
            ->setParameters(array('2' => array_values($flagIds)))
            ->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
