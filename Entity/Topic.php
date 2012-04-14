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
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $board;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank(message = "Title must not be blank!")
	 */
	protected $title;

	/**
	 * @ORM\OneToOne(targetEntity="Post", cascade={"remove"})
	 */
	protected $first_post;
		
	/**
	 * @ORM\OneToOne(targetEntity="Post", cascade={"remove"})
	 */
	protected $last_post;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $view_count;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $reply_count;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $closed_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="closed_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $closed_by;
	
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
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $is_sticky;
	

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
    public function setBoard(\CCDNForum\ForumBundle\Entity\Board $board)
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
     * Set view_count
     *
     * @param integer $viewCount
     */
    public function setViewCount($viewCount)
    {
        $this->view_count = $viewCount;
    }

    /**
     * Get view_count
     *
     * @return integer 
     */
    public function getViewCount()
    {
        return $this->view_count;
    }

    /**
     * Set reply_count
     *
     * @param integer $replyCount
     */
    public function setReplyCount($replyCount)
    {
        $this->reply_count = $replyCount;
    }

    /**
     * Get reply_count
     *
     * @return integer 
     */
    public function getReplyCount()
    {
        return $this->reply_count;
    }

    /**
     * Set last_post
     *
     * @param CCDNForum\ForumBundle\Entity\Post $lastPost
     */
    public function setLastPost(\CCDNForum\ForumBundle\Entity\Post $lastPost = null)
    {
        $this->last_post = $lastPost;
    }

    /**
     * Get last_post
     *
     * @return CCDNForum\ForumBundle\Entity\Post 
     */
    public function getLastPost()
    {
        return $this->last_post;
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
        $this->first_post = $firstPost;
    }

    /**
     * Get first_post
     *
     * @return CCDNForum\ForumBundle\Entity\Post 
     */
    public function getFirstPost()
    {
        return $this->first_post;
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
        $this->closed_date = $closedDate;
    }

    /**
     * Get closed_date
     *
     * @return datetime 
     */
    public function getClosedDate()
    {
        return $this->closed_date;
    }

    /**
     * Set closed_by
     *
     * @param CCDNUser\UserBundle\Entity\User $closedBy
     */
    public function setClosedBy(\CCDNUser\UserBundle\Entity\User $closedBy = null)
    {
        $this->closed_by = $closedBy;
    }

    /**
     * Get closed_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getClosedBy()
    {
        return $this->closed_by;
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
     * Set is_sticky
     *
     * @param boolean $isSticky
     */
    public function setIsSticky($isSticky)
    {
        $this->is_sticky = $isSticky;
    }

    /**
     * Get is_sticky
     *
     * @return boolean 
     */
    public function getIsSticky()
    {
        return $this->is_sticky;
    }
}