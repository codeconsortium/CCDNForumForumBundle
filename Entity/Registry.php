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

use CCDNForum\ForumBundle\Entity\Model\Registry as AbstractRegistry;

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
class Registry extends AbstractRegistry
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var integer $cachedPostCount
     */
    protected $cachedPostCount = 0;

    /**
     *
     * @var integer $cachedKarmaPositiveCount
     */
    protected $cachedKarmaPositiveCount = 0;

    /**
     *
     * @var integer $cachedKarmaNegativeCount
     */
    protected $cachedKarmaNegativeCount = 0;

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
     * Get cachedPostCount
     *
     * @return integer
     */
    public function getCachedPostCount()
    {
        return $this->cachedPostCount;
    }

    /**
     * Set cachedPostCount
     *
     * @param  integer  $cachedPostCount
     * @return Registry
     */
    public function setCachedPostCount($cachedPostCount)
    {
        $this->cachedPostCount = $cachedPostCount;

        return $this;
    }

    /**
     * Get cachedKarmaPositiveCount
     *
     * @return integer
     */
    public function getCachedKarmaPositiveCount()
    {
        return $this->cachedKarmaPositiveCount;
    }

    /**
     * Set cachedKarmaPositiveCount
     *
     * @param  integer  $cachedKarmaPositiveCount
     * @return Registry
     */
    public function setCachedKarmaPositiveCount($cachedKarmaPositiveCount)
    {
        $this->cachedKarmaPositiveCount = $cachedKarmaPositiveCount;

        return $this;
    }

    /**
     * Get cachedKarmaNegativeCount
     *
     * @return integer
     */
    public function getCachedKarmaNegativeCount()
    {
        return $this->cachedKarmaNegativeCount;
    }

    /**
     * Set cachedKarmaNegativeCount
     *
     * @param  integer  $cachedKarmaNegativeCount
     * @return Registry
     */
    public function setCachedKarmaNegativeCount($cachedKarmaNegativeCount)
    {
        $this->cachedKarmaNegativeCount = $cachedKarmaNegativeCount;

        return $this;
    }
}
