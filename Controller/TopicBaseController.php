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

namespace CCDNForum\ForumBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicBaseController extends BaseController
{
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToCreateTopic($board)
	{
		return $this->isAuthorised($this->getBoardManager()->isAuthorisedToCreateTopic($board));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToReplyToTopic($topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToReplyToTopic($topic));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToEditTopic($topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToEditTopic($topic));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToDeleteTopic($topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToDeleteTopic($topic));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToRestoreTopic($topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToRestoreTopic($topic));
	}
}