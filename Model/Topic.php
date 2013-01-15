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

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Subscription;

abstract class Topic
{
    /** @var ArrayCollection $posts */
    protected $posts;

    /** @var Board $board */
    protected $board = null;

    /** @var Post $firstPost */
    protected $firstPost = null;

    /** @var Post $lastPost */
    protected $lastPost = null;

    /** @var User $closedBy */
    protected $closedBy = null;

    /** @var User $deletedBy */
    protected $deletedBy = null;

    /** @var User $stickiedBy */
    protected $stickiedBy = null;

    /** @var ArrayCollection $subscriptions */
    protected $subscriptions;

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get board
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set board
     *
     * @param Board $board
     */
    public function setBoard(\CCDNForum\ForumBundle\Entity\Board $board = null)
    {
        $this->board = $board;
    }

    /**
     * Get first_post
     *
     * @return Post
     */
    public function getFirstPost()
    {
        return $this->firstPost;
    }

    /**
     * Set first_post
     *
     * @param Post $firstPost
     */
    public function setFirstPost(\CCDNForum\ForumBundle\Entity\Post $firstPost = null)
    {
        $this->firstPost = $firstPost;
    }

    /**
     * Get last_post
     *
     * @return Post
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Set last_post
     *
     * @param Post $lastPost
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
     * @param Post $posts
     */
    public function addPosts(array $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Add posts
     *
     * @param Post $posts
     */
    public function addPost(\CCDNForum\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Get closed_by
     *
     * @return User
     */
    public function getClosedBy()
    {
        return $this->closedBy;
    }

    /**
     * Set closed_by
     *
     * @param User $closedBy
     */
    public function setClosedBy(\CCDNUser\UserBundle\Entity\User $closedBy = null)
    {
        $this->closedBy = $closedBy;
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
     * Get stickiedBy
     *
     * @return User
     */
    public function getStickiedBy()
    {
        return $this->stickiedBy;
    }

    /**
     * Set stickiedBy
     *
     * @param User $stickiedBy
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
     * @param Subscription $subscriptions
     */
    public function addSubscription(\CCDNForum\ForumBundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions[] = $subscriptions;
    }
}
