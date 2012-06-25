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
	 * @access public
	 * @param $post
	 * @return $this
	 */	
	public function create($post)
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
			$topic->setIsDeleted(true);
			$topic->setDeletedBy($user);
			$topic->setDeletedDate(new \DateTime());
		
			// Close the topic as a precaution.
			$topic->setIsClosed(true);
			$topic->setClosedBy($user);
			$topic->setClosedDate(new \DateTime());
			
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
		$topic->setIsDeleted(false);
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
		$topicRepository = $this->container->get('ccdn_forum_forum.topic.repository');
		$postRepository = $this->container->get('ccdn_forum_forum.post.repository');
		
		// Gets stats.
		$topicReplyCount = $postRepository->getPostCountForTopicById($topic->getId());	
		$topicLastPost = $topicRepository->getLastPostForTopic($topic->getId());

		// Set the board / topic last post. 
		$topic->setCachedReplyCount( (($topicReplyCount) ? --$topicReplyCount : 0) );		
		$topic->setLastPost( (($topicLastPost) ? $topicLastPost : null) );
				
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
	 * @param $topic
	 */
	public function incrementViewCounter($topic)
	{
		// set the new counters
		$topic->setCachedViewCount($topic->getCachedViewCount() + 1);

		$this->persist($topic)->flushNow();
	}
	
}