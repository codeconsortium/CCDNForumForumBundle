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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNUser\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\PostRepository")
 */
class Post
{
	
    /** @var integer $id */
    protected $id;

    /** @var CCDNForum\ForumBundle\Entity\Topic $topic */
    protected $topic = null;

    /** @var string $body */
    protected $body;

    /** @var \DateTime $createdDate */
    protected $createdDate;

    /** @var CCDNUser\UserBundle\Entity\User $createdBy */
    protected $createdBy = null;

    /** @var \DateTime $editedDate */
    protected $editedDate;

    /** @var CCDNUser\UserBundle\Entity\User $editedBy */
    protected $editedBy = null;

    /** @var Boolean $isDeleted */
    protected $isDeleted = false;

    /** @var \DateTime $deletedDate */
    protected $deletedDate;

    /** @var CCDNUser\UserBundle\Entity\User $deletedBy */
    protected $deletedBy = null;

    /** @var Boolean $isLocked */
    protected $isLocked = false;

    /** @var \DateTime $lockedDate */
    protected $lockedDate;

    /** @var CCDNUser\UserBundle\Entity\User $lockedBy */
    protected $lockedBy = null;

    /** @var CCDNComponent\AttachmentBundle\Entity\Attachment $attachment */
    protected $attachment = null;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set created_date
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get created_date
     *
     * @return datetime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set edited_date
     *
     * @param datetime $editedDate
     */
    public function setEditedDate($editedDate)
    {
        $this->editedDate = $editedDate;
    }

    /**
     * Get edited_date
     *
     * @return datetime
     */
    public function getEditedDate()
    {
        return $this->editedDate;
    }

    /**
     * Set deleted_date
     *
     * @param datetime $deletedDate
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deletedDate = $deletedDate;
    }

    /**
     * Get deleted_date
     *
     * @return datetime
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
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
     * Set locked_date
     *
     * @param datetime $lockedDate
     */
    public function setLockedDate($lockedDate)
    {
        $this->lockedDate = $lockedDate;
    }

    /**
     * Get locked_date
     *
     * @return datetime
     */
    public function getLockedDate()
    {
        return $this->lockedDate;
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

    public function __construct()
    {
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

    /**
     * Set is_deleted
     *
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * Get is_deleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set is_locked
     *
     * @param boolean $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;
    }

    /**
     * Get is_locked
     *
     * @return boolean
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

}