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

use CCDNForum\ForumBundle\Model\Draft as AbstractDraft;

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
class Draft extends AbstractDraft
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
     * @var string $body
     */
    protected $body;

    /**
     *
     * @var \Datetime $createdDate
     */
    protected $createdDate;

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
     * @return Draft
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
     * @return Draft
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
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
     * @return Draft
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
