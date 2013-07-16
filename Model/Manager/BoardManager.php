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

namespace CCDNForum\ForumBundle\Model\Manager;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Model\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Board;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class BoardManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function saveNewBoard(Board $board)
    {
        $boardCount = $this->model->getBoardCount();

        $board->setListOrderPriority(++$boardCount['boardCount']);

        // insert a new row
        $this->persist($board)->flush();
		
		$this->refresh($board);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
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
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
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
     * @param  Array                                               $boards
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
     * @param  array                                               $boards
     * @param  int                                                 $boardId
     * @param  string                                              $direction
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
	
	public function reassignTopicsToBoard(ArrayCollection $topics, Board $board = null)
	{
		foreach ($topics as $topic) {
			$topic->setBoard($board);
			
			$this->persist($topic);
		}

		$this->flush();
		
		return $this;
	}
	
	public function deleteBoard(Board $board)
	{
		// If we do not refresh the board, AND we have reassigned the topics to null, 
		// then its lazy-loaded topics are dirty, as the topics in memory will still
		// have the old board id set. Removing the board will cascade into deleting
		// topics aswell, even though in the db the relation has been set to null.
		$this->refresh($board);
		
		$this->remove($board)->flush();
		
		return $this;
	}
}
