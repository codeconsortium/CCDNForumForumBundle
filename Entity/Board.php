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

use CCDNForum\ForumBundle\Model\Board as AbstractBoard;

/**
 * @ORM\Entity(repositoryClass="CCDNForum\ForumBundle\Repository\BoardRepository")
 */
class Board extends AbstractBoard
{
    /** @var integer $id */
    protected $id;

    /** @var string name */
    protected $name;

    /** @var string $description */
    protected $description;

    /** @var integer $cachedTopicCount */
    protected $cachedTopicCount = 0;

    /** @var integer $cachedPostCount */
    protected $cachedPostCount = 0;

    /** @var integer $listOrderPriority */
    protected $listOrderPriority = 0;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set list_order_priority
     *
     * @param integer $listOrderPriority
     */
    public function setListOrderPriority($listOrderPriority)
    {
        $this->listOrderPriority = $listOrderPriority;
    }

    /**
     * Get list_order_priority
     *
     * @return integer
     */
    public function getListOrderPriority()
    {
        return $this->listOrderPriority;
    }

    /**
     * Set cachedTopicCount
     *
     * @param integer $cachedTopicCount
     */
    public function setCachedTopicCount($cachedTopicCount)
    {
        $this->cachedTopicCount = $cachedTopicCount;
    }

    /**
     * Get cachedTopicCount
     *
     * @return integer
     */
    public function getCachedTopicCount()
    {
        return $this->cachedTopicCount;
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
}
