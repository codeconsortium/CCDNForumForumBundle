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

namespace CCDNForum\ForumBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;

abstract class Draft
{
    /** @var Board $board */
    protected $board;

    /** @var Topic $topic */
    protected $topic = null;

    /** @var User $createdBy */
    protected $createdBy = null;

    /** @var Attachment $attachment */
    protected $attachment = null;

    public function __construct()
    {

    }

    /**
     * Get topic
     *
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set topic
     *
     * @param Topic $topic
     */
    public function setTopic(\CCDNForum\ForumBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;
    }

    /**
     * Get created_by
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set created_by
     *
     * @param User $createdBy
     */
    public function setCreatedBy(\CCDNUser\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get attachment
     *
     * @return Attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set attachment
     *
     * @param Attachment $attachment
     */
    public function setAttachment(\CCDNComponent\AttachmentBundle\Entity\Attachment $attachment = null)
    {
        $this->attachment = $attachment;
    }

    /**
     * Get board
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set board
     *
     * @param Board $board
     */
    public function setBoard(\CCDNForum\ForumBundle\Entity\Board $board = null)
    {
        $this->board = $board;
    }
}
