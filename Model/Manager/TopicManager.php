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

namespace CCDNForum\ForumBundle\Model\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNForum\ForumBundle\Model\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

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
     * Post must have a set topic for topic to be set  correctly.
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post              $post
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function saveNewTopic(Post $post)
    {
        if (! $post->getTopic()) {
            throw new \Exception('Post must have a set topic to be saved.');
        }

        // insert a new row.
        $this->persist($post)->flush();

        // get the topic.
        $topic = $post->getTopic();

        // set topic last_post and first_post, board's last_post.
        $topic->setFirstPost($post);
        $topic->setLastPost($post);

        // persist and refresh after a flush to get topic id.
        $this->persist($topic)->flush();
        $this->refresh($topic);

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
        // update the record
        $this->persist($topic)->flush();

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
            $topic->setIsDeleted(true);
            $topic->setDeletedBy($user);
            $topic->setDeletedDate(new \DateTime());

            // Close the topic as a precaution.
            $topic->setIsClosed(true);
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
        $topic->setIsDeleted(false);
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
        $topic->setIsSticky(true);
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
        $topic->setIsSticky(false);
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
            $topic->setIsClosed(true);
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
        $topic->setIsClosed(false);
        $topic->setClosedBy(null);
        $topic->setClosedDate(null);

        if ($topic->isDeleted()) {
            $topic->setIsDeleted(false);
            $topic->setDeletedBy(null);
            $topic->setDeletedDate(null);
        }

        $this->persist($topic)->flush();

        return $this;
    }
}
