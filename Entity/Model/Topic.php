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

namespace CCDNForum\ForumBundle\Entity\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Board as ConcreteBoard;
use CCDNForum\ForumBundle\Entity\Post as ConcretePost;
use CCDNForum\ForumBundle\Entity\Subscription as ConcreteSubscription;

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

    /** @var Post $firstPost */
    protected $firstPost = null;

    /** @var Post $lastPost */
    protected $lastPost = null;

    /** @var ArrayCollection $posts */
    protected $posts;

    /** @var ArrayCollection $subscriptions */
    protected $subscriptions;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
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
     * @param  Board $board
     * @return Topic
     */
    public function setBoard(ConcreteBoard $board = null)
    {
        $this->board = $board;

        return $this;
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
     * @param  UserInterface $closedBy
     * @return Topic
     */
    public function setClosedBy(UserInterface $closedBy = null)
    {
        $this->closedBy = $closedBy;

        return $this;
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
     * @param  UserInterface $deletedBy
     * @return Topic
     */
    public function setDeletedBy(UserInterface $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;

        return $this;
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
     * @param  UserInterface $stickiedBy
     * @return Topic
     */
    public function setStickiedBy(UserInterface $stickiedBy = null)
    {
        $this->stickiedBy = $stickiedBy;

        return $this;
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
     * @param  Post  $firstPost
     * @return Topic
     */
    public function setFirstPost(ConcretePost $firstPost = null)
    {
        $this->firstPost = $firstPost;

        return $this;
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
     * @param  Post  $lastPost
     * @return Topic
     */
    public function setLastPost(ConcretePost $lastPost = null)
    {
        $this->lastPost = $lastPost;

        return $this;
    }

    /**
     *
     * Get posts
     *
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     *
     * Set posts
     *
     * @param  ArrayCollection $posts
     * @return Topic
     */
    public function setPosts(ArrayCollection $posts = null)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     *
     * Add post
     *
     * @param  Post  $post
     * @return Topic
     */
    public function addPost(ConcretePost $post)
    {
        $this->posts->add($post);

        return $this;
    }

    /**
     *
     * @param  Post  $post
     * @return Topic
     */
    public function removePost(ConcretePost $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     *
     * Get subscriptions
     *
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     *
     * @param  ArrayCollection $subscriptions
     * @return Topic
     */
    public function setSubscriptions(ArrayCollection $subscriptions = null)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * Add subscription
     *
     * @param  Subscription $subscription
     * @return Topic
     */
    public function addSubscription(ConcreteSubscription $subscription)
    {
        $this->subscriptions->add($subscription);

        return $this;
    }

    /**
     *
     * @param Subscription $subscription
     * @return $this
     */
    public function removeSubscription(ConcreteSubscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);

        return $this;
    }
}
