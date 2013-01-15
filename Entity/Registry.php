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

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\Mapping as ORM;

use CCDNForum\ForumBundle\Model\Registry as AbstractRegistry;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\RegistryRepository")
 */
class Registry extends AbstractRegistry
{
    /** @var integer $id */
    protected $id;

    /** @var integer $cachedPostCount */
    protected $cachedPostCount = 0;

    /** @var integer $cachedKarmaPositiveCount */
    protected $cachedKarmaPositiveCount = 0;

    /** @var integer $cachedKarmaNegativeCount */
    protected $cachedKarmaNegativeCount = 0;

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
     * Set cachedPostCount
     *
     * @param integer $cachedPostCount
     */
    public function setCachedPostCount($cachedPostCount)
    {
        $this->cachedPostCount = $cachedPostCount;
    }

    /**
     * Get cachedPostCount
     *
     * @return integer
     */
    public function getCachedPostCount()
    {
        return $this->cachedPostCount;
    }

    /**
     * Set cachedKarmaPositiveCount
     *
     * @param integer $cachedKarmaPositiveCount
     */
    public function setCachedKarmaPositiveCount($cachedKarmaPositiveCount)
    {
        $this->cachedKarmaPositiveCount = $cachedKarmaPositiveCount;
    }

    /**
     * Get cachedKarmaPositiveCount
     *
     * @return integer
     */
    public function getCachedKarmaPositiveCount()
    {
        return $this->cachedKarmaPositiveCount;
    }

    /**
     * Set cachedKarmaNegativeCount
     *
     * @param integer $cachedKarmaNegativeCount
     */
    public function setCachedKarmaNegativeCount($cachedKarmaNegativeCount)
    {
        $this->cachedKarmaNegativeCount = $cachedKarmaNegativeCount;
    }

    /**
     * Get cachedKarmaNegativeCount
     *
     * @return integer
     */
    public function getCachedKarmaNegativeCount()
    {
        return $this->cachedKarmaNegativeCount;
    }
}
