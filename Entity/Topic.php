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
	 *
	 * @access public
	 */
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
	 * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;
		
		return $this;
    }

    /**
     * Get closedDate
     *
     * @return \datetime
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * Set closedDate
     *
     * @param \datetime $closedDate
	 * @return Topic
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;
		
		return $this;
    }

    /**
     * Get deletedDate
     *
     * @return \datetime
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
    }

    /**
     * Set deletedDate
     *
     * @param \datetime $deletedDate
	 * @return Topic
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deletedDate = $deletedDate;
		
		return $this;
    }

    /**
     * Get isSticky
     *
     * @return boolean
     */
    public function getIsSticky()
    {
        return $this->isSticky;
    }
	
    /**
     * Set isSticky
     *
     * @param boolean $isSticky
	 * @return Topic
     */
    public function setIsSticky($isSticky)
    {
        $this->isSticky = $isSticky;
		
		return $this;
    }

    /**
     * Get isClosed
     *
     * @return boolean
     */
    public function getIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * Set isClosed
     *
     * @param boolean $isClosed
	 * @return Topic
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
		
		return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
	 * @return Topic
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
		
		return $this;
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
     * Set cachedViewCount
     *
     * @param integer $cachedViewCount
	 * @return Topic
     */
    public function setCachedViewCount($cachedViewCount)
    {
        $this->cachedViewCount = $cachedViewCount;
		
		return $this;
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
     * Set cachedReplyCount
     *
     * @param integer $cachedReplyCount
	 * @return Topic
     */
    public function setCachedReplyCount($cachedReplyCount)
    {
        $this->cachedReplyCount = $cachedReplyCount;
		
		return $this;
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
	
    /**
     * Set stickiedDate
     *
     * @param \datetime $stickiedDate
	 * @return Topic
     */
    public function setStickiedDate($stickiedDate)
    {
        $this->stickiedDate = $stickiedDate;
		
		return $this;
    }
}
