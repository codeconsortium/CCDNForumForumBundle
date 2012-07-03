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

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\TopicRepository")
 * @ORM\Table(name="CC_Forum_Topic")
 */
class Topic
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
	/**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="topic", cascade={"remove"})
     */
    protected $posts;

    /**
     * @ORM\ManyToOne(targetEntity="Board", inversedBy="topics")
     * @ORM\JoinColumn(name="fk_board_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $board = null;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $title;

	/**
	 * @ORM\OneToOne(targetEntity="Post", cascade={"remove"})
	 * @ORM\JoinColumn(name="fk_first_post_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $firstPost = null;
		
	/**
	 * @ORM\OneToOne(targetEntity="Post", cascade={"remove"})
	 * @ORM\JoinColumn(name="fk_last_post_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $lastPost = null;
	
	/**
	 * @ORM\Column(type="integer", name="cached_view_count", nullable=false)
	 */
	protected $cachedViewCount = 0;
	
	/**
	 * @ORM\Column(type="integer", name="cached_reply_count", nullable=false)
	 */
	protected $cachedReplyCount = 0;
	
	/**
	 *
	 * @ORM\Column(type="boolean", name="is_closed", nullable=false)
	 */
	protected $isClosed = false;
	
	/**
	 * @ORM\Column(type="datetime", name="closed_date", nullable=true)
	 */
	protected $closedDate;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_closed_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $closedBy = null;
	
	/**
	 *
	 * @ORM\Column(type="boolean", name="is_deleted", nullable=false)
	 */
	protected $isDeleted = false;
	
	/**
	 * @ORM\Column(type="datetime", name="deleted_date", nullable=true)
	 */
	protected $deletedDate;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_deleted_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $deletedBy = null;
	
	/**
	 * @ORM\Column(type="datetime", name="stickied_date", nullable=true)
	 */
	protected $stickiedDate;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_stickied_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $stickiedBy = null;
	
	/**
	 * @ORM\Column(type="boolean", name="is_sticky", nullable=false)
	 */
	protected $isSticky = false;
	

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }



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
     * Add posts
     *
     * @param CCDNForum\ForumBundle\Entity\Post $posts
     */
    public function addPosts(\CCDNForum\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
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

    /**
     * Set last_post
     *
     * @param CCDNForum\ForumBundle\Entity\Post $lastPost
     */
    public function setLastPost(\CCDNForum\ForumBundle\Entity\Post $lastPost = null)
    {
        $this->lastPost = $lastPost;
    }

    /**
     * Get last_post
     *
     * @return CCDNForum\ForumBundle\Entity\Post 
     */
    public function getLastPost()
    {
        return $this->lastPost;
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
     * Set first_post
     *
     * @param CCDNForum\ForumBundle\Entity\Post $firstPost
     */
    public function setFirstPost(\CCDNForum\ForumBundle\Entity\Post $firstPost = null)
    {
        $this->firstPost = $firstPost;
    }

    /**
     * Get first_post
     *
     * @return CCDNForum\ForumBundle\Entity\Post 
     */
    public function getFirstPost()
    {
        return $this->firstPost;
    }

    /**
     * Add posts
     *
     * @param CCDNForum\ForumBundle\Entity\Post $posts
     */
    public function addPost(\CCDNForum\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Set closed_date
     *
     * @param datetime $closedDate
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;
    }

    /**
     * Get closed_date
     *
     * @return datetime 
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * Set closed_by
     *
     * @param CCDNUser\UserBundle\Entity\User $closedBy
     */
    public function setClosedBy(\CCDNUser\UserBundle\Entity\User $closedBy = null)
    {
        $this->closedBy = $closedBy;
    }

    /**
     * Get closed_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getClosedBy()
    {
        return $this->closedBy;
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
     * Set is_sticky
     *
     * @param boolean $isSticky
     */
    public function setIsSticky($isSticky)
    {
        $this->isSticky = $isSticky;
    }

    /**
     * Get is_sticky
     *
     * @return boolean 
     */
    public function getIsSticky()
    {
        return $this->isSticky;
    }

    /**
     * Set is_closed
     *
     * @param boolean $isClosed
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    }

    /**
     * Get is_closed
     *
     * @return boolean 
     */
    public function getIsClosed()
    {
        return $this->isClosed;
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
     * Set cachedViewCount
     *
     * @param integer $cachedViewCount
     */
    public function setCachedViewCount($cachedViewCount)
    {
        $this->cachedViewCount = $cachedViewCount;
    }

    /**
     * Get cachedViewCount
     *
     * @return integer 
     */
    public function getCachedViewCount()
    {
        return $this->cachedViewCount;
    }

    /**
     * Set cachedReplyCount
     *
     * @param integer $cachedReplyCount
     */
    public function setCachedReplyCount($cachedReplyCount)
    {
        $this->cachedReplyCount = $cachedReplyCount;
    }

    /**
     * Get cachedReplyCount
     *
     * @return integer 
     */
    public function getCachedReplyCount()
    {
        return $this->cachedReplyCount;
    }


    /**
     * Set stickiedDate
     *
     * @param datetime $stickiedDate
     */
    public function setStickiedDate($stickiedDate)
    {
        $this->stickiedDate = $stickiedDate;
    }

    /**
     * Get stickiedDate
     *
     * @return datetime 
     */
    public function getStickiedDate()
    {
        return $this->stickiedDate;
    }

    /**
     * Set stickiedBy
     *
     * @param CCDNUser\UserBundle\Entity\User $stickiedBy
     */
    public function setStickiedBy(\CCDNUser\UserBundle\Entity\User $stickiedBy = null)
    {
        $this->stickiedBy = $stickiedBy;
    }

    /**
     * Get stickiedBy
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getStickiedBy()
    {
        return $this->stickiedBy;
    }
}