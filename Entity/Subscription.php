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

use CCDNForum\ForumBundle\Entity\Model\Subscription as AbstractSubscription;

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
class Subscription extends AbstractSubscription
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var Boolean $isRead
     */
    protected $isRead = false;

    /**
     *
     * @var Boolean $isSubscribed
     */
    protected $isSubscribed = false;

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
     * Get isRead
     *
     * @return boolean
     */
    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * Set isRead
     *
     * @param  boolean      $isRead
     * @return Subscription
     */
    public function setRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isSubscribed
     *
     * @return boolean
     */
    public function isSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * Set isSubscribed
     *
     * @param  boolean      $isSubscribed
     * @return Subscription
     */
    public function setSubscribed($isSubscribed)
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }
}
