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

namespace CCDNForum\ForumBundle\Model\Component\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNForum\ForumBundle\Model\Component\Gateway\GatewayInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\BaseManager;
use CCDNForum\ForumBundle\Component\Helper\PostLockHelper;

use CCDNForum\ForumBundle\Entity\Topic;

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
class TopicManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Component\Helper\PostLockHelper $postLockHelper
     */
    protected $postLockHelper;

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Gateway\GatewayInterface        $gateway
     * @param \CCDNForum\ForumBundle\Component\Helper\PostLockHelper $postLockHelper
     */
    public function __construct(GatewayInterface $gateway, PostLockHelper $postLockHelper)
    {
        $this->gateway = $gateway;
        $this->postLockHelper = $postLockHelper;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Topic
     */
    public function createTopic()
    {
        return $this->gateway->createTopic();
    }

    /**
     *
     * Post must have a set topic for topic to be set  correctly.
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post              $post
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function saveTopic(Topic $topic)
    {
        $this->gateway->saveTopic($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic             $topic
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function updateTopic(Topic $topic)
    {
        $this->gateway->updateTopic($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic             $topic
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function incrementViewCounter(Topic $topic)
    {
        // set the new counters
        $topic->setCachedViewCount($topic->getCachedViewCount() + 1);

        $this->persist($topic)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function softDelete(Topic $topic, UserInterface $user)
    {
        // Don't overwite previous users accountability.
        if (! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
            $topic->setDeleted(true);
            $topic->setDeletedBy($user);
            $topic->setDeletedDate(new \DateTime());

            // Close the topic as a precaution.
            $topic->setClosed(true);
            $topic->setClosedBy($user);
            $topic->setClosedDate(new \DateTime());

            // update the record before doing record counts
            $this->persist($topic)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic             $topic
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function restore(Topic $topic)
    {
        $topic->setDeleted(false);
        $topic->setDeletedBy(null);
        $topic->setDeletedDate(null);

        $this->persist($topic)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic             $topic
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function sticky(Topic $topic, UserInterface $user)
    {
        $topic->setSticky(true);
        $topic->setStickiedBy($user);
        $topic->setStickiedDate(new \DateTime());

        $this->persist($topic)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic             $topic
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function unsticky(Topic $topic)
    {
        $topic->setSticky(false);
        $topic->setStickiedBy(null);
        $topic->setStickiedDate(null);

        $this->persist($topic)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function close(Topic $topic, UserInterface $user)
    {
        // Don't overwite previous users accountability.
        if (! $topic->getClosedBy() && ! $topic->getClosedDate()) {
            $topic->setClosed(true);
            $topic->setClosedBy($user);
            $topic->setClosedDate(new \DateTime());

            $this->persist($topic)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic             $topic
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function reopen(Topic $topic)
    {
        $topic->setClosed(false);
        $topic->setClosedBy(null);
        $topic->setClosedDate(null);

        if ($topic->isDeleted()) {
            $topic->setDeleted(false);
            $topic->setDeletedBy(null);
            $topic->setDeletedDate(null);
        }

        $this->persist($topic)->flush();

        return $this;
    }
}
