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
     * @ORM\JoinColumn(name="fk_post_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $post = null;

    /**
     * @ORM\Column(type="integer")
     */
    protected $reason;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime", name="flagged_date")
     */
    protected $flaggedDate;

    /**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="fk_flagged_by_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $flaggedBy = null;

    /**
     * @ORM\Column(type="datetime", name="moderated_date", nullable=true)
     */
    protected $moderatedDate;

    /**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="fk_moderated_by_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $moderatedBy = null;

    /**
     * @ORM\Column(type="text", name="moderators_note", nullable=true)
     */
    protected $moderatorsNote;

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
        $this->flaggedDate = $flaggedDate;
    }

    /**
     * Get flagged_date
     *
     * @return datetime
     */
    public function getFlaggedDate()
    {
        return $this->flaggedDate;
    }

    /**
     * Set post
     *
     * @param CCDNForum\ForumBundle\Entity\Post $post
     */
    public function setPost(\CCDNForum\ForumBundle\Entity\Post $post = null)
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
    public function setFlaggedBy(\CCDNUser\UserBundle\Entity\User $flaggedBy = null)
    {
        $this->flaggedBy = $flaggedBy;
    }

    /**
     * Get flagged_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getFlaggedBy()
    {
        return $this->flaggedBy;
    }

    /**
     * Set moderators_note
     *
     * @param text $moderatorsNote
     */
    public function setModeratorsNote($moderatorsNote)
    {
        $this->moderatorsNote = $moderatorsNote;
    }

    /**
     * Get moderators_note
     *
     * @return text
     */
    public function getModeratorsNote()
    {
        return $this->moderatorsNote;
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
    public function setModeratedBy(\CCDNUser\UserBundle\Entity\User $moderatedBy = null)
    {
        $this->moderatedBy = $moderatedBy;
    }

    /**
     * Get moderated_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getModeratedBy()
    {
        return $this->moderatedBy;
    }

    /**
     * Set moderated_date
     *
     * @param datetime $moderatedDate
     */
    public function setModeratedDate($moderatedDate)
    {
        $this->moderatedDate = $moderatedDate;
    }

    /**
     * Get moderated_date
     *
     * @return datetime
     */
    public function getModeratedDate()
    {
        return $this->moderatedDate;
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
