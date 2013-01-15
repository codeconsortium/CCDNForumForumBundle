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

use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

abstract class Board
{
    /** @var Category $category */
    protected $category = null;

    /** @var ArrayCollection $topic */
    protected $topics;

    /** @var Post $lastPost */
    protected $lastPost = null;


    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param Category $category
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
    }

    /**
     * Get topics
     *
     * @return ArrayCollection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Set topics
     *
     * @param Topic $topics
     */
    public function setTopics(array $topics)
    {
        $this->topics[] = $topics;
    }

    /**
     * Add topics
     *
     * @param ArrayCollection $topics
     */
    public function addTopics(array $topics)
    {
        foreach ($topics as $topic) {
            $this->topics->add($topic);
        }
    }

    /**
     * Add topics
     *
     * @param Topic $topic
     */
    public function addTopic(Topic $topic)
    {
        $this->topics[] = $topic;
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
}
