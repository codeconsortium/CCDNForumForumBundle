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

namespace CCDNForum\ForumBundle\Model\Component\Manager;

use CCDNForum\ForumBundle\Model\Component\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\BaseManager;
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
     * @return \CCDNForum\ForumBundle\Entity\Registry
     */
    public function createRegistry()
    {
        return $this->gateway->createRegistry();
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Registry                         $registry
     * @return \CCDNForum\ForumBundle\Model\Component\Manager\RegistryManager
     */
    public function saveRegistry(Registry $registry)
    {
        $this->gateway->saveRegistry($registry);

        return $this;
    }
}
