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

namespace CCDNForum\ForumBundle\Model\Component\Repository;

use CCDNForum\ForumBundle\Model\Component\Repository\Repository;
use CCDNForum\ForumBundle\Model\Component\Repository\RepositoryInterface;

/**
 * SubscriptionRepository
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 */
class SubscriptionRepository extends BaseRepository implements RepositoryInterface
{
    /**
     *
     * @access public
     * @param  int                                         $userId
     * @param  bool                                        $canViewDeletedTopics
     * @return \Doctrine\Common\Collection\ArrayCollection
     */
    public function findAllSubscriptionsForUserById($userId, $canViewDeletedTopics = false)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $params = array(':userId' => $userId);

        $qb = $this->createSelectQuery(array('s', 't', 'b', 'c', 'f', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));

        $qb
            ->innerJoin('s.topic', 't')
            ->innerJoin('s.forum', 'f')
            ->innerJoin('t.firstPost', 'fp')
                ->leftJoin('fp.createdBy', 'fp_author')
            ->innerJoin('t.lastPost', 'lp')
                ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('t.closedBy', 't_closedBy')
            ->leftJoin('t.deletedBy', 't_deletedBy')
            ->leftJoin('t.stickiedBy', 't_stickiedBy')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->where(
                call_user_func_array(function ($canViewDeletedTopics, $qb) {
                    if ($canViewDeletedTopics) {
                        $expr = $qb->expr()->eq('s.ownedBy', ':userId');
                    } else {
                        $expr = $qb->expr()->andX(
                            $qb->expr()->eq('s.ownedBy', ':userId'),
                            $qb->expr()->eq('t.isDeleted', 'FALSE')
                        );
                    }

                    return $expr;
                }, array($canViewDeletedTopics, $qb))
            )
            ->setParameters($params)
            ->orderBy('lp.createdDate', 'DESC')
        ;

        return $this->gateway->findSubscriptions($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int                                         $topicId
     * @return \Doctrine\Common\Collection\ArrayCollection
     */
    public function findAllSubscriptionsForTopicById($topicId)
    {
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        $params = array(':topicId' => $topicId);

        $qb = $this->createSelectQuery(array('s', 't', 'b', 'c', 'f', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));

        $qb
            ->innerJoin('s.topic', 't')
            ->innerJoin('s.forum', 'f')
            ->innerJoin('t.firstPost', 'fp')
                ->leftJoin('fp.createdBy', 'fp_author')
            ->innerJoin('t.lastPost', 'lp')
                ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('t.closedBy', 't_closedBy')
            ->leftJoin('t.deletedBy', 't_deletedBy')
            ->leftJoin('t.stickiedBy', 't_stickiedBy')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('s.topic', ':topicId'),
                    $qb->expr()->eq('t.isDeleted', 'FALSE')
                )
            )
            ->setParameters($params)
            ->orderBy('lp.createdDate', 'DESC')
        ;

        return $this->gateway->findSubscriptions($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int                                                      $forumId
     * @param  int                                                      $userId
     * @param  int                                                      $page
     * @param  int                                                      $itemsPerPage
     * @param  string                                                   $filter
     * @param  bool                                                     $canViewDeletedTopics
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function findAllSubscriptionsPaginatedForUserById($userId, $page, $itemsPerPage = 25, $filter, $canViewDeletedTopics = false)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $params = array(':userId' => $userId);

        $qb = $this->createSelectQuery(array('s', 't', 'b', 'c', 'f', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));

        $qb
            ->innerJoin('s.topic', 't')
            ->innerJoin('t.firstPost', 'fp')
                ->leftJoin('fp.createdBy', 'fp_author')
            ->leftJoin('t.lastPost', 'lp')
                ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('t.closedBy', 't_closedBy')
            ->leftJoin('t.deletedBy', 't_deletedBy')
            ->leftJoin('t.stickiedBy', 't_stickiedBy')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->leftJoin('c.forum', 'f')
            ->where(
                call_user_func_array(function ($qb, $canViewDeletedTopics, $filter) {
                    $expr = $qb->expr()->andX(
                        $qb->expr()->eq('s.ownedBy', ':userId'),
                        $qb->expr()->eq('s.isSubscribed', 'TRUE')
                    );

                    if (! $canViewDeletedTopics) {
                        $expr = $qb->expr()->andX(
                            $expr,
                            $qb->expr()->eq('t.isDeleted', 'FALSE')
                        );
                    }

                    if ($filter) {
                        if ($filter == 'read') {
                            $expr = $qb->expr()->andX(
                                $expr,
                                $qb->expr()->eq('s.isRead', 'TRUE')
                            );
                        } else {
                            if ($filter == 'unread') {
                                $expr = $qb->expr()->andX(
                                    $expr,
                                    $qb->expr()->eq('s.isRead', 'FALSE')
                                );
                            }
                        }
                    }

                    return $expr;
                }, array($qb, $canViewDeletedTopics, $filter))
            )
            ->setParameters($params)
            ->orderBy('lp.createdDate', 'DESC')
        ;

        return $this->gateway->paginateQuery($qb, $itemsPerPage, $page);
    }

    /**
     *
     * @access public
     * @param  int                                                      $forumId
     * @param  int                                                      $userId
     * @param  int                                                      $page
     * @param  int                                                      $itemsPerPage
     * @param  string                                                   $filter
     * @param  bool                                                     $canViewDeletedTopics
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function findAllSubscriptionsPaginatedForUserByIdAndForumById($forumId, $userId, $page, $itemsPerPage = 25, $filter = 'unread', $canViewDeletedTopics = false)
    {
        if (null == $forumId || ! is_numeric($forumId) || $forumId == 0) {
            throw new \Exception('Forum id "' . $forumId . '" is invalid!');
        }

        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $params = array(':forumId' => $forumId, ':userId' => $userId);

        $qb = $this->createSelectQuery(array('s', 't', 'b', 'c', 'f', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));

        $qb
            ->innerJoin('s.topic', 't')
            ->innerJoin('t.firstPost', 'fp')
                ->leftJoin('fp.createdBy', 'fp_author')
            ->leftJoin('t.lastPost', 'lp')
                ->leftJoin('lp.createdBy', 'lp_author')
            ->leftJoin('t.closedBy', 't_closedBy')
            ->leftJoin('t.deletedBy', 't_deletedBy')
            ->leftJoin('t.stickiedBy', 't_stickiedBy')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->leftJoin('c.forum', 'f')
            ->where(
                call_user_func_array(function ($qb, $canViewDeletedTopics, $filter) {
                    if ($canViewDeletedTopics) {
                        $expr = $qb->expr()->eq('f.id', ':forumId');
                    } else {
                        $expr = $qb->expr()->andX(
                            $qb->expr()->eq('f.id', ':forumId'),
                            $qb->expr()->eq('t.isDeleted', 'FALSE')
                        );
                    }

                    $expr = $qb->expr()->andX(
                        $expr,
                        $qb->expr()->andX(
                            $qb->expr()->eq('s.ownedBy', ':userId'),
                            $qb->expr()->eq('s.isSubscribed', 'TRUE')
                        )
                    );

                    if ($filter) {
                        if ($filter == 'read') {
                            $expr = $qb->expr()->andX(
                                $expr,
                                $qb->expr()->eq('s.isRead', 'TRUE')
                            );
                        } else {
                            if ($filter == 'unread') {
                                $expr = $qb->expr()->andX(
                                    $expr,
                                    $qb->expr()->eq('s.isRead', 'FALSE')
                                );
                            }
                        }
                    }

                    return $expr;
                }, array($qb, $canViewDeletedTopics, $filter))
            )
            ->setParameters($params)
            ->orderBy('lp.createdDate', 'DESC')
        ;

        return $this->gateway->paginateQuery($qb, $itemsPerPage, $page);
    }

    /**
     *
     * @access public
     * @param  int                                        $topicId
     * @param  int                                        $userId
     * @return \CCDNForum\ForumBundle\Entity\Subscription
     */
    public function findOneSubscriptionForTopicByIdAndUserById($topicId, $userId)
    {
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('s', 't', 'u'));

        $qb
            ->leftJoin('s.topic', 't')
            ->leftJoin('s.ownedBy', 'u')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('s.topic', ':topicId'),
                    $qb->expr()->eq('s.ownedBy', ':userId')
                )
            )
        ;

        return $this->gateway->findSubscription($qb, array(':topicId' => $topicId, ':userId' => $userId));
    }

    /**
     *
     * @access public
     * @param  int $topicId
     * @return int
     */
    public function countSubscriptionsForTopicById($topicId)
    {
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        $qb = $this->createCountQuery();

        $qb->where('s.topic = :topicId');

        return $this->gateway->countSubscriptions($qb, array(':topicId' => $topicId));
    }
}
