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
class BoardManager extends BaseManager implements ManagerInterface
{


	/**
	 *
	 * @access public
	 * @param $board
	 * @return $this
	 */	
	public function updateStats($board)
	{
		$counters = $this->container->get('ccdn_forum_forum.board.repository')->getTopicAndPostCountsForBoard($board->getId());

		// set the board topic / post count
		$board->setCachedTopicCount($counters['topicCount']);
		$board->setCachedPostCount($counters['postCount']);

		$last_topic = $this->container->get('ccdn_forum_forum.board.repository')->findLastTopicForBoard($board->getId());
	
		// set last_post for board
		if ($last_topic)
		{
			$board->setLastPost( (($last_topic->getLastPost()) ? $last_topic->getLastPost() : null) );
		} else {
			$board->setLastPost(null);
		}
		
		$this->persist($board);
		
		return $this;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param $boards
	 * @return $this
	 */
	public function bulkUpdateStats($boards)
	{
		foreach ($boards as $board)
		{
			$this->updateStats($board);
		}
		
		$this->flushNow();
		
		return $this;
	}
	
}