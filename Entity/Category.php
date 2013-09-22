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
use Symfony\Component\Security\Core\SecurityContextInterface;

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
    protected $listOrderPriority = 0;

    /**
     *
     * @var array $readAuthorisedRoles
     */
    protected $readAuthorisedRoles;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        // your own logic
        $this->readAuthorisedRoles = array();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function forumName()
    {
        if ($this->getForum()) {
            return $this->getForum()->getName();
        }

        return 'Unassigned';
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

    /**
     *
     * @return array
     */
    public function getReadAuthorisedRoles()
    {
        return $this->readAuthorisedRoles;
    }

    /**
     *
     * @param  array $roles
     * @return Board
     */
    public function setReadAuthorisedRoles(array $roles = null)
    {
        $this->readAuthorisedRoles = $roles;

        return $this;
    }

    /**
     *
     * @param $role
     * @return bool
     */
    public function hasReadAuthorisedRole($role)
    {
        return in_array($role, $this->readAuthorisedRoles);
    }

    /**
     *
     * @param  SecurityContextInterface $securityContext
     * @return bool
     */
    public function isAuthorisedToRead(SecurityContextInterface $securityContext)
    {
        if (0 == count($this->readAuthorisedRoles)) {
            return true;
        }

        foreach ($this->readAuthorisedRoles as $role) {
            if ($securityContext->isGranted($role)) {
                return true;
            }
        }

        return false;
    }
}
