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

abstract class Post
{
    /** @var CCDNForum\ForumBundle\Entity\Topic $topic */
    protected $topic = null;

    /** @var CCDNUser\UserBundle\Entity\User $createdBy */
    protected $createdBy = null;

    /** @var CCDNUser\UserBundle\Entity\User $editedBy */
    protected $editedBy = null;

    /** @var CCDNUser\UserBundle\Entity\User $deletedBy */
    protected $deletedBy = null;
    /** @var CCDNUser\UserBundle\Entity\User $lockedBy */
    protected $lockedBy = null;

    /** @var CCDNComponent\AttachmentBundle\Entity\Attachment $attachment */
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
     * Set edited_by
     *
     * @param CCDNUser\UserBundle\Entity\User $editedBy
     */
    public function setEditedBy(\CCDNUser\UserBundle\Entity\User $editedBy = null)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * Get edited_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * Set deleted_by
     *
     * @param CCDNUser\UserBundle\Entity\User $deletedBy
     */
    public function setDeletedBy(\CCDNUser\UserBundle\Entity\User $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * Get deleted_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set locked_by
     *
     * @param CCDNUser\UserBundle\Entity\User $lockedBy
     */
    public function setLockedBy(\CCDNUser\UserBundle\Entity\User $lockedBy = null)
    {
        $this->lockedBy = $lockedBy;
    }

    /**
     * Get locked_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getLockedBy()
    {
        return $this->lockedBy;
    }

    /**
     * Set attachment
     *
     * @param  CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
     * @return Post
     */
    public function setAttachment(\CCDNComponent\AttachmentBundle\Entity\Attachment $attachment = null)
    {
        $this->attachment = $attachment;

        return $this;
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
}
