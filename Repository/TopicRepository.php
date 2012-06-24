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
	 */
	public function getTableIntegrityStatus()
	{
		$queryOrphanedTopicCount = $this->getEntityManager()
			->createQuery('
				SELECT COUNT(DISTINCT t.id) AS orphanedTopicCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.board IS NULL
			');
		$queryUnlinkedFirstPostCount = $this->getEntityManager()
			->createQuery('
				SELECT COUNT(DISTINCT t.id) AS unlinkedFirstPostCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.first_post IS NULL AND t.reply_count > 0 
			');
		$queryUnlinkedLastPostCount = $this->getEntityManager()
			->createQuery('
				SELECT COUNT(DISTINCT t.id) AS unlinkedLastPostCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.last_post IS NULL AND t.reply_count > 0 
			');
		$queryPartialClosureCount = $this->getEntityManager()
			->createQuery('
				SELECT COUNT(DISTINCT t.id) AS partialClosureCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.is_closed IS NULL OR (t.is_closed = FALSE AND t.closed_by IS NOT NULL)
			');
		$queryPartialDeletionCount = $this->getEntityManager()
			->createQuery('		
				SELECT COUNT(DISTINCT t.id) AS partialDeletionCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.is_deleted IS NULL OR (t.is_deleted = FALSE AND t.deleted_by IS NOT NULL)
			');
//		$queryPartialStickyCount = $this->getEntityManager()
//			->createQuery('		
//				SELECT COUNT(DISTINCT t.id) AS partialStickyCount
//				FROM CCDNForumForumBundle:Topic t
//				WHERE b.is_sticky IS NULL OR (t.is_sticky = FALSE AND t.stickied_by IS NOT NULL)
//			');				
		$queryUnsetReplyCount = $this->getEntityManager()
			->createQuery('		
				SELECT COUNT(DISTINCT t.id) AS unsetReplyCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.reply_count IS NULL
			');
		$queryUnsetViewCount = $this->getEntityManager()
			->createQuery('		
				SELECT COUNT(DISTINCT t.id) AS unsetViewCount
				FROM CCDNForumForumBundle:Topic t
				WHERE t.view_count IS NULL
			');

		try {
	        $result['orphanedTopicCount'] = $queryOrphanedTopicCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['orphanedTopicCount'] = '?';
	    }
	
		try {
	        $result['unlinkedFirstPostCount'] = $queryUnlinkedFirstPostCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['unlinkedFirstPostCount'] = '?';
	    }

		try {
	        $result['unlinkedLastPostCount'] = $queryUnlinkedLastPostCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['unlinkedLastPostCount'] = '?';
	    }

		try {
	        $result['partialClosureCount'] = $queryPartialClosureCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['partialClosureCount'] = '?';
	    }

		try {
	        $result['partialDeletionCount'] = $queryPartialDeletionCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['partialDeletionCount'] = '?';
	    }
	
		try {
	        $result['unsetReplyCount'] = $queryUnsetReplyCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['unsetReplyCount'] = '?';
	    }
	
		try {
	        $result['unsetViewCount'] = $queryUnsetViewCount->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        $result['unsetViewCount'] = '?';
	    }
	
		return $result;
	}
	
	
	
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