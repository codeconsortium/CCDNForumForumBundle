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

namespace CCDNForum\ForumBundle\Gateway;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

use CCDNForum\ForumBundle\Gateway\BaseGatewayInterface;
use CCDNForum\ForumBundle\Gateway\Bag\GatewayBagInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @abstract
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
	 * @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
	 */
	protected $doctrine;

	/**
	 *
	 * @access protected
	 * @var \Doctrine\ORM\EntityManager $em
	 */		
	protected $em;
	
	/**
	 *
	 * @access protected
	 * @var \Doctrine\ORM\EntityRepository $repository
	 */
	protected $repository;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\Bag\GatewayBagInterface $gatewayBag
	 */
	protected $gatewayBag;
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
	 * @param \Doctrine\ORM\EntityRepository $repository
	 * @param \CCDNForum\ForumBundle\Gateway\Bag\GatewayBagInterface $gatewayBag
	 * @param string $entityClass
	 */
	public function __construct(Registry $doctrine, $repository, GatewayBagInterface $gatewayBag, $entityClass)
	{
		$this->doctrine = $doctrine;
		
		$this->em = $doctrine->getEntityManager();
		
		$this->repository = $repository;
		
		$this->gatewayBag = $gatewayBag;
		
		$this->entityClass = $entityClass;
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
	 * @return \Doctrine\ORM\EntityRepository
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
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
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
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
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
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param int $itemsPerPage
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */
	public function paginateQuery(QueryBuilder $qb, $itemsPerPage, $page)
	{
        try {
            $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        } catch (\Exception $e) {
        	return null;
        }
		
        $pager->setMaxPerPage($itemsPerPage);
        $pager->setCurrentPage($page, false, true);
		
		return $pager;
	}
	
	/**
	 *
	 * @access protected
	 * @param $item
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
	 * @param $item
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