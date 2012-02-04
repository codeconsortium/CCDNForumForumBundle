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


	/**
	 *
	 * @access public
	 * @param int $board_id
	 */	
	public function findOneByIdJoinedWithTopics($board_id)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT b, t, fp, lp FROM CCDNForumForumBundle:Board b
				LEFT JOIN b.topics t
				LEFT JOIN t.last_post lp
				LEFT JOIN t.first_post fp
				WHERE b.id = :id AND t.deleted_by IS NULL
				GROUP BY t.id
				ORDER BY lp.created_date DESC')
			->setParameter('id', $board_id);
		
		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
//	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	
	}
	
	
	/**
	 *
	 * @access public
	 * @param int $board_id
	 */
	public function findOneByIdJoinedWithTopicsForModerators($board_id)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT b, t, fp, lp FROM CCDNForumForumBundle:Board b
				LEFT JOIN b.topics t
				LEFT JOIN t.last_post lp
				LEFT JOIN t.first_post fp
				WHERE b.id = :id
				GROUP BY t.id
				ORDER BY lp.created_date DESC')
			->setParameter('id', $board_id);

		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
//	        return $query->getSingleResult();
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
		/*
		SELECT COUNT(DISTINCT t.id) AS topicCount, COUNT(DISTINCT p.id) AS postCount
		FROM CCDNForumForumBundle:Topic t
		LEFT JOIN t.posts p
		WHERE t.board IN (:id) AND t.deleted_by IS NULL
		GROUP BY t.board
		*/
		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return;
	    }
	}
	
	
	/**
	 * When a reply to a topic is made we need to get the ReplyCounts(posts)
	 * for Topic stats and PostCounts(posts) for board stats so that the 
	 * PostManager can update the topic counters and boards post counters.
	 *
	 * THIS METHOD IS BROKEN - Bug, gives error:
	 * Unknown column 'c2_.id' in 'on clause'
	 * the query is broken into the 2 methods below this to work around.
	 * could be a Doctrine issue, cannot confirm.
	 * rc = replyCounts, bt Board Topics and pc = postCount
	 */
/*	public function getReplyCountsForTopicWithPostCountForBoard($topic_id, $board_id)
	{
		// get reply(post) count for topic / post count for board
		$query = $this->getEntityManager()
			->createQuery('	
				SELECT COUNT(DISTINCT rc.id) AS replyCount, COUNT(DISTINCT pc.id) AS postCount
				FROM CCDNForumForumBundle:Topic t, CCDNForumForumBundle:Board b
				LEFT JOIN t.posts rc
				LEFT JOIN b.topics bt
				LEFT JOIN bt.posts pc
				WHERE (t.id = :topic_id AND b.id = :board_id)
				')
			->setParameters(array('topic_id' => $topic_id, 'board_id' => $board_id));
		
		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return;
	    }	
	}*/
	
	
	
	/**
	 *
	 * @access public
	 * @param int $topic_id
	 */
	public function getReplyCountsForTopic($topic_id)
	{
		// get reply(post) count for topic / post count for board
		$query = $this->getEntityManager()
			->createQuery('	
				SELECT COUNT(DISTINCT rc.id) AS replyCount
				FROM CCDNForumForumBundle:Topic t
				LEFT JOIN t.posts rc
				WHERE (t.id = :topic_id)
				')
			->setParameters(array('topic_id' => $topic_id));
		
		try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return;
	    }
	}
	
	
	/**
	 *
	 * @access public
	 * @param int $board_id
	 */
	public function getPostCountForBoard($board_id)
	{
		// get reply(post) count for topic / post count for board
		$query = $this->getEntityManager()
			->createQuery('	
				SELECT COUNT(DISTINCT pc.id) AS postCount
				FROM CCDNForumForumBundle:Board b
				LEFT JOIN b.topics bt
				LEFT JOIN bt.posts pc
				WHERE (b.id = :board_id)
				')
			->setParameters(array('board_id' => $board_id));
		
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
	public function findLastPostForBoard($board_id)
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