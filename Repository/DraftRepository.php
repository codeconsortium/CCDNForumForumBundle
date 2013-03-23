<?php

namespace CCDNForum\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * DraftRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @deprecated (use managers instead)
 */
class DraftRepository extends EntityRepository
{
//    /**
//     *
//     * @access public
//     * @param  int $userId
//     * @return Collection
//     */
//    public function findDraftsPaginated($userId)
//    {
//        $draftsQuery = $this->getEntityManager()
//            ->createQuery('
//                SELECT d
//                FROM CCDNForum\ForumBundle\Entity\Draft d
//                WHERE d.createdBy = :id
//                ORDER BY d.createdDate ASC
//                ')
//            ->setParameter('id', $userId);
//
//        try {
//            return new Pagerfanta(new DoctrineORMAdapter($draftsQuery));
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
//
//    /**
//     *
//     * @access public
//     * @param  int $draftId, int $userId
//     * @return null|Draft
//     */
//    public function findOneByIdForUserById($draftId, $userId)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery('
//                SELECT d
//                FROM CCDNForum\ForumBundle\Entity\Draft d
//                WHERE d.id = :draftId AND d.createdBy = :userId')
//            ->setParameters(array('draftId' => $draftId, 'userId' => $userId));
//
//        try {
//            return $query->getsingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
}