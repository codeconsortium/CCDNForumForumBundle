<?php

/*
 * This file is part of the CCDN ForumBundle
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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\SubscriptionRepository")
 * @ORM\Table(name="CC_Forum_Subscription")
 */
class Subscription
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="Topic", cascade={"persist"})
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $topic;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="owned_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $owned_by;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $read_it;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $subscribed;
	
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
     * Set topic
     *
     * @param CCDNForum\ForumBundle\Entity\Topic $topic
     */
    public function setTopic(\CCDNForum\ForumBundle\Entity\Topic $topic)
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
    public function setOwnedBy(\CCDNUser\UserBundle\Entity\User $ownedBy)
    {
        $this->owned_by = $ownedBy;
    }

    /**
     * Get owned_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getOwnedBy()
    {
        return $this->owned_by;
    }

    /**
     * Set read_it
     *
     * @param boolean $readIt
     */
    public function setReadIt($readIt)
    {
        $this->read_it = $readIt;
    }

    /**
     * Get read_it
     *
     * @return boolean 
     */
    public function getReadIt()
    {
        return $this->read_it;
    }

    /**
     * Set subscribed
     *
     * @param boolean $subscribed
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;
    }

    /**
     * Get subscribed
     *
     * @return boolean 
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }
}