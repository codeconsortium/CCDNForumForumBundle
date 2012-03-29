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

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNUser\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\DraftRepository")
 * @ORM\Table(name="CC_Forum_Draft")
 */
class Draft
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="Board")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $board;
	
	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	protected $title;
	
    /**
     * @ORM\ManyToOne(targetEntity="Topic", cascade={"persist"})
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $topic;
	
	/**
     * @ORM\Column(type="text", nullable=true)
     */
	protected $body;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="created_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $created_by;

	/**
     * @ORM\ManyToOne(targetEntity="CCDNComponent\AttachmentBundle\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinColumn(name="attachment_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $attachment;
	


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
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set created_date
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->created_date = $createdDate;
    }

    /**
     * Get created_date
     *
     * @return datetime 
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set topic
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topic
     */
    public function setTopic(\CCDNForum\ForumBundle\Entity\Topic $topic)
    {
        $this->topic = $topic;
    }

    /**
     * Get topic
     *
     * @return CCDNForum\ForumBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set created_by
     *
     * @param CCDNUser\UserBundle\Entity\User $createdBy
     */
    public function setCreatedBy(\CCDNUser\UserBundle\Entity\User $createdBy)
    {
        $this->created_by = $createdBy;
    }

    /**
     * Get created_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set attachment
     *
     * @param CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
     */
    public function setAttachment(\CCDNComponent\AttachmentBundle\Entity\Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Get attachment
     *
     * @return CCDNComponent\AttachmentBundle\Entity\Attachment 
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set board
     *
     * @param CCDNForum\ForumBundle\Entity\Board $board
     */
    public function setBoard(\CCDNForum\ForumBundle\Entity\Board $board)
    {
        $this->board = $board;
    }

    /**
     * Get board
     *
     * @return CCDNForum\ForumBundle\Entity\Board 
     */
    public function getBoard()
    {
        return $this->board;
    }
}