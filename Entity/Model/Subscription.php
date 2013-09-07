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

namespace CCDNForum\ForumBundle\Entity\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNForum\ForumBundle\Entity\Forum as ConcreteForum;
use CCDNForum\ForumBundle\Entity\Topic as ConcreteTopic;

abstract class Subscription
{
    /** @var Topic $topic */
    protected $forum = null;

    /** @var Topic $topic */
    protected $topic = null;

    /** @var UserInterface $ownedBy */
    protected $ownedBy = null;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
    }

    /**
     * Get topic
     *
     * @return Forum
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Set topic
     *
     * @param  Forum        $forum
     * @return Subscription
     */
    public function setForum(ConcreteForum $forum = null)
    {
        $this->forum = $forum;

        return $this;
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
     * @param  Topic        $topic
     * @return Subscription
     */
    public function setTopic(ConcreteTopic $topic = null)
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
     * @param  UserInterface $ownedBy
     * @return Subscription
     */
    public function setOwnedBy(UserInterface $ownedBy = null)
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }
}
