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

abstract class Topic
{
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
     * @ORM\OneToOne(targetEntity="Post", cascade={"remove"})
     */
    protected $firstPost = null;

    /**
     * @ORM\OneToOne(targetEntity="Post", cascade={"remove"})
     */
    protected $lastPost = null;

    /** @var \CCDNUser\UserBundle\Entity\User $closedBy */
    protected $closedBy = null;

    /** @var \CCDNUser\UserBundle\Entity\User $deletedBy */
    protected $deletedBy = null;

    /** @var \CCDNUser\UserBundle\Entity\User $stickiedBy */
    protected $stickiedBy = null;

    /** @var \CCDNForum\ForumBundle\Entity\Subscription $subscriptions */
    protected $subscriptions;

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get board
     *
     * @return \CCDNForum\ForumBundle\Entity\Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set board
     *
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     */
    public function setBoard(\CCDNForum\ForumBundle\Entity\Board $board = null)
    {
        $this->board = $board;
    }

    /**
     * Get first_post
     *
     * @return \CCDNForum\ForumBundle\Entity\Post
     */
    public function getFirstPost()
    {
        return $this->firstPost;
    }

    /**
     * Set first_post
     *
     * @param \CCDNForum\ForumBundle\Entity\Post $firstPost
     */
    public function setFirstPost(\CCDNForum\ForumBundle\Entity\Post $firstPost = null)
    {
        $this->firstPost = $firstPost;
    }

    /**
     * Get last_post
     *
     * @return \CCDNForum\ForumBundle\Entity\Post
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Set last_post
     *
     * @param \CCDNForum\ForumBundle\Entity\Post $lastPost
     */
    public function setLastPost(\CCDNForum\ForumBundle\Entity\Post $lastPost = null)
    {
        $this->lastPost = $lastPost;
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set posts
     *
     * @param ArrayCollection $posts
     */
    public function setPosts(array $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Add posts
     *
     * @param \CCDNForum\ForumBundle\Entity\Post $posts
     */
    public function addPosts(\CCDNForum\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Add posts
     *
     * @param \CCDNForum\ForumBundle\Entity\Post $posts
     */
    public function addPost(\CCDNForum\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Get closed_by
     *
     * @return \CCDNUser\UserBundle\Entity\User
     */
    public function getClosedBy()
    {
        return $this->closedBy;
    }

    /**
     * Set closed_by
     *
     * @param \CCDNUser\UserBundle\Entity\User $closedBy
     */
    public function setClosedBy(\CCDNUser\UserBundle\Entity\User $closedBy = null)
    {
        $this->closedBy = $closedBy;
    }


    /**
     * Get deleted_by
     *
     * @return \CCDNUser\UserBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set deleted_by
     *
     * @param \CCDNUser\UserBundle\Entity\User $deletedBy
     */
    public function setDeletedBy(\CCDNUser\UserBundle\Entity\User $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * Get stickiedBy
     *
     * @return \CCDNUser\UserBundle\Entity\User
     */
    public function getStickiedBy()
    {
        return $this->stickiedBy;
    }
    
    /**
     * Set stickiedBy
     *
     * @param \CCDNUser\UserBundle\Entity\User $stickiedBy
     */
    public function setStickiedBy(\CCDNUser\UserBundle\Entity\User $stickiedBy = null)
    {
        $this->stickiedBy = $stickiedBy;
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Add subscriptions
     *
     * @param \CCDNForum\ForumBundle\Entity\Subscription $subscriptions
     */
    public function addSubscription(\CCDNForum\ForumBundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions[] = $subscriptions;
    }
}
