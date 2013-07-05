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

namespace CCDNForum\ForumBundle\Entity\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Registry
{
    /** @var UserInterface $ownedBy */
    protected $ownedBy = null;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
    }

    /**
     * Get owned_by
     *
     * @return UserInterface
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }

    /**
     * Set owned_by
     *
     * @param  UserInterface $ownedBy
     * @return Registry
     */
    public function setOwnedBy(UserInterface $ownedBy = null)
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }
}
