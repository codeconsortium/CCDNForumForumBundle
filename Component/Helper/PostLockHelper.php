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

namespace CCDNForum\ForumBundle\Component\Helper;

use CCDNForum\ForumBundle\Entity\Post;

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
class PostLockHelper
{
    /**
     *
     * @access protected
     * @var bool $enabled
     */
    protected $enabled;

    /**
     *
     * @access protected
     * @var int $afterDays
     */
    protected $afterDays;

    /**
     *
     * @access public
     * @param bool $enabled
     * @param int  $afterDays
     */
    public function __construct($enabled, $afterDays)
    {
        $this->enabled = $enabled;
        $this->afterDays = $afterDays;
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     */
    public function setLockLimitOnPost(Post $post)
    {
        $post->setUnlockedUntilDate(new \Datetime('now + ' . $this->afterDays . ' days'));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post $post
     * @return bool
     */
    public function isLocked(Post $post)
    {
        if ($this->enabled) {
            return $post->isLocked();
        }

        return false;
    }
}
