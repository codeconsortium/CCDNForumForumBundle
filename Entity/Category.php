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

use CCDNForum\ForumBundle\Entity\Board;
//use CCDNForum\ForumBundle\Entity\Topic;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\CategoryRepository")
 * @ORM\Table(name="CC_Forum_Category")
 */
class Category
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
	/**
     * @ORM\OneToMany(targetEntity="Board", mappedBy="category", cascade={"remove"})
     */
    protected $boards;

	/**
     * @ORM\Column(type="string", length=100)
     */
	protected $name;

	/**
	 * @ORM\Column(type="integer", name="list_order_priority")
	 */
	protected $listOrderPriority;
	
	
    public function __construct()
    {
        $this->boards = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add boards
     *
     * @param CCDNForum\ForumBundle\Entity\Board $boards
     */
    public function addBoards(\CCDNForum\ForumBundle\Entity\Board $boards)
    {
        $this->boards[] = $boards;
    }

    /**
     * Get boards
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add boards
     *
     * @param CCDNForum\ForumBundle\Entity\Board $boards
     */
    public function addBoard(\CCDNForum\ForumBundle\Entity\Board $boards)
    {
        $this->boards[] = $boards;
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
}