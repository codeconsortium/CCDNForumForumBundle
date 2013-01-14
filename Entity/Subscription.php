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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\SubscriptionRepository")
 */
class Subscription
{
    /** @var integer $id */
    protected $id;

    /** @var Boolean $isRead */
    protected $isRead = false;

    /** @var Boolean $isSubscribed */
    protected $isSubscribed = false;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set isSubscribed
     *
     * @param boolean $isSubscribed
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->isSubscribed = $isSubscribed;
    }

    /**
     * Get isSubscribed
     *
     * @return boolean
     */
    public function getIsSubscribed()
    {
        return $this->isSubscribed;
    }
}
