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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\BoardRepository")
 * @ORM\Table(name="CC_Forum_Board")
 */
class Board
{
	
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="boards")
     * @ORM\JoinColumn(name="fk_category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category = null;

    /**
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="board", cascade={"remove"})
     */
    protected $topics;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", name="cached_topic_count")
     */
    protected $cachedTopicCount = 0;

    /**
     * @ORM\Column(type="integer", name="cached_post_count")
     */
    protected $cachedPostCount = 0;

    /**
     * @ORM\OneToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="fk_last_post_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $lastPost = null;

    /**
     * @ORM\Column(type="integer", name="list_order_priority")
     */
    protected $listOrderPriority = 0;

    public function __construct()
    {
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
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
     * Add topics
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topics
     */
    public function addTopics(\CCDNForum\ForumBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;
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
     * Add topics
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topics
     */
    public function addTopic(\CCDNForum\ForumBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;
    }

    /**
     * Set list_order_priority
     *
     * @param integer $listOrderPriority
     */
    public function setListOrderPriority($listOrderPriority)
    {
        $this->listOrderPriority = $listOrderPriority;
    }

    /**
     * Get list_order_priority
     *
     * @return integer
     */
    public function getListOrderPriority()
    {
        return $this->listOrderPriority;
    }

    /**
     * Set cachedTopicCount
     *
     * @param integer $cachedTopicCount
     */
    public function setCachedTopicCount($cachedTopicCount)
    {
        $this->cachedTopicCount = $cachedTopicCount;
    }

    /**
     * Get cachedTopicCount
     *
     * @return integer
     */
    public function getCachedTopicCount()
    {
        return $this->cachedTopicCount;
    }

    /**
     * Set cachedPostCount
     *
     * @param integer $cachedPostCount
     */
    public function setCachedPostCount($cachedPostCount)
    {
        $this->cachedPostCount = $cachedPostCount;
    }

    /**
     * Get cachedPostCount
     *
     * @return integer
     */
    public function getCachedPostCount()
    {
        return $this->cachedPostCount;
    }

}
