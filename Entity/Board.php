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

use CCDNForum\ForumBundle\Entity\Model\Board as AbstractBoard;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class Board extends AbstractBoard
{
    /**
     *
     * @var integer $id
     */
    protected $id;

    /**
     *
     * @var string name
     */
    protected $name;

    /**
     *
     * @var string $description\
     */
    protected $description;

    /**
     *
     * @var integer $cachedTopicCount
     */
    protected $cachedTopicCount = 0;

    /**
     *
     * @var integer $cachedPostCount
     */
    protected $cachedPostCount = 0;

    /**
     *
     * @var integer $listOrderPriority
     */
    protected $listOrderPriority = 0;

    /**
     *
     * @var array $readAuthorisedRoles
     */
    protected $readAuthorisedRoles;

    /**
     *
     * @var array $topicCreateAuthorisedRoles
     */
    protected $topicCreateAuthorisedRoles;

    /**
     *
     * @var array $topicReplyAuthorisedRoles
     */
    protected $topicReplyAuthorisedRoles;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        // your own logic
        $this->readAuthorisedRoles = array();
        $this->topicCreateAuthorisedRoles = array();
        $this->topicReplyAuthorisedRoles = array();
    }

    public function __toString()
    {
        return $this->name;
    }

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Board
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Board
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     * Set list_order_priority
     *
     * @param  integer $listOrderPriority
     * @return Board
     */
    public function setListOrderPriority($listOrderPriority)
    {
        $this->listOrderPriority = $listOrderPriority;

        return $this;
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
     * Set cachedTopicCount
     *
     * @param  integer $cachedTopicCount
     * @return Board
     */
    public function setCachedTopicCount($cachedTopicCount)
    {
        $this->cachedTopicCount = $cachedTopicCount;

        return $this;
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
     * Set cachedPostCount
     *
     * @param  integer $cachedPostCount
     * @return Board
     */
    public function setCachedPostCount($cachedPostCount)
    {
        $this->cachedPostCount = $cachedPostCount;

        return $this;
    }

    /**
     * @return array
     */
    public function getReadAuthorisedRoles()
    {
        return $this->readAuthorisedRoles;
    }

    /**
     * @param  array $roles
     * @return Board
     */
    public function setReadAuthorisedRoles(array $roles = null)
    {
        $this->readAuthorisedRoles = $roles;

        return $this;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasReadAuthorisedRole($role)
    {
        return in_array($role, $this->readAuthorisedRoles);
    }

    /**
     * @param SecurityContextInterface $securityContext
     *
     * @return bool
     */
    public function isAuthorisedToRead(SecurityContextInterface $securityContext)
    {
        if (0 == count($this->readAuthorisedRoles)) {
            return true;
        }

        foreach ($this->readAuthorisedRoles as $role) {
            if ($securityContext->isGranted($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getTopicCreateAuthorisedRoles()
    {
        return $this->topicCreateAuthorisedRoles;
    }

    /**
     * @param array $roles
     *
     * @return Board
     */
    public function setTopicCreateAuthorisedRoles(array $roles = null)
    {
        $this->topicCreateAuthorisedRoles = $roles;

        return $this;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasTopicCreateAuthorisedRole($role)
    {
        return in_array($role, $this->topicCreateAuthorisedRoles);
    }

    /**
     * @param SecurityContextInterface $securityContext
     *
     * @return bool
     */
    public function isAuthorisedToCreateTopic(SecurityContextInterface $securityContext)
    {
        if (0 == count($this->topicCreateAuthorisedRoles)) {
            return true;
        }

        foreach ($this->topicCreateAuthorisedRoles as $role) {
            if ($securityContext->isGranted($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getTopicReplyAuthorisedRoles()
    {
        return $this->topicReplyAuthorisedRoles;
    }

    /**
     * @param array $roles
     *
     * @return Board
     */
    public function setTopicReplyAuthorisedRoles(array $roles = null)
    {
        $this->topicReplyAuthorisedRoles = $roles;

        return $this;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasTopicReplyAuthorisedRole($role)
    {
        return in_array($role, $this->topicReplyAuthorisedRoles);
    }

    /**
     * @param SecurityContextInterface $securityContext
     *
     * @return bool
     */
    public function isAuthorisedToReplyToTopic(SecurityContextInterface $securityContext)
    {
        if (0 == count($this->topicReplyAuthorisedRoles)) {
            return true;
        }

        foreach ($this->topicReplyAuthorisedRoles as $role) {
            if ($securityContext->isGranted($role)) {
                return true;
            }
        }

        return false;
    }
}
