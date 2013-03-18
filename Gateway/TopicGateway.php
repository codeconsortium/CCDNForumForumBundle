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

use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Gateway\BaseGatewayInterface;
use CCDNForum\ForumBundle\Gateway\BaseGateway;
use CCDNForum\ForumBundle\Gateway\Bag\GatewayBag;

use CCDNForum\ForumBundle\Entity\Topic;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicGateway extends BaseGateway implements BaseGatewayInterface
{
	/**
	 *
	 * @access private
	 * @var string $entityClass
	 */
	private $entityClass = 'CCDNForum\ForumBundle\Entity\Topic';
	
	/**
	 *
	 * @access private
	 * @var string $queryAlias
	 */
	private $queryAlias = 't';

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
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function findTopic(QueryBuilder $qb = null, $parameters = null)
	{
		if (null == $qb) {
			$qb = $this->createSelectQuery();
		}
				
		return $this->one($qb, $parameters);
	}
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function findTopics(QueryBuilder $qb = null, $parameters = null)
	{
		if (null == $qb) {
			$qb = $this->createSelectQuery();
		}
		
		return $this->all($qb, $parameters);
	}
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return int
	 */
	public function countTopics(QueryBuilder $qb = null, $parameters = null)
	{
		if (null == $qb) {
			$qb = $this->createCountQuery();
		}
		
		if (null == $parameters) {
			$parameters = array();
		}
		
		$qb->setParameters($parameters);

		try {
			return $qb->getQuery()->getSingleScalarResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return 0;
		}
	}
	
	/**
	 *
	 * @access public
	 * @param string $column = null
	 * @param Array $aliases = null
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
	 * @param Array $aliases = null
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
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
	 */	
	public function persistTopic(Topic $topic)
	{
		$this->persist($topic)->flush();
		
		return $this;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
	 */	
	public function updateTopic(Topic $topic)
	{
		$this->persist($topic)->flush();
		
		return $this;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
	 */	
	public function deleteTopic(Topic $topic)
	{
		$this->remove($topic)->flush();
		
		return $this;
	}
}