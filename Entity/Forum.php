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

use CCDNForum\ForumBundle\Entity\Model\Forum as AbstractForum;

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
class Forum extends AbstractForum
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var integer $id
     */
    protected $name;
	
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
	
    /**
     * Set name
     *
     * @return Forum
     */
    public function setName($name)
    {
        return $this->name = $name;
    }
}