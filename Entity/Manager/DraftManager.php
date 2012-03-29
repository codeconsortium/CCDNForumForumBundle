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

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Draft;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class DraftManager extends BaseManager implements EntityManagerInterface
{

	
	
	public function flushNow()
	{
		parent::flushNow();
		
//		$user = $this->container->get('security.context')->getToken()->getUser();

//		$this->container->get('ccdn_forum_forum.registry.manager')->updateCachePostCountForUser($user);
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */
	public function insert($post)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$draft = new Draft();
		
		//
		// is this a post?
		//
		if (is_object($post) && $post instanceof Post)
		{		
			//
			// is this a topic?
			//
			if (is_object($post->getTopic()) && $post->getTopic() instanceof Topic)
			{
				if ($post->getTopic()->getId())
				{
					$draft->setTopic($post->getTopic());
					$draft->setBoard($post->getTopic()->getBoard());
				} else {
					$draft->setTitle($post->getTopic()->getTitle());
				}
			}
			
			$draft->setBody($post->getBody());		
			$draft->setCreatedBy($user);
			$draft->setCreatedDate(new \DateTime());
			
			if ($post->getAttachment())
			{
				$draft->setAttachment($post->getAttachment());			
			}
			
			// insert a new row
			$this->persist($draft)->flushNow();
		}
		
		return $this;
	}	
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */
	public function update($draft)
	{
		// update a record
		$this->persist($draft);
		
		return $this;
	}
	
	
	/**
	 *
	 * @access public
	 * @param $post
	 * @return $this
	 */
	public function delete($post)
	{
/*		$topic = $post->getTopic();
		
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

//			$this->container->get('ccdn_forum_forum.board.manager')->updateBoardStats($topic->getBoard())->flushNow();			
		}
		
		// update the record
		$this->persist($post);
	*/
		return $this;
	}
	
}