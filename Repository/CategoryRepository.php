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
 * CategoryRepository
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CategoryRepository extends EntityRepository
{
    /**
     *
     * @access public
     */
    public function findAllJoinedToBoard()
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
     */
    public function findCategoryByName($name)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c
                FROM CCDNForum\ForumBundle\Entity\Category c
                WHERE c.name = :name
            ')
			->setParameter('name', $name);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
	}
	
    /**
     *
     * @access public
     * @param int $categoryId
     */
    public function findOneByIdJoinedToBoard($categoryId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c, b
                FROM CCDNForum\ForumBundle\Entity\Category c
                LEFT JOIN c.boards b
                WHERE c.id = :id
                ORDER BY c.listOrderPriority, b.listOrderPriority
            ')
            ->setParameter('id', $categoryId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * for ADMIN
     *
     * @access public
     */
    public function findCategoriesOrderedByPriority()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c
                FROM CCDNForum\ForumBundle\Entity\Category c
                ORDER BY c.listOrderPriority ASC');

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * for ADMIN
     *
     * @access public
     */
    public function countCategories()
    {
		$categoryCountQuery = $this->getEntityManager()
			->createQuery('
	            SELECT COUNT(c.id)
	            FROM CCDNForum\ForumBundle\Entity\Category c
			');

        try {
            return $categoryCountQuery->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
			return 0;
        }
    }
}