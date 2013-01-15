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

use CCDNForum\ForumBundle\Model\Topic as AbstractTopic;

class Topic extends AbstractTopic
{
    /** @var integer $id */
    protected $id;

    /** @var string $title */
    protected $title;

    /** @var integer $cachedViewCount */
    protected $cachedViewCount = 0;

    /** @var integer $cachedReplyCount */
    protected $cachedReplyCount = 0;

    /** @var Boolean $isClosed */
    protected $isClosed = false;

    /** \DateTime $closedDate */
    protected $closedDate;

    /** @var Boolean $isDeleted */
    protected $isDeleted = false;

    /** \DateTime $deletedDate */
    protected $deletedDate;

    /** \DateTime $stickiedDate */
    protected $stickiedDate;

    /** @var Boolean $isSticky */
    protected $isSticky = false;

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
     * Set closed_date
     *
     * @param \datetime $closedDate
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;
    }

    /**
     * Get closed_date
     *
     * @return \datetime
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }


    /**
     * Set deleted_date
     *
     * @param \datetime $deletedDate
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deletedDate = $deletedDate;
    }

    /**
     * Get deleted_date
     *
     * @return \datetime
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
    }

    /**
     * Set is_sticky
     *
     * @param boolean $isSticky
     */
    public function setIsSticky($isSticky)
    {
        $this->isSticky = $isSticky;
    }

    /**
     * Get is_sticky
     *
     * @return boolean
     */
    public function getIsSticky()
    {
        return $this->isSticky;
    }

    /**
     * Set is_closed
     *
     * @param boolean $isClosed
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    }

    /**
     * Get is_closed
     *
     * @return boolean
     */
    public function getIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * Set is_deleted
     *
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * Get is_deleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set cachedViewCount
     *
     * @param integer $cachedViewCount
     */
    public function setCachedViewCount($cachedViewCount)
    {
        $this->cachedViewCount = $cachedViewCount;
    }

    /**
     * Get cachedViewCount
     *
     * @return integer
     */
    public function getCachedViewCount()
    {
        return $this->cachedViewCount;
    }

    /**
     * Set cachedReplyCount
     *
     * @param integer $cachedReplyCount
     */
    public function setCachedReplyCount($cachedReplyCount)
    {
        $this->cachedReplyCount = $cachedReplyCount;
    }

    /**
     * Get cachedReplyCount
     *
     * @return integer
     */
    public function getCachedReplyCount()
    {
        return $this->cachedReplyCount;
    }

    /**
     * Set stickiedDate
     *
     * @param \datetime $stickiedDate
     */
    public function setStickiedDate($stickiedDate)
    {
        $this->stickiedDate = $stickiedDate;
    }

    /**
     * Get stickiedDate
     *
     * @return \datetime
     */
    public function getStickiedDate()
    {
        return $this->stickiedDate;
    }

}
