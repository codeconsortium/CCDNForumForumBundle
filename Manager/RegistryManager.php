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

use CCDNForum\ForumBundle\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Registry;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RegistryManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access public
     * @param $user
     * @return self
     */
    public function updateCachePostCountForUser($user)
    {

        $record = $this->repository->findRegistryRecordForUser($user->getId());

        if (! $record) {
            $record = new Registry();
            $record->setOwnedBy($user);
        }

        $postCount = $this->managerBag->getPostManager()->getRepository()->getPostCountForUserById($user->getId());

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
     * @param array $users
     * @return self
     */
    public function bulkUpdateCachePostCountForUser($users)
    {
        foreach ($users as $user) {
            $this->updateCachePostCountForUser($user);
        }
    }

    /**
     *
     * @access public
     * @param array $registryUserIds
     * @return self
     */
    public function getRegistriesForUsersAsArray($registryUserIds = array())
    {

        $registriesTemp = $this->repository->getPostCountsForUsers($registryUserIds);

        //
        // Sort the registries so that the [id] is the key of the parent key.
        //
        $registries = array();

        foreach ($registriesTemp as $key => $registry) {
            $registries[$registry->getOwnedBy()->getId()] = $registry;
        }

        if (! @isset($registries)) {
            $registries = array();
        } else {
            if (count($registries) < 1) {
                $registries = array();
            }
        }

        return $registries;
    }
}