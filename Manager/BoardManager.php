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
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
	 * @return bool
	 */
	public function isAuthorisedToCreateTopic($board)
	{
        if (! $board->isAuthorisedToCreateTopic($this->securityContext)) {
        	return false;
		}
        
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @return \CCDNForum\ForumBundle\Entity\Board
	 */	
	public function findOneByIdWithCategory($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$qb = $this->createSelectQuery(array('b', 'c'));
		
		$qb
			->leftJoin('b.category', 'c')
			->where('b.id = :boardId');
		
		return $this->gateway->findBoard($qb, array(':boardId' => $boardId));
	}
	
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @return Array
	 */	
	public function getTopicAndPostCountForBoardById($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$qb = $this->getQueryBuilder();

		$topicEntityClass = $this->managerBag->getTopicManager()->getGateway()->getEntityClass();
			
		$qb
			->select('COUNT(DISTINCT t.id) AS topicCount, COUNT(DISTINCT p.id) AS postCount')
			->from($topicEntityClass, 't')
			->leftJoin('t.posts', 'p')
			->where('t.board = :boardId')
			->andWhere('t.isDeleted = FALSE')
			->andWhere('p.isDeleted = FALSE')
			->setParameter(':boardId', $boardId)
			->groupBy('t.board');
		
		try {
			return $qb->getQuery()->getSingleResult();			
		} catch (\Doctrine\ORM\NoResultException $e) {
			return array('topicCount' => null, 'postCount' => null);
		} catch (\Exception $e) {
			return array('topicCount' => null, 'postCount' => null);			
		}
	}
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateStats($board)
    {
		$stats = $this->getTopicAndPostCountForBoardById($board->getId());
		
        // set the board topic / post count
        $board->setCachedTopicCount($stats['topicCount']);
        $board->setCachedPostCount($stats['postCount']);
        
        $lastTopic = $this->managerBag->getTopicManager()->findLastTopicForBoardByIdWithLastPost($board->getId());
        
        // set last_post for board
        if ($lastTopic) {
            $board->setLastPost($lastTopic->getLastPost() ?: null);
        } else {
            $board->setLastPost(null);
        }
        
        $this->persist($board)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param Array $boards
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
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
	 * @param Array $boards
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
