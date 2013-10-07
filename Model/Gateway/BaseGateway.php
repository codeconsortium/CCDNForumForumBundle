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

namespace CCDNForum\ForumBundle\Model\Gateway;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Gateway\BaseGatewayInterface;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 * @abstract
 *
 */
abstract class BaseGateway implements BaseGatewayInterface
{
    /**
     *
     * @access private
     * @var string $entityClass
     */
    protected $entityClass;

    /**
     *
     * @access protected
     * @var $paginator
     */
    protected $paginator;

    /**
     *
     * @access protected
     * @var \Doctrine\Common\Persistence\ObjectManager $em
     */
    protected $em;

    /**
     *
     * @access public
     * @param string                                     $entityClass
     * @param \Doctrine\Common\Persistence\ObjectManager $em
     * @param \Knp\Component\Pager\Paginator             $paginator
     */
    public function __construct($entityClass, ObjectManager $em, $paginator = null)
    {
        $this->entityClass = $entityClass;

        $this->em = $em;

        $this->paginator = $paginator;
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Repository\BaseRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     *
     * @access public
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->em->createQueryBuilder();
    }

    /**
     *
     * @access public
     * @param  Array                      $aliases = null
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSelectQuery(Array $aliases = null)
    {
        if (null == $aliases || ! is_array($aliases)) {
            $aliases = array($this->queryAlias);
        }

        if (! in_array($this->queryAlias, $aliases)) {
            $aliases = array($this->queryAlias) + $aliases;
        }

        return $this->getQueryBuilder()->select($aliases)->from($this->entityClass, $this->queryAlias);
    }

    /**
     *
     * @access public
     * @param  string                     $column  = null
     * @param  Array                      $aliases = null
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createCountQuery($column = null, Array $aliases = null)
    {
        if (null == $column) {
            $column = 'count(' . $this->queryAlias . '.id)';
        }

        if (null == $aliases || ! is_array($aliases)) {
            $aliases = array($column);
        }

        if (! in_array($column, $aliases)) {
            $aliases = array($column) + $aliases;
        }

        return $this->getQueryBuilder()->select($aliases)->from($this->entityClass, $this->queryAlias);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function one(QueryBuilder $qb, $parameters = array())
    {
        if (count($parameters)) {
            $qb->setParameters($parameters);
        }

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function all(QueryBuilder $qb, $parameters = array())
    {
        if (count($parameters)) {
            $qb->setParameters($parameters);
        }

        try {
            return $qb->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access protected
     * @param string                    $defaultOrderBy
     * @param string                    $direction
     * @param Doctrine\ORM\QueryBuilder $qb
     */
    protected function hasDefaultOrderBy($defaultOrderBy, $direction, QueryBuilder $qb)
    {
        $sorts = $qb->getDQLPart('orderBy');

        $isSorted = false;

        foreach ($sorts as $sort) {
            if (strstr($sort->__toString(), $defaultOrderBy)) {
                $isSorted = true;

                break;
            }
        }

        if (! $isSorted) {
            $qb->addOrderBy($defaultOrderBy, $direction);
        }
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                               $qb
     * @param  int                                                      $itemsPerPage
     * @param  int                                                      $page
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function paginateQuery(QueryBuilder $qb, $itemsPerPage, $page)
    {
        return $this->paginator->paginate($qb, $page, $itemsPerPage);
    }

    /**
     *
     * @access protected
     * @param  Object                                              $item
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    protected function persist($item)
    {
        $this->em->persist($item);

        return $this;
    }

    /**
     *
     * @access protected
     * @param  Object                                              $item
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    protected function remove($item)
    {
        $this->em->remove($item);

        return $this;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    public function flush()
    {
        $this->em->flush();

        return $this;
    }
}
