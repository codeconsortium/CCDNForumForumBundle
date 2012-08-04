<?php

/*
 * This file is part of the CCDN ForumBundle
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
                FROM CCDNForumForumBundle:Category c
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
     * @param int $category_id
     */
    public function findOneByIdJoinedToBoard($category_id)
    {

        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c, b
                FROM CCDNForumForumBundle:Category c
                LEFT JOIN c.boards b
                WHERE c.id = :id
                ORDER BY c.listOrderPriority, b.listOrderPriority
            ')
            ->setParameter('id', $category_id);

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
        $categories_query = $this->getEntityManager()
            ->createQuery('
                SELECT c
                FROM CCDNForumForumBundle:Category c
                ORDER BY c.listOrderPriority ASC');

        try {
            return $categories_query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
