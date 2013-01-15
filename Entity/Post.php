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

use CCDNForum\ForumBundle\Model\Post as AbstractPost;
use CCDNForum\ForumBundle\Entity\Topic;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\PostRepository")
 */
class Post extends AbstractPost
{
    /** @var integer $id */
    protected $id;

    /** @var string $body */
    protected $body;

    /** @var \DateTime $createdDate */
    protected $createdDate;

    /** @var \DateTime $editedDate */
    protected $editedDate;

    /** @var Boolean $isDeleted */
    protected $isDeleted = false;

    /** @var \DateTime $deletedDate */
    protected $deletedDate;

    /** @var Boolean $isLocked */
    protected $isLocked = false;

    /** @var \DateTime $lockedDate */
    protected $lockedDate;

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
