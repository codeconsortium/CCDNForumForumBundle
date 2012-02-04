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

use CCDNCommon\CommonBundle\Entity\Manager\EntityManagerInterface;
use CCDNCommon\CommonBundle\Entity\Manager\BaseManager;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class BoardManager extends BaseManager implements EntityManagerInterface
{


	/**
	 *
	 * @access public
	 * @param $board
	 * @return $this
	 */	
	public function updateBoardStats($board)
	{
		$counters = $this->container->get('board.repository')->getTopicAndPostCountsForBoard($board->getId());

		// set the board topic / post count
		$board->setTopicCount($counters['topicCount']);
		$board->setPostCount($counters['postCount']);

		$lastPost = $this->container->get('board.repository')->findLastPostForBoard($board->getId())->getLastPost();
	
		// set last_post for board
		$board->setLastPost($lastPost);
	
		$this->persist($board);
		
		return $this;
	}
	
}