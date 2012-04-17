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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use CCDNUser\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\RegistryRepository")
 * @ORM\Table(name="CC_Forum_Registry")
 */
class Registry
{
	
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;

	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="owned_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $owned_by;
		
	/**
     * @ORM\Column(type="integer")
     */
    protected $cachePostCount;

	/**
     * @ORM\Column(type="integer")
     */
	protected $cacheKarmaPositiveCount;
	
	/**
     * @ORM\Column(type="integer")
     */
	protected $cacheKarmaNegativeCount;
	
	
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
     * Set cachePostCount
     *
     * @param integer $cachePostCount
     * @return Registry
     */
    public function setCachePostCount($cachePostCount)
    {
        $this->cachePostCount = $cachePostCount;
        return $this;
    }

    /**
     * Get cachePostCount
     *
     * @return integer 
     */
    public function getCachePostCount()
    {
        return $this->cachePostCount;
    }

    /**
     * Set owned_by
     *
     * @param CCDNUser\UserBundle\Entity\User $ownedBy
     * @return Registry
     */
    public function setOwnedBy(\CCDNUser\UserBundle\Entity\User $ownedBy = null)
    {
        $this->owned_by = $ownedBy;
        return $this;
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
     * Set cacheKarmaPositiveCount
     *
     * @param integer $cacheKarmaPositiveCount
     */
    public function setCacheKarmaPositiveCount($cacheKarmaPositiveCount)
    {
        $this->cacheKarmaPositiveCount = $cacheKarmaPositiveCount;
    }

    /**
     * Get cacheKarmaPositiveCount
     *
     * @return integer 
     */
    public function getCacheKarmaPositiveCount()
    {
        return $this->cacheKarmaPositiveCount;
    }

    /**
     * Set cacheKarmaNegativeCount
     *
     * @param integer $cacheKarmaNegativeCount
     */
    public function setCacheKarmaNegativeCount($cacheKarmaNegativeCount)
    {
        $this->cacheKarmaNegativeCount = $cacheKarmaNegativeCount;
    }

    /**
     * Get cacheKarmaNegativeCount
     *
     * @return integer 
     */
    public function getCacheKarmaNegativeCount()
    {
        return $this->cacheKarmaNegativeCount;
    }
}