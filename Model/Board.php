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
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set category
     *
     * @param CCDNForum\ForumBundle\Entity\Category $category
     */
    public function setCategory(\CCDNForum\ForumBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return CCDNForum\ForumBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get topics
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Add topics
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topics
     */
    public function addTopics(\CCDNForum\ForumBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;
    }

    /**
     * Add topics
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topics
     */
    public function addTopic(\CCDNForum\ForumBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;
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
}
