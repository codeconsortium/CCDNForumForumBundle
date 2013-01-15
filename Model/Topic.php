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

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Subscription;

abstract class Topic
{
    /** @var Board $board */
    protected $board = null;

    /** @var UserInterface $closedBy */
    protected $closedBy = null;

    /** @var UserInterface $deletedBy */
    protected $deletedBy = null;

    /** @var UserInterface $stickiedBy */
    protected $stickiedBy = null;

    /** @var PostInterface $firstPost */
    protected $firstPost = null;

    /** @var PostInterface $lastPost */
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
     * @return UserInterface
     */
    public function getClosedBy()
    {
        return $this->closedBy;
    }

    /**
     * Set closed_by
     *
     * @param UserInterface $closedBy
     */
    public function setClosedBy(UserInterface $closedBy = null)
    {
        $this->closedBy = $closedBy;
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
     * Get stickiedBy
     *
     * @return UserInterface
     */
    public function getStickiedBy()
    {
        return $this->stickiedBy;
    }

    /**
     * Set stickiedBy
     *
     * @param UserInterface $stickiedBy
     */
    public function setStickiedBy(UserInterface $stickiedBy = null)
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
    public function setPosts(array $posts = null)
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
        foreach($posts as $post) {
            $this->posts->add($post);
        }
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
     * @param ArrayCollection $subscriptions
     */
    public function addSubscriptions(array $subscriptions)
    {
        foreach($subscriptions as $subscription) {
            $this->subscriptions->add($subscription);
        }
    }

    /**
     * Add subscription
     *
     * @param Subscription $subscription
     */
    public function addSubscription(Subscription $subscription)
    {
        $this->subscriptions->add($subscription);
    }
}
