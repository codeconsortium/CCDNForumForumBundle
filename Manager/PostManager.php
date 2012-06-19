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
class PostManager extends BaseManager implements ManagerInterface
{

		
	
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
		
		// Update affected Topic stats.
		$this->container->get('ccdn_forum_forum.topic.manager')->updateStats($post->getTopic());

		// Update the cached post count of the post author.
		$this->container->get('ccdn_forum_forum.registry.manager')->updateCachePostCountForUser($post->getCreatedBy());
		
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
		// Don't overwite previous users accountability.
		if ( ! $post->getDeletedBy() && ! $post->getDeletedDate())
		{
			$post->setDeletedBy($user);
			$post->setDeletedDate(new \DateTime());
		
			$topic = $post->getTopic();
		
			// if this is the first post and only post, then soft delete the topic aswell.
			if ($topic->getReplyCount() < 1)
			{
				// Don't overwite previous users accountability.
				if ( ! $topic->getDeletedBy() && ! $topic->getDeletedDate())
				{
					$topic->setDeletedBy($user);
					$topic->setDeletedDate(new \DateTime());
			
					$this->persist($topic);
				}
			}
			
			// update the record
			$this->persist($post)->flushNow();
	
			// Update affected Topic stats.
			$this->container->get('ccdn_forum_forum.topic.manager')->updateStats($post->getTopic())->flushNow();
		}
		
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
		
		$topic = $post->getTopic();
		
		// if this is the first post and only post,
		// then restore the topic aswell.
		if ($topic->getReplyCount() == 0)
		{
			$topic->setDeletedBy(null);
			$topic->setDeletedDate(null);
			$topic->setClosedBy(null);
			$topic->setClosedDate(null);
			
			$this->persist($topic);
		}
		
		// update the record
		$this->persist($post)->flushNow();
		
		// Update affected Topic stats.
		$this->container->get('ccdn_forum_forum.topic.manager')->updateStats($post->getTopic())->flushNow();
		
		return $this;
	}
	
}