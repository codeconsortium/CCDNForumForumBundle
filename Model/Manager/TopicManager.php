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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Manager\BaseManagerInterface;
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
class TopicManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @param  \CCDNForum\ForumBundle\Entity\Post  $post
     * @return int
     */
    public function getPageForPostOnTopic(Topic $topic, Post $post)
    {
        $postsPerPage = $this->getPostsPerPageOnTopics();
        $page = 1;

        foreach ($topic->getPosts() as $index => $postTest) {
            if ($post->getId() == $postTest->getId()) {
                $page = ceil($index / $postsPerPage);
                break;
            }
        }

        return $page;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
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
     * Post must have a set topic for topic to be set  correctly.
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function postNewTopic(Post $post)
    {
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

        if ($topic->getBoard()) {
            // Update affected Board stats.
            $this->managerBag->getBoardManager()->updateStats($topic->getBoard())->flush();
        }

        // Subscribe the user to the topic.
        $this->managerBag->getSubscriptionManager()->subscribe($topic)->flush();

        $this->managerBag->getRegistryManager()->updateCachedPostCountForUser($post->getCreatedBy())->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateTopic(Topic $topic)
    {
        // update the record
        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateStats(Topic $topic)
    {
        $postManager = $this->managerBag->getPostManager();

        // Get stats.
        $topicPostCount = $this->getPostCountForTopicById($topic->getId());
        $topicFirstPost = $postManager->getFirstPostForTopicById($topic->getId());
        $topicLastPost = $postManager->getLastPostForTopicById($topic->getId());

        // Set the board / topic last post.
        $topic->setCachedReplyCount($topicPostCount['postCount'] > 0 ? --$topicPostCount['postCount'] : 0);
        $topic->setFirstPost($topicFirstPost ?: null);
        $topic->setLastPost($topicLastPost ?: null);

        $this->persist($topic)->flush();

        if ($topic->getBoard()) {
            // Update affected Board stats.
            $this->managerBag->getBoardManager()->updateStats($topic->getBoard())->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkUpdateStats($topics)
    {
        foreach ($topics as $topic) {
            $this->updateStats($topic);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function sticky(Topic $topic, UserInterface $user)
    {
        $topic->setIsSticky(true);
        $topic->setStickiedBy($user);
        $topic->setStickiedDate(new \DateTime());

        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsticky(Topic $topic)
    {
        $topic->setIsSticky(false);
        $topic->setStickiedBy(null);
        $topic->setStickiedDate(null);

        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param Topic $topic
     * @param $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function close(Topic $topic, UserInterface $user)
    {
        // Don't overwite previous users accountability.
        if (! $topic->getClosedBy() && ! $topic->getClosedDate()) {
            $topic->setIsClosed(true);
            $topic->setClosedBy($user);
            $topic->setClosedDate(new \DateTime());

            $this->persist($topic);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkClose($topics, UserInterface $user)
    {
        foreach ($topics as $topic) {
            // Don't overwite previous users accountability.
            if (! $topic->getClosedBy() && ! $topic->getClosedDate()) {
                $topic->setIsClosed(true);
                $topic->setClosedBy($user);
                $topic->setClosedDate(new \DateTime());

                $this->persist($topic);
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reopen(Topic $topic)
    {
        $topic->setIsClosed(false);
        $topic->setClosedBy(null);
        $topic->setClosedDate(null);

        if ($topic->getIsDeleted()) {
            $topic->setIsDeleted(false);
            $topic->setDeletedBy(null);
            $topic->setDeletedDate(null);
        }

        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkReopen($topics)
    {
        foreach ($topics as $topic) {
            $topic->setIsClosed(false);
            $topic->setClosedBy(null);
            $topic->setClosedDate(null);

            $this->persist($topic);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function restore(Topic $topic)
    {
        $topic->setIsDeleted(false);
        $topic->setDeletedBy(null);
        $topic->setDeletedDate(null);

        $this->persist($topic)->flush();

        // Update affected Topic stats.
        $this->updateStats($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkRestore($topics)
    {
        $boardsToUpdate = array();

        foreach ($topics as $topic) {
            // Add the board of the topic to be updated.
            if ($topic->getBoard()) {
                if (! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                    $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                }
            }

            // Remove deletion attributes.
            $topic->setIsDeleted(false);
            $topic->setDeletedBy(null);
            $topic->setDeletedDate(null);

            $this->persist($topic);
        }

        $this->flush();

        if (count($boardsToUpdate) > 0) {
            // Update all affected board stats.
            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @param $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
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

            // Update affected Topic stats.
            $this->updateStats($topic);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  array                                               $topics
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkSoftDelete($topics, UserInterface $user)
    {
        $boardsToUpdate = array();

        foreach ($topics as $topic) {
            // Don't overwite previous users accountability.
            if (! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
                // Add the board of the topic to be updated.
                if ($topic->getBoard()) {
                    if (! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                        $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                    }
                }

                // Set the deletion attributes.
                $topic->setIsDeleted(true);
                $topic->setDeletedBy($user);
                $topic->setDeletedDate(new \DateTime());

                $this->persist($topic);
            }
        }

        $this->flush();

        if (count($boardsToUpdate) > 0) {
            // Update all affected board stats.
            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function hardDelete(Topic $topic)
    {
        $usersPostCountToUpdate = array();

        // Add the board of the topic to be updated.
        if ($topic->getBoard()) {
            $boardToUpdate = $topic->getBoard();
        }

        // Add author of each post to chain of cached post counts to update.
        if (count($topic->getPosts()) > 0) {
            foreach ($topic->getPosts() as $postKey => $post) {
                if ($post->getCreatedBy()) {
                    $author = $post->getCreatedBy();

                    if (! array_key_exists($author->getId(), $usersPostCountToUpdate)) {
                        $usersPostCountToUpdate[$author->getId()] = $author;
                    }
                }
            }
        }

        $this->remove($topic);

        $this->flush();

        // Update all affected Board stats.
        if (is_object($boardToUpdate) && $boardToUpdate instanceof Board) {
            $this->managerBag->getBoardManager()->bulkUpdateStats(array($boardToUpdate))->flush();
        }

        // Update all affected Users cached post counts.
        if (count($usersPostCountToUpdate) > 0) {
            $this->managerBag->getRegistryManager()->bulkUpdateCachedPostCountForUsers($usersPostCountToUpdate)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkHardDelete($topics)
    {
        $boardsToUpdate = array();
        $usersPostCountToUpdate = array();

        // Remove topics.
        foreach ($topics as $topic) {
            // Add the board of the topic to be updated.
            if ($topic->getBoard()) {
                if (! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                    $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                }
            }

            // Add author of each post to chain of cached post counts to update.
            if (count($topic->getPosts()) > 0) {
                foreach ($topic->getPosts() as $postKey => $post) {
                    if ($post->getCreatedBy()) {
                        $author = $post->getCreatedBy();

                        if (! array_key_exists($author->getId(), $usersPostCountToUpdate)) {
                            $usersPostCountToUpdate[$author->getId()] = $author;
                        }
                    }
                }
            }

            $this->remove($topic);
        }

        $this->flush();

        // Update all affected Board stats.
        if (count($boardsToUpdate) > 0) {
            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
        }

        // Update all affected Users cached post counts.
        if (count($usersPostCountToUpdate) > 0) {
            $this->managerBag->getRegistryManager()->bulkUpdateCachedPostCountForUsers($usersPostCountToUpdate)->flush();
        }

        return $this;
    }
}
