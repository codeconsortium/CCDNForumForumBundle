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

namespace CCDNForum\ForumBundle\Component\Dispatcher\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;

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
class ModeratorTopicMoveEvent extends Event
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected $topic;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $oldBoard
     */
    protected $oldBoard;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $newBoard
     */
    protected $newBoard;

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \CCDNForum\ForumBundle\Entity\Topic       $topic
     */
    public function __construct(Request $request, Board $oldBoard, Board $newBoard, Topic $topic = null)
    {
        $this->request = $request;
        $this->topic = $topic;
        $this->oldBoard = $oldBoard;
        $this->newBoard = $newBoard;
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function getOldBoard()
    {
        return $this->oldBoard;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function getNewBoard()
    {
        return $this->newBoard;
    }
}
