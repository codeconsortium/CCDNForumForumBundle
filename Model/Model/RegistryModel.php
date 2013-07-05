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

namespace CCDNForum\ForumBundle\Model\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\BaseModelInterface;

use CCDNForum\ForumBundle\Entity\Registry;

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
class RegistryModel extends BaseModel implements BaseModelInterface
{
    /**
     *
     * @access public
     * @param  int                                    $userId
     * @return \CCDNForum\ForumBundle\Entity\Registry
     */
    public function findRegistryForUserById($userId)
    {
        return $this->getRepository()->findRegistryForUserById($userId);
    }

    /**
     *
     * @access public
     * @param  Array                                        $userIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findRegistriesForTheseUsersById($registryUserIds = array())
    {
        return $this->getRepository()->findRegistriesForTheseUsersById($registryUserIds);
    }

    /**
     *
     * @access public
     * @param $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateCachedPostCountForUser(UserInterface $user)
    {
        return $this->getManager()->updateCachedPostCountForUser($user);
    }

    /**
     *
     * @access public
     * @param  Array                                               $users
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkUpdateCachedPostCountForUsers($users)
    {
        return $this->getManager()->bulkUpdateCachedPostCountForUsers($users);
    }
}