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

use CCDNForum\ForumBundle\Entity\Model\Topic as AbstractTopic;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class Topic extends AbstractTopic
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var string $title
     */
    protected $title;

    /**
     *
     * @var integer $cachedViewCount
     */
    protected $cachedViewCount = 0;

    /**
     *
     * @var integer $cachedReplyCount
     */
    protected $cachedReplyCount = 0;

    /**
     *
     * @var Boolean $isClosed
     */
    protected $isClosed = false;

    /**
     *
     * @var \DateTime $closedDate
     */
    protected $closedDate;

    /**
     *
     * @var Boolean $isDeleted
     */
    protected $isDeleted = false;

    /**
     *
     * @var \DateTime $deletedDate
     */
    protected $deletedDate;

    /**
     *
     * @var \DateTime $stickiedDate
     */
    protected $stickiedDate;

    /**
     *
     *  @var Boolean $isSticky
     */
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
     * @param  string $title
     * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @param  integer $cachedViewCount
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
     * @param  integer $cachedReplyCount
     * @return Topic
     */
    public function setCachedReplyCount($cachedReplyCount)
    {
        $this->cachedReplyCount = $cachedReplyCount;

        return $this;
    }

    /**
     * Get isClosed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->isClosed;
    }

    /**
     * Set isClosed
     *
     * @param  boolean $isClosed
     * @return Topic
     */
    public function setClosed($isClosed)
    {
        $this->isClosed = $isClosed;

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
     * @param  \datetime $closedDate
     * @return Topic
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDeleted
     *
     * @param  boolean $isDeleted
     * @return Topic
     */
    public function setDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

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
     * @param  \datetime $deletedDate
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
    public function isSticky()
    {
        return $this->isSticky;
    }

    /**
     * Set isSticky
     *
     * @param  boolean $isSticky
     * @return Topic
     */
    public function setSticky($isSticky)
    {
        $this->isSticky = $isSticky;

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
     * @param  \datetime $stickiedDate
     * @return Topic
     */
    public function setStickiedDate($stickiedDate)
    {
        $this->stickiedDate = $stickiedDate;

        return $this;
    }
}
