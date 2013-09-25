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

namespace CCDNForum\ForumBundle\Model\Model;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\BaseModelInterface;

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
class BoardModel extends BaseModel implements BaseModelInterface
{
    /**
     *
     * @access public
     * @return \Doctrine\Common\Collection\ArrayCollection
     */
    public function findAllBoards()
    {
        return $this->getRepository()->findAllBoards();
    }

    /**
     *
     * @access public
     * @param  int                                          $categoryId
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllBoardsForCategoryById($categoryId)
    {
        return $this->getRepository()->findAllBoardsForCategoryById($categoryId);
    }

    /**
     *
     * @access public
     * @param  int                                          $forumId
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllBoardsForForumById($forumId)
    {
        return $this->getRepository()->findAllBoardsForForumById($forumId);
    }

    /**
     *
     * @access public
     * @param  int                                 $boardId
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function findOneBoardById($boardId)
    {
        return $this->getRepository()->findOneBoardById($boardId);
    }

    /**
     *
     * @access public
     * @param  int                                 $boardId
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function findOneBoardByIdWithCategory($boardId)
    {
        return $this->getRepository()->findOneBoardByIdWithCategory($boardId);
    }

    /**
     *
     * @access public
     * @param  int   $boardId
     * @return Array
     */
    public function getTopicAndPostCountForBoardById($boardId)
    {
        return $this->getRepository()->getTopicAndPostCountForBoardById($boardId);
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function getBoardCount()
    {
        return $this->getRepository()->getBoardCount();
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function saveNewBoard(Board $board)
    {
        return $this->getManager()->saveNewBoard($board);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateBoard(Board $board)
    {
       return $this->getManager()->updateBoard($board);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function deleteBoard(Board $board)
    {
        return $this->getManager()->deleteBoard($board);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\Common\Collections\ArrayCollection        $topics
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reassignTopicsToBoard(ArrayCollection $topics, Board $board = null)
    {
        return $this->getManager()->reassignTopicsToBoard($topics, $board);
    }

    /**
     *
     * @access public
     * @param  Array                                               $boards
     * @param  \CCDNForum\ForumBundle\Entity\Board                 $boardShift
     * @param  int                                                 $direction
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reorderBoards($boards, Board $boardShift, $direction)
    {
        return $this->getManager()->reorderBoards($boards, $boardShift, $direction);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board               $board
     * @return \CCDNForum\ForumBundle\Model\Manager\BoardManager
     */
    public function updateStats(Board $board)
    {
        return $this->getManager()->updateStats($board);
    }

//    /**
//     *
//     * @access public
//     * @param  Array                                               $boards
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkUpdateStats($boards)
//    {
//        return $this->getManager()->bulkUpdateStats($boards);
//    }
}
