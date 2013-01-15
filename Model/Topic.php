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
    /** @var Board $board */
    protected $board = null;

    /** @var User $closedBy */
    protected $closedBy = null;

    /** @var User $deletedBy */
    protected $deletedBy = null;

    /** @var User $stickiedBy */
    protected $stickiedBy = null;

    /** @var Post $firstPost */
    protected $firstPost = null;

    /** @var Post $lastPost */
    protected $lastPost = null;

    /** @var ArrayCollection $posts */
    protected $posts;

    /** @var ArrayCollection $subscriptions */
    protected $subscriptions;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
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
    public function setBoard(Board $board = null)
    {
        $this->board = $board;
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
    public function setClosedBy(User $closedBy = null)
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
    public function setDeletedBy(User $deletedBy = null)
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
    public function setStickiedBy(User $stickiedBy = null)
    {
        $this->stickiedBy = $stickiedBy;
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
    public function setFirstPost(Post $firstPost = null)
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
    public function setLastPost(Post $lastPost = null)
    {
        $this->lastPost = $lastPost;
    }

    /**
     * Get posts
     *
     * @return ArrayCollection
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
     * @param ArrayCollection $posts
     */
    public function addPosts(array $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Add posts
     *
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;
    }

    /**
     * Get subscriptions
     *
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param ArrayCollection $subscriptions
     */
    public function setSubscriptions(array $subscriptions = null)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * Add subscriptions
     *
     * @param Subscription $subscription
     */
    public function addSubscription(Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;
    }
}
