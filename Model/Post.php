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

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Topic;

abstract class Post
{
    /** @var Topic $topic */
    protected $topic = null;

    /** @var UserInterface $createdBy */
    protected $createdBy = null;

    /** @var UserInterface $editedBy */
    protected $editedBy = null;

    /** @var UserInterface $deletedBy */
    protected $deletedBy = null;

    /** @var UserInterface $lockedBy */
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
    public function setTopic(Topic $topic = null)
    {
        $this->topic = $topic;
    }

    /**
     * Get created_by
     *
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set created_by
     *
     * @param UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy = null)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get edited_by
     *
     * @return UserInterface
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * Set edited_by
     *
     * @param UserInterface $editedBy
     */
    public function setEditedBy(UserInterface $editedBy = null)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * Get deleted_by
     *
     * @return UserInterface
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set deleted_by
     *
     * @param UserInterface $deletedBy
     */
    public function setDeletedBy(UserInterface $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * Get locked_by
     *
     * @return UserInterface
     */
    public function getLockedBy()
    {
        return $this->lockedBy;
    }

    /**
     * Set locked_by
     *
     * @param UserInterface $lockedBy
     */
    public function setLockedBy(UserInterface $lockedBy = null)
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
    public function setAttachment(Attachment $attachment = null)
    {
        $this->attachment = $attachment;

        return $this;
    }
}
