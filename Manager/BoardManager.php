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

use CCDNForum\ForumBundle\Entity\Board;

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
	 * @return \CCDNForum\ForumBundle\Entity\Board
	 */	
	public function findOneById($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$qb = $this->createSelectQuery(array('b'));
				
		$qb->where('b.id = :boardId');
		
		$board = $this->gateway->findBoard($qb, array(':boardId' => $boardId));
		
		$boards = $this->filterViewableBoards($board);
		
		if (count($boards)) {
			return $boards[0];
		} else {
			return null;
		}
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
		
		$board = $this->gateway->findBoard($qb, array(':boardId' => $boardId));
		
		$boards = $this->filterViewableBoards($board);
		
		if (count($boards)) {
			return $boards[0];
		} else {
			return null;
		}
	}
		
	/**
	 *
	 * @access public
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function findAllForFormDropDown()
	{
		$qb = $this->createSelectQuery(array('b'));
				
		$boards = $this->gateway->findBoards($qb);
		
		return $this->filterViewableBoards($boards);
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
	 * @param Array $boards
	 * @return Array
	 */
    public function filterViewableBoards($boards)
    {
		if (! is_array($boards)) {
			if (! is_object($boards) || ! $boards instanceof Board) {
				throw new \Exception('$boards must be type of Array containing instances of \CCDNForum\ForumBundle\Entity\Board');
			}
			
			$boards = array($boards);
		}
		
        foreach ($boards as $boardKey => $board) {
            if (! $board->isAuthorisedToRead($this->securityContext)) {
                unset($boards[$boardKey]);
            }
        }

        return $boards;
    }
	
	/**
	 *
	 * @access public
	 * @return Array
	 */
	public function getBoardCount()
	{	
		$qb = $this->createCountQuery();
			
		$qb
			->select('COUNT(DISTINCT b.id) AS boardCount')
		;
		
		try {
			return $qb->getQuery()->getSingleResult();			
		} catch (\Doctrine\ORM\NoResultException $e) {
			return array('boardCount' => null);
		} catch (\Exception $e) {
			return array('boardCount' => null);			
		}
	}
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function saveNewBoard(Board $board)
    {
		$boardCount = $this->getBoardCount();

        $board->setListOrderPriority(++$boardCount['boardCount']);

        // insert a new row
        $this->persist($board);

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateBoard(Board $board)
    {
        // update a record
        $this->persist($board);

        return $this;
    }
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateStats(Board $board)
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
     * @param array $boards
	 * @param int $boardId
	 * @param string $direction
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reorder($boards, $boardId, $direction)
    {
        $boardCount = count($boards);
        for ($index = 0, $priority = 1, $align = false; $index < $boardCount; $index++, $priority++) {
            if ($boards[$index]->getId() == $boardId) {
                if ($align == false) { // if aligning then other indices priorities are being corrected
                    // **************
                    // **** DOWN ****
                    // **************
                    if ($direction == 'down') {
                        if ($index < ($boardCount - 1)) { // <-- must be lower because we need to alter an offset of the next index.
                            $boards[$index]->setListOrderPriority($priority+1); // move this down the page
                            $boards[$index+1]->setListOrderPriority($priority); // move this up the page
                            $index+=1; $priority++; // the next index has already been changed
                        } else {
                            $boards[$index]->setListOrderPriority(1); // move to the top of the page
                            $index = -1; $priority = 1; // alter offsets for alignment of all other priorities
                        }
                    // **************
                    // ***** UP *****
                    // **************
                    } else {
                        if ($index > 0) {
                            $boards[$index]->setListOrderPriority($priority-1); // move this up the page
                            $boards[$index-1]->setListOrderPriority($priority); // move this down the page
                            $index+=1; $priority++;
                        } else {
                            $boards[$index]->setListOrderPriority($boardCount); // move to the bottom of the page
                            $index = -1; $priority = -1; // alter offsets for alignment of all other priorities
                        }
                    } // end down / up direction
                    $align = true; continue;
                }// end align
            } else {
                $boards[$index]->setListOrderPriority($priority);
            } // end board id match
        } // end loop

        foreach ($boards as $board) { $this->persist($board); }

		$this->flush();
		
        return $this;
    }
}