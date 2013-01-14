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
     * Set topic
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topic
     */
    public function setTopic(\CCDNForum\ForumBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;
    }

    /**
     * Get topic
     *
     * @return CCDNForum\ForumBundle\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set created_by
     *
     * @param CCDNUser\UserBundle\Entity\User $createdBy
     */
    public function setCreatedBy(\CCDNUser\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get created_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set attachment
     *
     * @param CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
     */
    public function setAttachment(\CCDNComponent\AttachmentBundle\Entity\Attachment $attachment = null)
    {
        $this->attachment = $attachment;
    }

    /**
     * Get attachment
     *
     * @return CCDNComponent\AttachmentBundle\Entity\Attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set board
     *
     * @param CCDNForum\ForumBundle\Entity\Board $board
     */
    public function setBoard(\CCDNForum\ForumBundle\Entity\Board $board = null)
    {
        $this->board = $board;
    }

    /**
     * Get board
     *
     * @return CCDNForum\ForumBundle\Entity\Board
     */
    public function getBoard()
    {
        return $this->board;
    }
}
