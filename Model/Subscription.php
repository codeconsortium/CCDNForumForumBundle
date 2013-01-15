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

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Topic;

abstract class Subscription
{
    /** @var Topic $topic */
    protected $topic = null;

    /** @var UserInterface $ownedBy */
    protected $ownedBy = null;

    public function __construct()
    {

    }

    /**
     * Get topic
     *
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set topic
     *
     * @param Topic $topic
     * @return Subscription
     */
    public function setTopic(Topic $topic = null)
    {
        $this->topic = $topic;

        return $this;
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
     * @param UserInterface $ownedBy
     * @return Subscription
     */
    public function setOwnedBy(UserInterface $ownedBy = null)
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }
}
