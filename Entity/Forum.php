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
