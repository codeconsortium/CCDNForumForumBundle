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
class PostManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function postTopicReply(Post $post)
    {
        // insert a new row
        $this->persist($post)->flush();

        // refresh the user so that we have an PostId to work with.
        $this->refresh($post);

        // Update affected Topic stats.
        //$this->managerBag->getTopicManager()->updateStats($post->getTopic());

        // Subscribe the user to the topic.
        //$this->managerBag->getSubscriptionManager()->subscribe($post->getTopic())->flush();

        //$this->managerBag->getRegistryManager()->updateCachedPostCountForUser($post->getCreatedBy())->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updatePost(Post $post)
    {
        // update a record
        $this->persist($post)->flush();

		$this->refresh($post);
		
        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function lock(Post $post)
    {
		$post->setUnlockedUntilDate(new \Datetime('now'));

        $this->persist($post)->flush();

		$this->refresh($post);
		
        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function restore(Post $post)
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
//                $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function softDelete(Post $post, UserInterface $user)
    {
        // Don't overwite previous users accountability.
        if (! $post->getDeletedBy() && ! $post->getDeletedDate()) {
            $post->setIsDeleted(true);
            $post->setDeletedBy($user);
            $post->setDeletedDate(new \DateTime());

            // Lock the post as a precaution.
            //$post->setIsLocked(true);
            //$post->setLockedBy($user);
            //$post->setLockedDate(new \DateTime());

            // update the record
            $this->persist($post)->flush();

// Temporarily commented out because cachedReplyCount is not being used as of yet, so topics get erroniously deleted when deleting post.
//            if ($post->getTopic()) {
//                $topic = $post->getTopic();
//
//                // if this is the first post and only post, then soft delete the topic aswell.
//                if ($topic->getCachedReplyCount() < 1) {
//                    // Don't overwite previous users accountability.
//                    if (! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
//                        $topic->setIsDeleted(true);
//                        $topic->setDeletedBy($user);
//                        $topic->setDeletedDate(new \DateTime());
//
//                        // Close the topic as a precaution.
//                        $topic->setIsClosed(true);
//                        $topic->setClosedBy($user);
//                        $topic->setClosedDate(new \DateTime());
//
//                        $this->persist($topic)->flush();
//
//                        // Update affected Topic stats.
////                        $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
//                    }
//                }
//            }
        }

        return $this;
    }

//
//    /**
//     *
//     * @access public
//     * @param  Array                                               $posts
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkRestore($posts)
//    {
//        $boardsToUpdate = array();
//
//        foreach ($posts as $post) {
//            // Add the board of the topic to be updated.
//            if ($post->getTopic()) {
//                $topic = $post->getTopic();
//
//                if ($topic->getBoard()) {
//                    if (! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
//                        $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
//                    }
//                }
//
//                if ($topic->getCachedReplyCount() < 1 && $topic->getFirstPost()->getId() == $post->getId()) {
//                    $topic->setIsDeleted(false);
//                    $topic->setDeletedBy(null);
//                    $topic->setDeletedDate(null);
//
//                    $this->persist($topic);
//                }
//            }
//
//            $post->setIsDeleted(false);
//            $post->setDeletedBy(null);
//            $post->setDeletedDate(null);
//
//            $this->persist($post);
//        }
//
//        $this->flush();
//
//        if (count($boardsToUpdate) > 0) {
//            // Update all affected board stats.
//            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
//        }
//
//        return $this;
//    }
//
//    /**
//     *
//     * @access public
//     * @param  Array                                               $posts
//     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkSoftDelete($posts, UserInterface $user)
//    {
//        $boardsToUpdate = array();
//
//        foreach ($posts as $post) {
//            // Don't overwite previous users accountability.
//            if (! $post->getDeletedBy() && ! $post->getDeletedDate()) {
//                // Add the board of the topic to be updated.
//                if ($post->getTopic()) {
//                    $topic = $post->getTopic();
//
//                    if ($topic->getBoard()) {
//                        if ( ! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
//                            $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
//                        }
//                    }
//
//                    if ($topic->getCachedReplyCount() < 1 && $topic->getFirstPost()->getId() == $post->getId()) {
//                        $topic->setIsDeleted(true);
//                        $topic->setDeletedBy($user);
//                        $topic->setDeletedDate(new \DateTime());
//
//                        $this->persist($topic);
//                    }
//                }
//
//                $post->setIsDeleted(true);
//                $post->setDeletedBy($user);
//                $post->setDeletedDate(new \DateTime());
//
//                $this->persist($post);
//            }
//        }
//
//        $this->flush();
//
//        if (count($boardsToUpdate) > 0) {
//            // Update all affected board stats.
//            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
//        }
//
//        return $this;
//    }
//
//    /**
//     *
//     * @access public
//     * @param  Array                                               $posts
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkHardDelete($posts)
//    {
//        $postsToDelete = array();
//        $topicsToDelete = array();
//        $topicsToUpdate = array();
//        $boardsToUpdate = array();
//        $usersPostCountToUpdate = array();
//
//        foreach ($posts as $post) {
//            if ($post->getTopic()) {
//                $topic = $post->getTopic();
//
//                //
//                // If post is the topics last post unlink it.
//                //
//                if ($topic->getLastPost()) {
//                    if ($topic->getLastPost()->getId() == $post->getId()) {
//                        $topic->setLastPost(null);
//
//                        // Add the topic to the topics to be updated list.
//                        if (! array_key_exists($topic->getId(), $topicsToUpdate)) {
//                            $topicsToUpdate[$topic->getId()] = $topic;
//                        }
//
//                        // If post is topics last, it is likely linked as
//                        // last on the board too if it is the last topic.
//                        if ($topic->getBoard()) {
//                            $board = $topic->getBoard();
//
//                            if ($board->getLastPost()->getId() == $post->getId()) {
//                                // Add the board of the topic to be updated.
//                                if ( ! array_key_exists($board->getId(), $boardsToUpdate)) {
//                                    $boardsToUpdate[$board->getId()] = $board;
//                                }
//
//                                $board->setLastPost(null);
//
//                                $this->persist($board);
//                            }
//                        }
//                    }
//                }
//
//                //
//                // If post is the topics first post unlink it.
//                //
//                if ($topic->getFirstPost()) {
//                    if ($topic->getFirstPost()->getId() == $post->getId()) {
//                        $topic->setFirstPost(null);
//
//                        // We will hard delete the topic too
//                        // if it is the only post in the topic.
//                        if ($topic->getCachedReplyCount() < 1) {
//                            if (! array_key_exists($topic->getId(), $topicsToDelete)) {
//                                $topicsToDelete[$topic->getId()] = $topic;
//
//                                if (array_key_exists($topic->getId(), $topicsToUpdate)) {
//                                    unset($topicsToUpdate[$topic->getId()]);
//                                }
//                            }
//                        } else {
//                            // Add the topic to the topics to be updated list.
//                            if (! array_key_exists($topic->getId(), $topicsToUpdate)) {
//                                $topicsToUpdate[$topic->getId()] = $topic;
//                            }
//                        }
//                    }
//                }
//
//                // Finally unlink the post from the topic.
//                $post->setTopic(null);
//
//                // Flush all the changes to the topic as we go.
//                $this->persist($topic)->flush();
//            }
//
//            // Add post to the delete chain
//            if (! array_key_exists($post->getId(), $postsToDelete)) {
//                $postsToDelete[$post->getId()] = $post;
//            }
//
//            // Add author to chain of cached post counts to update.
//            if ($post->getCreatedBy()) {
//                $author = $post->getCreatedBy();
//
//                if (! array_key_exists($author->getId(), $usersPostCountToUpdate)) {
//                    $usersPostCountToUpdate[$author->getId()] = $author;
//                }
//            }
//        }
//
//        // Flush all the unlinking.
//        $this->flush();
//
//        // Drop the post records from the db.
//        foreach ($postsToDelete as $post) {
//            $this->refresh($post);
//
//            if ($post) {
//                $this->remove($post);
//            }
//        }
//
//        $this->flush();
//
//        // Drop the topic records from the db.
//        foreach ($topicsToDelete as $topic) {
//            $this->refresh($topic);
//
//            if ($topic) {
//                $this->remove($topic);
//            }
//        }
//
//        $this->flush();
//
//        // Update all affected Board stats.
//        $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
//
//        // Update all affected Topic stats.
//        $this->managerBag->getTopicManager()->bulkUpdateStats($topicsToUpdate)->flush();
//
//        return $this;
//    }
}
