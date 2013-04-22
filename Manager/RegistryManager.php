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

namespace CCDNForum\ForumBundle\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

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
class RegistryManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  int                                    $userId
     * @return \CCDNForum\ForumBundle\Entity\Registry
     */
    public function findRegistryForUserById($userId)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $params = array(':userId' => $userId);

        $qb = $this->createSelectQuery(array('r'));

        $qb->where('r.ownedBy = :userId');

        return $this->gateway->findRegistry($qb, $params);
    }

    /**
     *
     * @access public
     * @param  Array                                        $userIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findRegistriesForTheseUsersById($registryUserIds = array())
    {
        if (! is_array($registryUserIds) || count($registryUserIds) < 1) {
            throw new \Exception('Parameter 1 must be an array and contain at least 1 user id!');
        }

        $qb = $this->createSelectQuery(array('r'));

        $qb->where($qb->expr()->in('r.ownedBy', $registryUserIds));

        $registriesTemp = $this->gateway->findRegistries($qb, $params);

        // Sort the registries so that the user[id] is the key of the users registry entity.
        $registries = array();

        foreach ($registriesTemp as $key => $registry) {
            $registries[$registry->getOwnedBy()->getId()] = $registry;
        }

        if (count($registries) < 1) {
            $registries = array();
        }

        return $registries;
    }

    /**
     *
     * @access public
     * @param $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateCachedPostCountForUser(UserInterface $user)
    {
        $record = $this->findRegistryForUserById($user->getId());

        if (! $record) {
            $record = new Registry();
            $record->setOwnedBy($user);
        }

        $postCount = $this->managerBag->getPostManager()->getPostCountForUserById($user->getId());

        if (! $postCount) {
            $record->setCachedPostCount(0);
        } else {
            $record->setCachedPostCount($postCount['postCount']);
        }

        $this->persist($record)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                               $users
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkUpdateCachedPostCountForUsers($users)
    {
        foreach ($users as $user) {
            $this->updateCachePostCountForUser($user);
        }
    }
}
