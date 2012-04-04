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

namespace CCDNForum\ForumBundle\Entity\Manager;

use CCDNComponent\CommonBundle\Entity\Manager\EntityManagerInterface;
use CCDNComponent\CommonBundle\Entity\Manager\BaseManager;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class TopicManager extends BaseManager implements EntityManagerInterface
{
	
	
	/**
	 *
	 * @access protected
	 */
	protected $counters;

	
	
	/**
	 *
	 * @access public
	 */
	public function flushNow()
	{
		parent::flushNow();
		
		$user = $this->container->get('security.context')->getToken()->getUser();

		$this->container->get('ccdn_forum_forum.registry.manager')->updateCachePostCountForUser($user);
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */	
	public function insert($post)
	{
		// insert a new row
		$this->persist($post)->flushNow();
		
		// refresh the user so that we have an PostId to work with.
		$this->refresh($post);
		
		// get the topic
		$topic = $post->getTopic();	

		// set topic last_post and first_post, last_post for board
		$topic->setFirstPost($post);
		$topic->setLastPost($post);

		$this->persist($topic)->flushNow();
		
		$this->container->get('ccdn_forum_forum.board.manager')->updateBoardStats($topic->getBoard())->flushNow();			
		
		return $this;
	}

	
	
	/**
	 *
	 * @access public
	 * @param $topic
	 * @return $this
	 */
	public function update($topic)
	{
		// update the record
		$this->persist($topic);
	
		return $this;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $topic, $user
	 * @return $this
	 */
	public function softDelete($topic, $user)
	{
		$topic->setDeletedBy($user);
		$topic->setDeletedDate(new \DateTime());
		$topic->setClosedBy($user);
		$topic->setClosedDate(new \DateTime());
		
		// update the record before doing record counts
		$this->persist($topic)->flushNow();
		
		$this->container->get('ccdn_forum_forum.board.manager')->updateBoardStats($topic->getBoard())->flushNow();
		
		return $this;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $topic
	 * @return $this
	 */
	public function restore($topic)
	{
		$topic->setDeletedBy(null);
		$topic->setDeletedDate(null);
		
		$this->persist($topic);
		
		return $this;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $topic, $user
	 * @return $this
	 */
	public function close($topic, $user)
	{
		$topic->setClosedBy($user);
		$topic->setClosedDate(new \DateTime());
		
		$this->persist($topic);
		
		return $this;
	}
	

	
	/**
	 *
	 * @access public
	 * @param $topic
	 * @return $this
	 */
	public function reopen($topic)
	{
		$topic->setClosedBy(null);
		$topic->setClosedDate(null);
		
		$this->persist($topic);
		
		return $this;
	}
	
	
	public function bulkClose($topics)
	{
		foreach($topics as $topic)
		{
			$topic->setClosedBy($this->container->get('security.context')->getToken()->getUser());
			$topic->setClosedDate(new \DateTime());
			
			$this->persist($topic);
		}
		
		return $this;
	}
	
	public function bulkReopen($topics)
	{
		foreach($topics as $topic)
		{
			$topic->setClosedBy(null);
			$topic->setClosedDate(null);
			
			$this->persist($topic);
		}
		
		return $this;
	}
	
	public function bulkRestore($topics)
	{
		foreach($topics as $topic)
		{
			$topic->setDeletedBy(null);
			$topic->setDeletedDate(null);
			
			$this->persist($topic);
		}
		
		return $this;
	}
	
	public function bulkSoftDelete($topics)
	{
		foreach($topics as $topic)
		{
			$topic->setDeletedBy($this->container->get('security.context')->getToken()->getUser());
			$topic->setDeletedDate(new \DateTime());
			
			$this->persist($topic);
		}
		
		return $this;
	}
	
	/**
	 *
	 * @access public
	 * @return Array()
	 */
	public function getCounters()
	{
		return array(
			'topicCount' => $this->counters['topicCount'],
			'postCount' => $this->counters['postCount']
		);
	}
	
}