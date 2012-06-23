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
	public function findTopicsForBoardById($boardId, $includeDeleted)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN lp.created_by lpu
				LEFT JOIN t.first_post fp
				LEFT JOIN fp.created_by fpu
				WHERE t.board = :id' . 
					(($includeDeleted) ? null : ' AND t.is_deleted = FALSE') .
				' AND t.is_sticky = FALSE
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
	public function findStickyTopicsForBoardById($boardId, $includeDeleted)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN lp.created_by lpu
				LEFT JOIN t.first_post fp
				LEFT JOIN fp.created_by fpu
				WHERE t.board = :id' . 
					(($includeDeleted) ? null : ' AND t.is_deleted = FALSE') .
				' AND t.is_sticky = TRUE
				GROUP BY t.id
				ORDER BY lp.created_date DESC')
			->setParameter('id', $boardId);

		try	{
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
	 * for moderator
	 *
	 *
	 * @access public
	 */
	public function findClosedTopicsForModeratorsPaginated()
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT t, fp, lp, b 
				FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.last_post lp
				LEFT JOIN t.first_post fp
				LEFT JOIN t.board b
				WHERE t.is_closed = TRUE OR t.is_deleted = TRUE
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
	
	
	
	/**
	 *
	 * @access public
	 * @param int $topic_id
	 */
	public function getLastPostForTopic($topic_id)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();

		$query = $qb
			->add('select', 'p')
			->add('from', 'CCDNForumForumBundle:Post p')
			->add('where', 'p.topic = ?1')
			->add('orderBy', 'p.created_date DESC')
			->setMaxResults(1)
			->setParameter(1, $topic_id)
			->getQuery();

		try {
			return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    } catch (\Doctrine\ORM\NonUniqueResultException $e) {
			return null;
		}
	}

}