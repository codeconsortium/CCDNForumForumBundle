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

namespace CCDNForum\ForumBundle\Model\Component\Manager;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Model\Component\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\BaseManager;

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
class BoardManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function createBoard()
    {
        return $this->gateway->createBoard();
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board             $board
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function saveBoard(Board $board)
    {
        $boardCount = $this->model->getBoardCount();
        $board->setListOrderPriority(++$boardCount);

        $this->gateway->saveBoard($board);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board             $board
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function updateBoard(Board $board)
    {
        $this->gateway->updateBoard($board);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board             $board
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
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

    /**
     *
     * @access public
     * @param  \Doctrine\Common\Collections\ArrayCollection    $topics
     * @param  \CCDNForum\ForumBundle\Entity\Board             $board
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function reassignTopicsToBoard(ArrayCollection $topics, Board $board = null)
    {
        foreach ($topics as $topic) {
            $topic->setBoard($board);
            $this->persist($topic);
        }

        $this->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                           $boards
     * @param  \CCDNForum\ForumBundle\Entity\Board             $boardShift
     * @param  int                                             $direction
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function reorderBoards($boards, Board $boardShift, $direction)
    {
        $boardCount = (count($boards) - 1);

        // Find board in collection to shift and use list order as array key for easier editing.
        $sorted = array();
        $shiftIndex = null;
        foreach ($boards as $boardIndex => $board) {
            if ($boards[$boardIndex]->getId() == $boardShift->getId()) {
                $shiftIndex = $boardIndex;
            }

            $sorted[$boardIndex] = $board;
        }

        $incrementKeysAfterIndex = function ($index, $arr) {
            $hydrated = array();

            foreach ($arr as $key => $el) {
                if ($key > $index) {
                    $hydrated[$key + 1] = $el;
                } else {
                    $hydrated[$key] = $el;
                }
            }

            return $hydrated;
        };

        $decrementKeysBeforeIndex = function ($index, $arr) {
            $hydrated = array();

            foreach ($arr as $key => $el) {
                if ($key < $index) {
                    $hydrated[$key - 1] = $el;
                } else {
                    $hydrated[$key] = $el;
                }
            }

            return $hydrated;
        };

        $shifted = array();

        // First Board needs reordering??
        if ($shiftIndex == 0) {
            if ($direction) { // Down (move down 1)
                $shifted = $sorted;
                $shifted[$shiftIndex] = $sorted[$shiftIndex + 1];
                $shifted[$shiftIndex + 1] = $sorted[$shiftIndex];
            } else { // Up (send to bottom)
                $shifted[$boardCount] = $sorted[0];
                unset($sorted[0]);
                $shifted = array_merge($decrementKeysBeforeIndex($boardCount + 1, $sorted), $shifted);
            }
        } else {
            // Last board needs reordering??
            if ($shiftIndex == $boardCount) {
                if ($direction) { // Down (send to top)
                    $shifted[0] = $sorted[$boardCount];
                    unset($sorted[$boardCount]);
                    $shifted = array_merge($shifted, $incrementKeysAfterIndex(-1, $sorted));
                } else { // Up (move up 1)
                    $shifted = $sorted;
                    $shifted[$shiftIndex] = $sorted[$shiftIndex - 1];
                    $shifted[$shiftIndex - 1] = $sorted[$shiftIndex];
                }
            } else {
                // Swap 2 boards not at beginning or end.
                $shifted = $sorted;
                if ($direction) { // Down (move down 1)
                    $shifted[$shiftIndex] = $sorted[$shiftIndex + 1];
                    $shifted[$shiftIndex + 1] = $sorted[$shiftIndex];
                } else { // Up (move up 1)
                    $shifted[$shiftIndex] = $sorted[$shiftIndex - 1];
                    $shifted[$shiftIndex - 1] = $sorted[$shiftIndex];
                }
            }
        }

        // Set the order from the $index arranged prior and persist.
        foreach ($shifted as $index => $board) {
            $board->setListOrderPriority($index);
            $this->persist($board);
        }

        $this->flush();

        return $this;
    }
}
