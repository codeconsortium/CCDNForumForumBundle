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

namespace CCDNForum\ForumBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BoardManager extends BaseManager implements BaseManagerInterface
{
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function findOneByIdWithCategory($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$qb = $this->createSelectQuery(array('b', 'c'));
		
		$qb
			->innerJoin('b.category', 'c')
			->where('b.id = :boardId');
		
		return $this->gateway->findBoard($qb, array(':boardId' => $boardId));
	}
	
    /**
     *
     * @access public
     * @param Board $board
     * @return self
     */
    public function updateStats($board)
    {
        $counters = $this->repository->getTopicAndPostCountsForBoard($board->getId());

        // set the board topic / post count
        $board->setCachedTopicCount($counters['topicCount']);
        $board->setCachedPostCount($counters['postCount']);

        $last_topic = $this->repository->findLastTopicForBoard($board->getId());

        // set last_post for board
        if ($last_topic) {
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
     * @return self
     */
    public function bulkUpdateStats($boards)
    {
        foreach ($boards as $board) {
            $this->updateStats($board);
        }

        $this->flush();

        return $this;
    }
	
	/**
	 *
	 * @access public
	 * @param $boards
	 * @return Array
	 */
    public function filterViewableBoards($boards)
    {
        foreach ($boards as $boardKey => $board) {
            if (! $board->isAuthorisedToRead($this->securityContext)) {
                unset($boards[$boardKey]);
            }
        }

        return $boards;
    }
}
