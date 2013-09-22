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

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Category as ConcreteCategory;
use CCDNForum\ForumBundle\Entity\Topic as ConcreteTopic;

abstract class Board
{
    /** @var Category $category */
    protected $category = null;

    /** @var ArrayCollection $topic */
    protected $topics;

    /** @var Post $lastPost */
    protected $lastPost;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
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
     * @param  Category $category
     * @return Board
     */
    public function setCategory(ConcreteCategory $category = null)
    {
        $this->category = $category;

        return $this;
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
     * @param  ArrayCollection $topics
     * @return Board
     */
    public function setTopics(ArrayCollection $topics = null)
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * Add topic
     *
     * @param  Topic $topic
     * @return Board
     */
    public function addTopic(ConcreteTopic $topic)
    {
        $this->topics->add($topic);

        return $this;
    }

    /**
     * @param Topic $topic
     *
     * @return $this
     */
    public function removeTopic(ConcreteTopic $topic)
    {
        $this->topics->removeElement($topic);

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
     * @return Board
     */
    public function setLastPost($lastPost = null)
    {
        $this->lastPost = $lastPost;

        return $this;
    }
}
