<?php

/*
 * This file is part of the CCDNForum ForumBundle
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

use CCDNForum\ForumBundle\Entity\Registry;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class RegistryManager extends BaseManager implements EntityManagerInterface
{



	/**
	 *
	 * @access public
	 * @param $user
	 */
	public function updateCachePostCountForUser($user)
	{

		$record = $this->container->get('ccdn_forum_forum.registry.repository')->findRegistryRecordForUser($user->getId());
		
		if ( ! $record)
		{
			$record = new Registry();
			$record->setOwnedBy($user);
		}
		
		$postCount = $this->container->get('ccdn_forum_forum.post.repository')->getPostCountForUserById($user->getId());
		
		$record->setCachePostCount($postCount['postCount']);
		
		$this->persist($record)->flushNow();
	}
	
}