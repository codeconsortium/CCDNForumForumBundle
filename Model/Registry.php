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

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

abstract class Registry
{
    /** @var User $ownedBy */
    protected $ownedBy = null;

    public function __construct()
    {

    }

    /**
     * Get owned_by
     *
     * @return User
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }

    /**
     * Set owned_by
     *
     * @param  User $ownedBy
     * @return Registry
     */
    public function setOwnedBy(User $ownedBy = null)
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }
}
