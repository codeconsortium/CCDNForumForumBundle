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
 * TopicRepository
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class TopicRepository extends EntityRepository
{

	
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 */
	public function findTopicsForBoardById($boardId)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN lp.created_by lpu
				LEFT JOIN t.first_post fp
				LEFT JOIN fp.created_by fpu
				WHERE t.board = :id AND t.deleted_by IS NULL
				GROUP BY t.id
				ORDER BY lp.created_date DESC')
			->setParameter('id', $boardId);

		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 */
	public function findTopicsForBoardByIdForModerators($boardId)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN lp.created_by lpu
				LEFT JOIN t.first_post fp
				LEFT JOIN fp.created_by fpu
				WHERE t.board = :id
				GROUP BY t.id
				ORDER BY lp.created_date DESC')
			->setParameter('id', $boardId);

		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 */
	public function findStickyTopicsForBoardById($boardId)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN lp.created_by lpu
				LEFT JOIN t.first_post fp
				LEFT JOIN fp.created_by fpu
				WHERE t.board = :id AND t.deleted_by IS NULL AND t.is_sticky = true
				GROUP BY t.id
				ORDER BY lp.created_date DESC')
			->setParameter('id', $boardId);

		try {
			//return new Pagerfanta(new DoctrineORMAdapter($query));
			return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 */
	public function findByIdWithBoardAndCategory($topicId)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, b, c FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.board b
				LEFT JOIN b.category c
				WHERE t.id = :id')
			->setParameter('id', $topicId);
					
		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param int $topic_id
	 * CURRENT ISSUE with Pagerfanta: https://github.com/whiteoctober/Pagerfanta/pull/51
	 */
/*	public function findOneByIdJoinedToPostsPaginated($topic_id)
	{
		
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, p	FROM CCDNForumForumBundle:Topic t
				INNER JOIN t.posts p
				LEFT JOIN p.created_by u
				LEFT JOIN p.registry r
				WHERE t.id = :id
				GROUP BY p.id
				ORDER BY p.created_date ASC')
			->setParameter('id', $topic_id);
					
		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
	        //return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}*/



	/**
	 *
	 * @access public
	 * @param int $topic_id
	 */
	public function findOneByIdJoinedToPosts($topic_id)
	{
		
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, p	FROM CCDNForumForumBundle:Topic t
				INNER JOIN t.posts p
				LEFT JOIN p.created_by u
				WHERE t.id = :id
				GROUP BY p.id
				ORDER BY p.created_date ASC')
			->setParameter('id', $topic_id);
					
		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $topic
	 */
	public function incrementViewCounter($topic)
	{
		// set the new counters
		$topic->setViewCount($topic->getViewCount() + 1);

		// inject both back into the db		
		$this->_em->flush($topic);
	}
	
	
	
	/**
	 *
	 * for moderator
	 *
	 *
	 * @access public
	 */
	public function findClosedTopicsForModeratorsPaginated()
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp, b FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN t.first_post fp
				LEFT JOIN t.board b
				WHERE t.closed_by IS NOT NULL OR t.deleted_by IS NOT NULL
				GROUP BY t.id
				ORDER BY lp.created_date DESC');
	
		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * for moderator
	 *
	 *
	 * @access public
	 */
	public function findTheseTopicsByIdForModeration($topicIds)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$query = $qb->add('select', 't')
			->from('CCDNForumForumBundle:Topic', 't')
			->where($qb->expr()->in('t.id', '?1'))
			->setParameters(array('1' => array_values($topicIds)))
			->getQuery();

		try {
			return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	
	}


/*	
	public function getTopicForeignCounters($topic_id)
	{
		// get topic / post count
		$counter_query = $this->getEntityManager()
			->createQuery('	
				SELECT COUNT(p.id) AS postCount
				FROM CCDNForumForumBundle:Post p
				WHERE p.topic = :id')
			->setParameter('id', $topic_id);
			
		try {
	        return $counter_query->getsingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return;
	    }
	}
	*/
}