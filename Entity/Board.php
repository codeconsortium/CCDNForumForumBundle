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
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;

	/**
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="board", cascade={"remove"})
     */
    protected $topics;
    
	/**
     * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank(message = "Board name must not be blank!")
     */
	protected $name;
	
	/**
     * @ORM\Column(type="text")
	 * @Assert\NotBlank(message = "Description must not be blank!")
     */
	protected $description;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $topic_count;
	
	/**
	 * @ORM\Column(type="integer")
	 */	
	protected $post_count;
	
	/**
	 * @ORM\OneToOne(targetEntity="Post")
	 */
	protected $last_post;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $list_order_priority;



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
     * Set topic_count
     *
     * @param integer $topicCount
     */
    public function setTopicCount($topicCount)
    {
        $this->topic_count = $topicCount;
    }

    /**
     * Get topic_count
     *
     * @return integer 
     */
    public function getTopicCount()
    {
        return $this->topic_count;
    }

    /**
     * Set post_count
     *
     * @param integer $postCount
     */
    public function setPostCount($postCount)
    {
        $this->post_count = $postCount;
    }

    /**
     * Get post_count
     *
     * @return integer 
     */
    public function getPostCount()
    {
        return $this->post_count;
    }

    /**
     * Set category
     *
     * @param CCDNForum\ForumBundle\Entity\Category $category
     */
    public function setCategory(\CCDNForum\ForumBundle\Entity\Category $category)
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
        $this->list_order_priority = $listOrderPriority;
    }

    /**
     * Get list_order_priority
     *
     * @return integer 
     */
    public function getListOrderPriority()
    {
        return $this->list_order_priority;
    }
}