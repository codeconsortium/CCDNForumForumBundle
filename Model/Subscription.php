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

abstract class Subscription
{
    /** @var CCDNForum\ForumBundle\Entity\Topic $topic */
    protected $topic = null;

    /** @var CCDNUser\UserBundle\Entity\User $ownedBy */
    protected $ownedBy = null;

    public function __construct()
    {

    }

    /**
     * Set topic
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topic
     */
    public function setTopic(\CCDNForum\ForumBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;
    }

    /**
     * Get topic
     *
     * @return CCDNForum\ForumBundle\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set owned_by
     *
     * @param CCDNUser\UserBundle\Entity\User $ownedBy
     */
    public function setOwnedBy(\CCDNUser\UserBundle\Entity\User $ownedBy = null)
    {
        $this->ownedBy = $ownedBy;
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
