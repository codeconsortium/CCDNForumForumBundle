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
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\FlagRepository")
 * @ORM\Table(name="CC_Forum_Flag")
 */
class Flag
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="CCDNForum\ForumBundle\Entity\Post", inversedBy="flags", cascade={"persist"})
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $post;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $reason;
	
	/**
     * @ORM\Column(type="text")
     */
	protected $description;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $flagged_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="flagged_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $flagged_by;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $moderated_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="moderated_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $moderated_by;

	/**
     * @ORM\Column(type="text", nullable=true)
     */
	protected $moderators_note;
		
	/**
     * @ORM\Column(type="integer", nullable=true)
     */
	protected $status;
	
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
     * Set reason
     *
     * @param text $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * Get reason
     *
     * @return text 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set flagged_date
     *
     * @param datetime $flaggedDate
     */
    public function setFlaggedDate($flaggedDate)
    {
        $this->flagged_date = $flaggedDate;
    }

    /**
     * Get flagged_date
     *
     * @return datetime 
     */
    public function getFlaggedDate()
    {
        return $this->flagged_date;
    }

    /**
     * Set post
     *
     * @param CCDNForum\ForumBundle\Entity\Post $post
     */
    public function setPost(\CCDNForum\ForumBundle\Entity\Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return CCDNForum\ForumBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set flagged_by
     *
     * @param CCDNUser\UserBundle\Entity\User $flaggedBy
     */
    public function setFlaggedBy(\CCDNUser\UserBundle\Entity\User $flaggedBy)
    {
        $this->flagged_by = $flaggedBy;
    }

    /**
     * Get flagged_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getFlaggedBy()
    {
        return $this->flagged_by;
    }

    /**
     * Set moderators_note
     *
     * @param text $moderatorsNote
     */
    public function setModeratorsNote($moderatorsNote)
    {
        $this->moderators_note = $moderatorsNote;
    }

    /**
     * Get moderators_note
     *
     * @return text 
     */
    public function getModeratorsNote()
    {
        return $this->moderators_note;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set moderated_by
     *
     * @param CCDNUser\UserBundle\Entity\User $moderatedBy
     */
    public function setModeratedBy(\CCDNUser\UserBundle\Entity\User $moderatedBy)
    {
        $this->moderated_by = $moderatedBy;
    }

    /**
     * Get moderated_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getModeratedBy()
    {
        return $this->moderated_by;
    }

    /**
     * Set moderated_date
     *
     * @param datetime $moderatedDate
     */
    public function setModeratedDate($moderatedDate)
    {
        $this->moderated_date = $moderatedDate;
    }

    /**
     * Get moderated_date
     *
     * @return datetime 
     */
    public function getModeratedDate()
    {
        return $this->moderated_date;
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
}