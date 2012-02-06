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
class PostManager extends BaseManager implements EntityManagerInterface
{
	
	
	/**
	 *
	 * @access protected
	 */
	protected $replyCount;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $postCount;
	
	
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
		
		// we need to return this to the controller so it
		//  can redirect the user to the appropriate page.
		$topic_counter = $this->container->get('board.repository')->getReplyCountsForTopic($topic->getId());
		$this->replyCount = ($topic_counter['replyCount'] - 1);
			
		// set the board / topic last post 
		$topic->setLastPost($post);
		$topic->setReplyCount($this->replyCount);
				
		$this->persist($topic)->flushNow();

		$this->container->get('board.manager')->updateBoardStats($topic->getBoard())->flushNow();			
		
		return $this;
	}	
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */
	public function update($post)
	{
		// update a record
		$this->persist($post);
		
		return $this;
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post, $user
	 * @return $this
	 */
	public function softDelete($post, $user)
	{
		$post->setDeletedBy($user);
		$post->setDeletedDate(new \DateTime());
		
		$topic = $post->getTopic();
		
		// if this is the first post and only post, then
		// soft delete the topic aswell.
		if ($topic->getReplyCount() == 0)
		{
			$topic->setDeletedBy($user);
			$topic->setDeletedDate(new \DateTime());
			$topic->setClosedBy($user);
			$topic->setClosedDate(new \DateTime());
	
			// we must persist and flush before we can get accurate counter information.
			$this->persist($topic, $post)->flushNow();

			$this->container->get('board.manager')->updateBoardStats($topic->getBoard())->flushNow();			
		}
		
		// update the record
		$this->persist($post);
	
		return $this;
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */
	public function restore($post)
	{
		$post->setDeletedBy(null);
		$post->setDeletedDate(null);
		
		$this->persist($post);
		
		return $this;
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post, $user
	 * @return $this
	 */
	public function lock($post, $user)
	{		
		$post->setLockedBy($user);
		$post->setLockedDate(new \DateTime());
		
		$this->persist($post);
		
		return $this;
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */
	public function unlock($post)
	{
		$post->setLockedBy(null);
		$post->setLockedDate(null);
				
		$this->persist($post);
		
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
			'replyCount' => $this->replyCount,
			'postCount' => $this->postCount);
	}
	
}