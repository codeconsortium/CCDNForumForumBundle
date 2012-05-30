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

namespace CCDNForum\ForumBundle\Manager;

use CCDNComponent\CommonBundle\Manager\ManagerInterface;
use CCDNComponent\CommonBundle\Manager\BaseManager;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class TopicManager extends BaseManager implements ManagerInterface
{
	

	
	/**
	 *
	 * @access protected
	 */
	protected $counters;
	
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */	
	public function insert($post)
	{
		// insert a new row.
		$this->persist($post)->flushNow();
		
		// refresh the user so that we have an PostId to work with.
		$this->refresh($post);
		
		// get the topic.
		$topic = $post->getTopic();	

		// set topic last_post and first_post, board's last_post.
		$topic->setFirstPost($post);
		$topic->setLastPost($post);

		// persist and refresh after a flush to get topic id.
		$this->persist($topic)->flushNow();
		
		$this->refresh($topic);
		
		if ($topic->getBoard())
		{
			// Update affected Board stats.
			$this->container->get('ccdn_forum_forum.board.manager')->updateStats($topic->getBoard())->flushNow();			
		}
		
		// Update the cached post count of the post author.
		$this->container->get('ccdn_forum_forum.registry.manager')->updateCachePostCountForUser($post->getCreatedBy());
		
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
		// Don't overwite previous users accountability.
		if ( ! $topic->getDeletedBy() && ! $topic->getDeletedDate())
		{
			$topic->setDeletedBy($user);
			$topic->setDeletedDate(new \DateTime());
		
			// update the record before doing record counts
			$this->persist($topic)->flushNow();
		
			// Update affected Topic stats.
			$this->updateStats($topic);		
		}
		
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
		
		$this->persist($topic)->flushNow();
		
		// Update affected Topic stats.
		$this->updateStats($topic);
		
		return $this;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $topic
	 * @return $this
	 */
	public function updateStats($topic)
	{
		$topic_repository = $this->container->get('ccdn_forum_forum.topic.repository');
		
		// Gets stats.
		$topic_reply_count = $topic_repository->getReplyCountForTopic($topic->getId());	
		$topic_last_post = $topic_repository->getLastPostForTopic($topic->getId());
			
		// Set the board / topic last post. 
		$topic->setReplyCount( (($topic_reply_count) ? --$topic_reply_count : 0) );		
		$topic->setLastPost( (($topic_last_post) ? $topic_last_post : null) );
				
		$this->persist($topic)->flushNow();

		if ($topic->getBoard())
		{
			// Update affected Board stats.
			$this->container->get('ccdn_forum_forum.board.manager')->updateStats($topic->getBoard())->flushNow();
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