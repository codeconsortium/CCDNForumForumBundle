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

namespace CCDNForum\ForumBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param Post $post
     * @return self
     */
    public function create($post)
    {
        // insert a new row
        $this->persist($post)->flush();

        // refresh the user so that we have an PostId to work with.
        $this->refresh($post);

        // Update affected Topic stats.
        $this->managerBag->getTopicManager()->updateStats($post->getTopic());

		// Subscribe the user to the topic.
		//$this->container->get('ccdn_forum_forum.manager.subscription')->subscribe($post->getTopic()->getId(), $post->getCreatedBy());
		
        return $this;
    }

    /**
     *
     * @access public
     * @param Post $post
     * @return self
     */
    public function update($post)
    {
        // update a record
        $this->persist($post);

        return $this;
    }

    /**
     *
     * @access public
     * @param Post $post, $user
     * @return self
     */
    public function softDelete($post, $user)
    {
        // Don't overwite previous users accountability.
        if ( ! $post->getDeletedBy() && ! $post->getDeletedDate()) {
            $post->setIsDeleted(true);
            $post->setDeletedBy($user);
            $post->setDeletedDate(new \DateTime());

            // Lock the post as a precaution.
            $post->setIsLocked(true);
            $post->setLockedBy($user);
            $post->setLockedDate(new \DateTime());

            // update the record
            $this->persist($post)->flush();

            if ($post->getTopic()) {
                $topic = $post->getTopic();

                // if this is the first post and only post, then soft delete the topic aswell.
                if ($topic->getCachedReplyCount() < 1) {
                    // Don't overwite previous users accountability.
                    if ( ! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
                        $topic->setIsDeleted(true);
                        $topic->setDeletedBy($user);
                        $topic->setDeletedDate(new \DateTime());

                        // Close the topic as a precaution.
                        $topic->setIsClosed(true);
                        $topic->setClosedBy($user);
                        $topic->setClosedDate(new \DateTime());

                        $this->persist($topic)->flush();

                        // Update affected Topic stats.
                        $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
                    }
                }
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param Post $post
     * @return self
     */
    public function restore($post)
    {
        $post->setIsDeleted(false);
        $post->setDeletedBy(null);
        $post->setDeletedDate(null);

        // update the record
        $this->persist($post)->flush();

        if ($post->getTopic()) {
            $topic = $post->getTopic();

            // if this is the first post and only post,
            // then restore the topic aswell.
            if ($topic->getCachedReplyCount() < 1) {
                $topic->setIsDeleted(false);
                $topic->setDeletedBy(null);
                $topic->setDeletedDate(null);

                $this->persist($topic)->flush();

                // Update affected Topic stats.
                $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param Post $post, $user
     * @return self
     */
    public function lock($post, $user)
    {
        // Don't overwite previous users accountability.
        if ( ! $post->getLockedBy() && ! $post->getLockedDate()) {
            $post->setIsLocked(true);
            $post->setLockedBy($user);
            $post->setLockedDate(new \DateTime());

            $this->persist($post);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param Post $post
     * @return self
     */
    public function unlock($post)
    {
        $post->setIsLocked(false);
        $post->setLockedBy(null);
        $post->setLockedDate(null);

        $this->persist($post);

        return $this;
    }
}