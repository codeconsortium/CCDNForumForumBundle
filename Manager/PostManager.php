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
	public function reply($post)
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
			$post->setIsDeleted(true);
			$post->setDeletedBy($user);
			$post->setDeletedDate(new \DateTime());

			// Lock the post as a precaution.
			$post->setIsLocked(true);
			$post->setLockedBy($user);
			$post->setLockedDate(new \DateTime());
			
			// update the record
			$this->persist($post)->flushNow();
					
			if ($post->getTopic())
			{
				$topic = $post->getTopic();
		
				// if this is the first post and only post, then soft delete the topic aswell.
				if ($topic->getReplyCount() < 1)
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
			
						$this->persist($topic)->flushNow();
						
						// Update affected Topic stats.
						$this->container->get('ccdn_forum_forum.topic.manager')->updateStats($post->getTopic())->flushNow();
					}
				}
			}
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
		$post->setIsDeleted(false);
		$post->setDeletedBy(null);
		$post->setDeletedDate(null);

		// update the record
		$this->persist($post)->flushNow();
				
		if ($post->getTopic())
		{
			$topic = $post->getTopic();
		
			// if this is the first post and only post,
			// then restore the topic aswell.
			if ($topic->getReplyCount() < 1)
			{
				$topic->setIsDeleted(false);
				$topic->setDeletedBy(null);
				$topic->setDeletedDate(null);
			
				$this->persist($topic)->flushNow();
				
				// Update affected Topic stats.
				$this->container->get('ccdn_forum_forum.topic.manager')->updateStats($post->getTopic())->flushNow();
			}
		}
				
		return $this;
	}
	
}