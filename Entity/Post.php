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

use CCDNForum\ForumBundle\Entity\Model\Post as AbstractPost;

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
class Post extends AbstractPost
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var string $body
     */
    protected $body;

    /**
     *
     * @var \DateTime $createdDate
     */
    protected $createdDate;

    /**
     *
     * @var \DateTime $editedDate
     */
    protected $editedDate;

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
     * @var \DateTime $unlockedDate
     */
    protected $unlockedDate;

    /**
     *
     * @var \DateTime $unlockedUntilDate
     */
    protected $unlockedUntilDate;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        // your own logic

        $this->unlockedDate = new \Datetime('now');
        $this->unlockedUntilDate = new \Datetime('now + 7 days');
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
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set body
     *
     * @param  string $body
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \datetime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set createdDate
     *
     * @param  \datetime $createdDate
     * @return Post
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get edited_date
     *
     * @return \datetime
     */
    public function getEditedDate()
    {
        return $this->editedDate;
    }

    /**
     * Set editedDate
     *
     * @param  \datetime $editedDate
     * @return Post
     */
    public function setEditedDate($editedDate)
    {
        $this->editedDate = $editedDate;

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
     * Set is_deleted
     *
     * @param  boolean $isDeleted
     * @return Post
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
     * @return Post
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deletedDate = $deletedDate;

        return $this;
    }

    /**
     * Get unlockedDate
     *
     * @return \datetime
     */
    public function getUnlockedDate()
    {
        return $this->unlockedDate;
    }

    /**
     * Set unlockedDate
     *
     * @param  \datetime $datetime
     * @return Post
     */
    public function setUnlockedDate(\Datetime $datetime)
    {
        $this->unlockedDate = $datetime;

        return $this;
    }

    /**
     * Get unlockedUntilDate
     *
     * @return \datetime
     */
    public function getUnlockedUntilDate()
    {
        return $this->unlockedUntilDate;
    }

    /**
     * Set unlockedUntilDate
     *
     * @param  \datetime $datetime
     * @return Post
     */
    public function setUnlockedUntilDate(\Datetime $datetime)
    {
        $this->unlockedUntilDate = $datetime;

        return $this;
    }

    /**
     * Get isUnlocked
     *
     * @return \datetime
     */
    public function isLocked()
    {
        return $this->unlockedUntilDate > new \Datetime('now') ? false : true;
    }
}
