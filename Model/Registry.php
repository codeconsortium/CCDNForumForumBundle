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

abstract class Registry
{
    /** @var User $ownedBy */
    protected $ownedBy = null;

    public function __construct()
    {

    }

    /**
     * Set owned_by
     *
     * @param  CCDNUser\UserBundle\Entity\User $ownedBy
     * @return Registry
     */
    public function setOwnedBy(\CCDNUser\UserBundle\Entity\User $ownedBy = null)
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }

    /**
     * Get owned_by
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }
}
