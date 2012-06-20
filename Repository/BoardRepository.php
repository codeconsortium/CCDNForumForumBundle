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
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * BoardRepository
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class BoardRepository extends EntityRepository
{

	
/*	public function findAllHydratedAsArray()
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT b, c FROM CCDNForumForumBundle:Board b
				INNER JOIN b.category c
				GROUP BY c.id
			');
			
		try {
			$results = $query->getResult();
			$hydrated = array();
			
			//
			// Do some custom array hydration.
			//
			foreach($results as $result)
			{
				$hydrated[$result->getCategory()->getName()][$result->getId()] = $result->getName();
			}
			
	        return $hydrated;
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}*/
	
	
	
	/**
	 *
	 * @access public
	 * @param int $board_id
	 */	
	public function findOneByIdWithCategory($boardId)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT b, c FROM CCDNForumForumBundle:Board b
				LEFT JOIN b.category c
				WHERE b.id = :id')
			->setParameter('id', $boardId);

		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	
	}



	/**
	 * When a new topic is created we need to get the TopicCounts and
	 * PostCounts for board so that the TopicManager can update the
	 * board counters.
	 *
	 *
	 * @access public
	 * @param int $board_id
	 */
	public function getTopicAndPostCountsForBoard($board_id)
	{
		// get topic / post count
		$query = $this->getEntityManager()
			->createQuery('	
				SELECT COUNT(DISTINCT t.id) AS topicCount, COUNT(DISTINCT p.id) AS postCount
				FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.posts p
				WHERE t.board = :id AND t.deleted_by IS NULL
				GROUP BY t.board')
			->setParameter('id', $board_id);

		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return;
	    }
	}

	
		
	/**
	 *
	 * for adminBundle
	 *
	 * @access public
	 * @param int $category_id
	 */
	public function findBoardsOrderedByPriorityInCategory($category_id)
	{

		$boards_query = $this->getEntityManager()
			->createQuery('
				SELECT b
				FROM CCDNForumForumBundle:Board b
				WHERE b.category = :id
				ORDER BY b.list_order_priority ASC
				')
			->setParameter('id', $category_id);

		try {
			return $boards_query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {		
			return null;
		}
	}
	

	
	/**
	 *
	 * @access public
	 * @param int $board_id
	 */
	public function findLastTopicForBoard($board_id)
	{
		$lastPost_query = $this->getEntityManager()
			->createQuery('
				SELECT t, lp 
				FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				WHERE t.board = :id AND t.deleted_by IS NULL
				GROUP BY t.id
				ORDER BY lp.created_date DESC
				')
			->setParameter('id', $board_id)
			->setMaxResults(1);

		try {
			return $lastPost_query->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {		
			return null;
		}
	}
	
}