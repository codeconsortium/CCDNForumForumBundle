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

use CCDNForum\ForumBundle\Entity\Model\Category as AbstractCategory;

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
class Category extends AbstractCategory
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var string $name
     */
    protected $name;

    /**
     *
     * @var integer $listOrderPriority
     */
    protected $listOrderPriority;

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
     * @param  string   $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get list_order_priority
     *
     * @return integer
     */
    public function getListOrderPriority()
    {
        return $this->listOrderPriority;
    }

    /**
     * Set list_order_priority
     *
     * @param  integer  $listOrderPriority
     * @return Category
     */
    public function setListOrderPriority($listOrderPriority)
    {
        $this->listOrderPriority = $listOrderPriority;

        return $this;
    }
}
