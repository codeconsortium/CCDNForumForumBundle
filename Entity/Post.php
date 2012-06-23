<?php

/*
 * This file is part of the CCDN ForumBundle
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
use Symfony\Component\Validator\Constraints as Assert;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNUser\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\PostRepository")
 * @ORM\Table(name="CC_Forum_Post")
 */
class Post
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $topic;
	
	/**
     * @ORM\Column(type="text")
	 * @Assert\NotBlank()
     */
	protected $body;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", inversedBy="forum_posts", cascade={"persist"})
     * @ORM\JoinColumn(name="created_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $created_by;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $edited_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="edited_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */	
	protected $edited_by;
	
	/**
	 *
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $is_deleted;
		
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $deleted_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="deleted_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $deleted_by;
	
	/**
	 *
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $is_locked;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $locked_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="locked_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $locked_by;
	
	/**
     * @ORM\OneToMany(targetEntity="CCDNForum\ForumBundle\Entity\Flag", mappedBy="post", cascade={"remove"})
     */
	protected $flags;

	/**
     * @ORM\ManyToOne(targetEntity="CCDNComponent\AttachmentBundle\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinColumn(name="attachment_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $attachment;
	
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
        $this->created_date = $createdDate;
    }

    /**
     * Get created_date
     *
     * @return datetime 
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set creator_user_id
     *
     * @param integer $creatorUserId
     */
    public function setCreatorUserId($creatorUserId)
    {
        $this->creator_user_id = $creatorUserId;
    }

    /**
     * Get creator_user_id
     *
     * @return integer 
     */
    public function getCreatorUserId()
    {
        return $this->creator_user_id;
    }

    /**
     * Set edited_date
     *
     * @param datetime $editedDate
     */
    public function setEditedDate($editedDate)
    {
        $this->edited_date = $editedDate;
    }

    /**
     * Get edited_date
     *
     * @return datetime 
     */
    public function getEditedDate()
    {
        return $this->edited_date;
    }

    /**
     * Set editor_user_id
     *
     * @param integer $editorUserId
     */
    public function setEditorUserId($editorUserId)
    {
        $this->editor_user_id = $editorUserId;
    }

    /**
     * Get editor_user_id
     *
     * @return integer 
     */
    public function getEditorUserId()
    {
        return $this->editor_user_id;
    }

    /**
     * Set deleted_date
     *
     * @param datetime $deletedDate
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deleted_date = $deletedDate;
    }

    /**
     * Get deleted_date
     *
     * @return datetime 
     */
    public function getDeletedDate()
    {
        return $this->deleted_date;
    }

    /**
     * Set deleter_user_id
     *
     * @param integer $deleterUserId
     */
    public function setDeleterUserId($deleterUserId)
    {
        $this->deleter_user_id = $deleterUserId;
    }

    /**
     * Get deleter_user_id
     *
     * @return integer 
     */
    public function getDeleterUserId()
    {
        return $this->deleter_user_id;
    }


    /**
     * Set topic
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topic
     */
    public function setTopic(\CCDNForum\ForumBundle\Entity\Topic $topic)
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
     * Set editor_user
     *
     * @param integer $editorUser
     */
    public function setEditorUser($editorUser)
    {
        $this->editor_user = $editorUser;
    }

    /**
     * Get editor_user
     *
     * @return integer 
     */
    public function getEditorUser()
    {
        return $this->editor_user;
    }

    /**
     * Set deleter_user
     *
     * @param integer $deleterUser
     */
    public function setDeleterUser($deleterUser)
    {
        $this->deleter_user = $deleterUser;
    }

    /**
     * Get deleter_user
     *
     * @return integer 
     */
    public function getDeleterUser()
    {
        return $this->deleter_user;
    }

    /**
     * Set creator_user
     *
     * @param CCDNUser\UserBundle\Entity\User $creatorUser
     */
    public function setCreatorUser(\CCDNUser\UserBundle\Entity\User $creatorUser)
    {
        $this->creator_user = $creatorUser;
    }

    /**
     * Get creator_user
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getCreatorUser()
    {
        return $this->creator_user;
    }

    /**
     * Set creator
     *
     * @param CCDNUser\UserBundle\Entity\User $creator
     */
    public function setCreator(\CCDNUser\UserBundle\Entity\User $creator)
    {
        $this->creator = $creator;
    }

    /**
     * Get creator
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set created_by
     *
     * @param CCDNUser\UserBundle\Entity\User $createdBy
     */
    public function setCreatedBy(\CCDNUser\UserBundle\Entity\User $createdBy)
    {
        $this->created_by = $createdBy;
    }

    /**
     * Get created_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set edited_by
     *
     * @param CCDNUser\UserBundle\Entity\User $editedBy
     */
    public function setEditedBy(\CCDNUser\UserBundle\Entity\User $editedBy = null)
    {
        $this->edited_by = $editedBy;
    }

    /**
     * Get edited_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getEditedBy()
    {
        return $this->edited_by;
    }

    /**
     * Set deleted_by
     *
     * @param CCDNUser\UserBundle\Entity\User $deletedBy
     */
    public function setDeletedBy(\CCDNUser\UserBundle\Entity\User $deletedBy = null)
    {
        $this->deleted_by = $deletedBy;
    }

    /**
     * Get deleted_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getDeletedBy()
    {
        return $this->deleted_by;
    }

    /**
     * Set locked_date
     *
     * @param datetime $lockedDate
     */
    public function setLockedDate($lockedDate)
    {
        $this->locked_date = $lockedDate;
    }

    /**
     * Get locked_date
     *
     * @return datetime 
     */
    public function getLockedDate()
    {
        return $this->locked_date;
    }

    /**
     * Set locked_by
     *
     * @param CCDNUser\UserBundle\Entity\User $lockedBy
     */
    public function setLockedBy(\CCDNUser\UserBundle\Entity\User $lockedBy = null)
    {
        $this->locked_by = $lockedBy;
    }

    /**
     * Get locked_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getLockedBy()
    {
        return $this->locked_by;
    }
    public function __construct()
    {
        $this->flags = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add flags
     *
     * @param CCDNForum\ForumBundle\Entity\Flag $flags
     */
    public function addFlag(\CCDNForum\ForumBundle\Entity\Flag $flags)
    {
        $this->flags[] = $flags;
    }

    /**
     * Get flags
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Set attachment
     *
     * @param CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
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
        $this->is_deleted = $isDeleted;
    }

    /**
     * Get is_deleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * Set is_locked
     *
     * @param boolean $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->is_locked = $isLocked;
    }

    /**
     * Get is_locked
     *
     * @return boolean 
     */
    public function getIsLocked()
    {
        return $this->is_locked;
    }
}