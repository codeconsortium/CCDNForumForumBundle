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
     * @return bool
     */
    public function allowedToViewDeletedTopics()
    {
        return $this->managerBag->getPolicyManager()->allowedToViewDeletedTopics();
    }

    /**
     *
     * @access public
     * @param  int                                $topicId
     * @return \CCDNForum\ForumBundle\Entity\Post
     */
    public function getFirstPostForTopicById($topicId)
    {
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        $params = array(':topicId' => $topicId);

        $qb = $this->createSelectQuery(array('p'));

        $qb
            ->where('p.topic = :topicId')
            ->orderBy('p.createdDate', 'ASC')
            ->setMaxResults(1);

        return $this->gateway->findPost($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int                                $topicId
     * @return \CCDNForum\ForumBundle\Entity\Post
     */
    public function getLastPostForTopicById($topicId)
    {
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        $params = array(':topicId' => $topicId);

        $qb = $this->createSelectQuery(array('p'));

        $qb
            ->where('p.topic = :topicId')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(1);

        return $this->gateway->findPost($qb, $params);
    }

//    /**
//     *
//     * @access public
//     * @param  int                                $postId
//     * @return \CCDNForum\ForumBundle\Entity\Post
//     */
//    public function findOnePostByIdWithTopicAndBoardhTopicAndBoard($postId)
//    {
//        if (null == $postId || ! is_numeric($postId) || $postId == 0) {
//            throw new \Exception('Post id "' . $postId . '" is invalid!');
//        }
//
//        $canViewDeleted = $this->allowedToViewDeletedTopics();
//
//        $params = array(':postId' => $postId);
//
//        $qb = $this->createSelectQuery(array('p', 't', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 'p_createdBy', 'p_editedBy', 'p_deletedBy'));
//
//        $qb
//            ->join('p.topic', 't')
//                ->leftJoin('t.firstPost', 'fp')
//                    ->leftJoin('fp.createdBy', 'fp_author')
//                ->leftJoin('t.lastPost', 'lp')
//                    ->leftJoin('lp.createdBy', 'lp_author')
//            ->leftJoin('p.createdBy', 'p_createdBy')
//            ->leftJoin('p.editedBy', 'p_editedBy')
//            ->leftJoin('p.deletedBy', 'p_deletedBy')
//            ->leftJoin('t.board', 'b')
//            ->leftJoin('b.category', 'c')
//            ->where(
//                $this->limitQueryByTopicsDeletedStateAndByPostId($qb, $canViewDeleted)
//            );
//
//        return $this->gateway->findPost($qb, $params);
//    }

    /**
     *
     * @access protected
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @param  bool                       $canViewDeletedTopics
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function limitQueryByTopicsDeletedStateAndByPostId(QueryBuilder $qb, $canViewDeletedTopics)
    {
        if ($canViewDeletedTopics) {
            $expr = $qb->expr()->eq('p.id', ':postId');
        } else {
            $expr = $qb->expr()->andX(
                $qb->expr()->eq('p.id', ':postId'),
                $qb->expr()->eq('t.isDeleted', 'FALSE')
            );
        }

        return $expr;
    }

    /**
     *
     * @access public
     * @param  int                    $topicId
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findAllPaginatedByTopicId($topicId, $page)
    {
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        $params = array(':topicId' => $topicId);

        $qb = $this->createSelectQuery(array('p', 't', 'b', 'fp', 'fp_author', 'lp', 'lp_author', 'p_createdBy', 'p_editedBy', 'p_deletedBy'));

        $qb
            ->join('p.topic', 't')
                ->leftJoin('t.firstPost', 'fp')
                    ->leftJoin('fp.createdBy', 'fp_author')
                ->leftJoin('t.lastPost', 'lp')
                    ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('p.createdBy', 'p_createdBy')
            ->leftJoin('p.editedBy', 'p_editedBy')
            ->leftJoin('p.deletedBy', 'p_deletedBy')
            ->leftJoin('t.board', 'b')
            ->where('p.topic = :topicId')
            ->setParameters($params)
            ->orderBy('p.createdDate', 'ASC');

        return $this->gateway->paginateQuery($qb, $this->getPostsPerPageOnTopics(), $page);
    }

    /**
     *
     * @access public
     * @param  Array                                        $postIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findThesePostsById($postIds = array())
    {
        if (! is_array($postIds) || count($postIds) < 1) {
            throw new \Exception('Parameter 1 must be an array and contain at least 1 post id!');
        }

        $qb = $this->createSelectQuery(array('p'));

        $qb
            ->where($qb->expr()->in('p.id', $postIds))
            ->orderBy('p.createdDate', 'ASC')
        ;

        return $this->gateway->findPosts($qb);
    }

    /**
     *
     * @access public
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findLockedPostsForModeratorsPaginated($page)
    {
        $params = array(':isLocked' => true);

        $qb = $this->createSelectQuery(array('p', 't', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 'p_createdBy', 'p_editedBy', 'p_deletedBy'));

        $qb
            ->join('p.topic', 't')
                ->leftJoin('t.firstPost', 'fp')
                    ->leftJoin('fp.createdBy', 'fp_author')
                ->leftJoin('t.lastPost', 'lp')
                    ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('p.createdBy', 'p_createdBy')
            ->leftJoin('p.editedBy', 'p_editedBy')
            ->leftJoin('p.deletedBy', 'p_deletedBy')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->where('p.isLocked = :isLocked')
            ->setParameters($params)
            ->orderBy('p.createdDate', 'ASC');

        return $this->gateway->paginateQuery($qb, $this->getPostsPerPageOnTopics(), $page);
    }

    /**
     *
     * @access public
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findDeletedPostsForAdminsPaginated($page)
    {
        $params = array(':isDeleted' => true);

        $qb = $this->createSelectQuery(array('p', 't', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 'p_createdBy', 'p_editedBy', 'p_deletedBy'));

        $qb
            ->join('p.topic', 't')
                ->leftJoin('t.firstPost', 'fp')
                    ->leftJoin('fp.createdBy', 'fp_author')
                ->leftJoin('t.lastPost', 'lp')
                    ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('p.createdBy', 'p_createdBy')
            ->leftJoin('p.editedBy', 'p_editedBy')
            ->leftJoin('p.deletedBy', 'p_deletedBy')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->where('p.isDeleted = :isDeleted')
            ->setParameters($params)
            ->orderBy('p.createdDate', 'ASC');

        return $this->gateway->paginateQuery($qb, $this->getPostsPerPageOnTopics(), $page);
    }

    /**
     *
     * @access public
     * @param  int   $userId
     * @return Array
     */
    public function getPostCountForUserById($userId)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $qb = $this->getQueryBuilder();

        $topicEntityClass = $this->gateway->getEntityClass();

        $qb
            ->select('COUNT(DISTINCT p.id) AS postCount')
            ->from($topicEntityClass, 'p')
            ->where('p.createdBy = :userId')
            ->setParameter(':userId', $userId);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return array('postCount' => null);
        } catch (\Exception $e) {
            return array('postCount' => null);
        }
    }




    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function lock(Post $post, UserInterface $user)
    {
        // Don't overwite previous users accountability.
        if (! $post->getLockedBy() && ! $post->getLockedDate()) {
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
     * @param  Array                                               $posts
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkLock($posts, UserInterface $user)
    {
        foreach ($posts as $post) {
            // Don't overwite previous users accountability.
            if (! $post->getLockedBy() && ! $post->getLockedDate()) {
                $post->setIsLocked(true);
                $post->setLockedBy($user);
                $post->setLockedDate(new \DateTime());
            }

            $this->persist($post);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unlock(Post $post)
    {
        $post->setIsLocked(false);
        $post->setLockedBy(null);
        $post->setLockedDate(null);

        $this->persist($post);

        return $this;
    }

    /**
     *
     * @access public
     * @param  Array                                               $posts
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkUnlock($posts)
    {
        foreach ($posts as $post) {
            $post->setIsLocked(false);
            $post->setLockedBy(null);
            $post->setLockedDate(null);

            $this->persist($post);
        }

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
                $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
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
                    if (! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
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
     * @param  Array                                               $posts
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkRestore($posts)
    {
        $boardsToUpdate = array();

        foreach ($posts as $post) {
            // Add the board of the topic to be updated.
            if ($post->getTopic()) {
                $topic = $post->getTopic();

                if ($topic->getBoard()) {
                    if (! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                        $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                    }
                }

                if ($topic->getCachedReplyCount() < 1 && $topic->getFirstPost()->getId() == $post->getId()) {
                    $topic->setIsDeleted(false);
                    $topic->setDeletedBy(null);
                    $topic->setDeletedDate(null);

                    $this->persist($topic);
                }
            }

            $post->setIsDeleted(false);
            $post->setDeletedBy(null);
            $post->setDeletedDate(null);

            $this->persist($post);
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
     * @param  Array                                               $posts
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkSoftDelete($posts, UserInterface $user)
    {
        $boardsToUpdate = array();

        foreach ($posts as $post) {
            // Don't overwite previous users accountability.
            if (! $post->getDeletedBy() && ! $post->getDeletedDate()) {
                // Add the board of the topic to be updated.
                if ($post->getTopic()) {
                    $topic = $post->getTopic();

                    if ($topic->getBoard()) {
                        if ( ! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                            $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                        }
                    }

                    if ($topic->getCachedReplyCount() < 1 && $topic->getFirstPost()->getId() == $post->getId()) {
                        $topic->setIsDeleted(true);
                        $topic->setDeletedBy($user);
                        $topic->setDeletedDate(new \DateTime());

                        $this->persist($topic);
                    }
                }

                $post->setIsDeleted(true);
                $post->setDeletedBy($user);
                $post->setDeletedDate(new \DateTime());

                $this->persist($post);
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
     * @param  Array                                               $posts
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkHardDelete($posts)
    {
        $postsToDelete = array();
        $topicsToDelete = array();
        $topicsToUpdate = array();
        $boardsToUpdate = array();
        $usersPostCountToUpdate = array();

        foreach ($posts as $post) {
            if ($post->getTopic()) {
                $topic = $post->getTopic();

                //
                // If post is the topics last post unlink it.
                //
                if ($topic->getLastPost()) {
                    if ($topic->getLastPost()->getId() == $post->getId()) {
                        $topic->setLastPost(null);

                        // Add the topic to the topics to be updated list.
                        if (! array_key_exists($topic->getId(), $topicsToUpdate)) {
                            $topicsToUpdate[$topic->getId()] = $topic;
                        }

                        // If post is topics last, it is likely linked as
                        // last on the board too if it is the last topic.
                        if ($topic->getBoard()) {
                            $board = $topic->getBoard();

                            if ($board->getLastPost()->getId() == $post->getId()) {
                                // Add the board of the topic to be updated.
                                if ( ! array_key_exists($board->getId(), $boardsToUpdate)) {
                                    $boardsToUpdate[$board->getId()] = $board;
                                }

                                $board->setLastPost(null);

                                $this->persist($board);
                            }
                        }
                    }
                }

                //
                // If post is the topics first post unlink it.
                //
                if ($topic->getFirstPost()) {
                    if ($topic->getFirstPost()->getId() == $post->getId()) {
                        $topic->setFirstPost(null);

                        // We will hard delete the topic too
                        // if it is the only post in the topic.
                        if ($topic->getCachedReplyCount() < 1) {
                            if (! array_key_exists($topic->getId(), $topicsToDelete)) {
                                $topicsToDelete[$topic->getId()] = $topic;

                                if (array_key_exists($topic->getId(), $topicsToUpdate)) {
                                    unset($topicsToUpdate[$topic->getId()]);
                                }
                            }
                        } else {
                            // Add the topic to the topics to be updated list.
                            if (! array_key_exists($topic->getId(), $topicsToUpdate)) {
                                $topicsToUpdate[$topic->getId()] = $topic;
                            }
                        }
                    }
                }

                // Finally unlink the post from the topic.
                $post->setTopic(null);

                // Flush all the changes to the topic as we go.
                $this->persist($topic)->flush();
            }

            // Add post to the delete chain
            if (! array_key_exists($post->getId(), $postsToDelete)) {
                $postsToDelete[$post->getId()] = $post;
            }

            // Add author to chain of cached post counts to update.
            if ($post->getCreatedBy()) {
                $author = $post->getCreatedBy();

                if (! array_key_exists($author->getId(), $usersPostCountToUpdate)) {
                    $usersPostCountToUpdate[$author->getId()] = $author;
                }
            }
        }

        // Flush all the unlinking.
        $this->flush();

        // Drop the post records from the db.
        foreach ($postsToDelete as $post) {
            $this->refresh($post);

            if ($post) {
                $this->remove($post);
            }
        }

        $this->flush();

        // Drop the topic records from the db.
        foreach ($topicsToDelete as $topic) {
            $this->refresh($topic);

            if ($topic) {
                $this->remove($topic);
            }
        }

        $this->flush();

        // Update all affected Board stats.
        $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();

        // Update all affected Topic stats.
        $this->managerBag->getTopicManager()->bulkUpdateStats($topicsToUpdate)->flush();

        return $this;
    }
}
