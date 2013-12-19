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

use Symfony\Component\Security\Core\User\UserInterface;
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
class RoleHelper
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    protected $securityContext;

    /**
     *
     * @access protected
     * @var array $availableRoles
     */
    protected $availableRoles;

    /**
     *
     * @access protected
     * @var array $availableRoleKeys
     */
    protected $availableRoleKeys;

    /**
     *
     * @access public
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     * @param array                                                     $availableRoles
     */
    public function __construct(SecurityContextInterface $securityContext, $availableRoles)
    {
        $this->securityContext = $securityContext;

        // default role is array is empty.
        if (empty($availableRoles)) {
            $availableRoles[] = 'ROLE_USER';
        }

        $this->availableRoles = $availableRoles;

        // Remove the associate arrays.
        $this->availableRoleKeys = array_keys($availableRoles);
    }

    /**
     *
     * @access public
     * @return Array
     */
    public function getRoleHierarchy()
    {
        $roles = array();

        foreach ($this->availableRoles as $roleName => $roleSubs) {
            $subs = '<ul><li>' . implode('</li><li>', $roleSubs) . '</li></ul>';
            $roles[$roleName] = '<strong>' . $roleName . '</strong>' . ($subs != '<ul><li>' . $roleName . '</li></ul>' ? "\n" . $subs:'');
        }

        return $roles;
    }

    /**
     *
     * @access public
     * @return array $availableRoles
     */
    public function getAvailableRoles()
    {
        return $this->availableRoles;
    }

    /**
     *
     * @access public
     * @return array $availableRoles
     */
    public function getAvailableRoleKeys()
    {
        return $this->availableRoleKeys;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @param  string                                              $role
     * @return bool
     */
    public function hasRole(UserInterface $user, $role)
    {
        foreach ($this->availableRoles as $aRoleKey => $aRole) {
            if ($user->hasRole($aRoleKey)) {
                if (in_array($role, $aRole) || $role == $aRoleKey) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * @access public
     * @param  array $userRoles
     * @return int   $highestUsersRoleKey
     */
    public function getUsersHighestRole($usersRoles)
    {
        $usersHighestRoleKey = 0;

        // Compare (A)vailable roles against (U)sers roles.
        foreach ($this->availableRoleKeys as $aRoleKey => $aRole) {
            foreach ($usersRoles as $uRole) {
                if ($uRole == $aRole && $aRoleKey > $usersHighestRoleKey) {
                    $usersHighestRoleKey = $aRoleKey;

                    break; // break because once uRole == aRole we know we cannot match anything else.
                }
            }
        }

        return $usersHighestRoleKey;
    }

    /**
     *
     * @access public
     * @param  array  $userRoles
     * @return string $role
     */
    public function getUsersHighestRoleAsName($usersRoles)
    {
        $usersHighestRoleKey = $this->getUsersHighestRole($usersRoles);

        $roles = $this->availableRoleKeys;

        if (array_key_exists($usersHighestRoleKey, $roles)) {
            return $roles[$usersHighestRoleKey];
        } else {
            return 'ROLE_USER';
        }
    }
}
