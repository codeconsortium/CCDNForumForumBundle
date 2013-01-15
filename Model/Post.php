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

use CCDNForum\ForumBundle\Entity\Topic;

abstract class Post
{
    /** @var Topic $topic */
    protected $topic = null;

    /** @var User $createdBy */
    protected $createdBy = null;

    /** @var User $editedBy */
    protected $editedBy = null;

    /** @var User $deletedBy */
    protected $deletedBy = null;

    /** @var User $lockedBy */
    protected $lockedBy = null;

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
     * Get edited_by
     *
     * @return User
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * Set edited_by
     *
     * @param User $editedBy
     */
    public function setEditedBy(\CCDNUser\UserBundle\Entity\User $editedBy = null)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * Get deleted_by
     *
     * @return User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set deleted_by
     *
     * @param User $deletedBy
     */
    public function setDeletedBy(\CCDNUser\UserBundle\Entity\User $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * Get locked_by
     *
     * @return User
     */
    public function getLockedBy()
    {
        return $this->lockedBy;
    }

    /**
     * Set locked_by
     *
     * @param User $lockedBy
     */
    public function setLockedBy(\CCDNUser\UserBundle\Entity\User $lockedBy = null)
    {
        $this->lockedBy = $lockedBy;
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
     * @param  Attachment $attachment
     * @return Post
     */
    public function setAttachment(\CCDNComponent\AttachmentBundle\Entity\Attachment $attachment = null)
    {
        $this->attachment = $attachment;

        return $this;
    }
}
