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

namespace CCDNForum\ForumBundle\Model\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNForum\ForumBundle\Model\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

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
class RegistryManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access public
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
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
     * @param  Array                                           $users
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function bulkUpdateCachedPostCountForUsers($users)
    {
        foreach ($users as $user) {
            $this->updateCachePostCountForUser($user);
        }
    }
}
