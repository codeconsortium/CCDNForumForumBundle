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

namespace CCDNForum\ForumBundle\Model\Repository;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Model\Repository\BaseRepository;
use CCDNForum\ForumBundle\Model\Repository\BaseRepositoryInterface;

/**
 * PostRepository
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 */
class PostRepository extends BaseRepository implements BaseRepositoryInterface
{
    /**
     *
     * @access public
     * @param  int                                                      $topicId
     * @param  int                                                      $page
     * @param  bool                                                     $canViewDeletedTopics
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function findAllPostsPaginatedByTopicId($topicId, $page, $canViewDeletedTopics = false)
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
            ->where(
                call_user_func_array(function($canViewDeletedTopics, $qb) {
                    if ($canViewDeletedTopics) {
                        $expr = $qb->expr()->eq('p.topic', ':topicId');
                    } else {
                        $expr = $qb->expr()->andX(
                            $qb->expr()->eq('p.topic', ':topicId'),
                            $qb->expr()->eq('t.isDeleted', 'FALSE')
                        );
                    }

                    return $expr;
                }, array($canViewDeletedTopics, $qb))
            )
            ->setParameters($params)
            ->orderBy('p.createdDate', 'ASC')
        ;

        return $this->gateway->paginateQuery($qb, $this->getPostsPerPageOnTopics(), $page);
    }

    /**
     *
     * @access public
     * @param  int                                $postId
     * @param  bool                               $canViewDeletedTopics
     * @return \CCDNForum\ForumBundle\Entity\Post
     */
    public function findOnePostByIdWithTopicAndBoard($postId, $canViewDeletedTopics = false)
    {
        if (null == $postId || ! is_numeric($postId) || $postId == 0) {
            throw new \Exception('Post id "' . $postId . '" is invalid!');
        }

        $params = array(':postId' => $postId);

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
            ->where(
                call_user_func_array(function($canViewDeletedTopics, $qb) {
                    if ($canViewDeletedTopics) {
                        $expr = $qb->expr()->eq('p.id', ':postId');
                    } else {
                        $expr = $qb->expr()->andX(
                            $qb->expr()->eq('p.id', ':postId'),
                            $qb->expr()->eq('t.isDeleted', 'FALSE')
                        );
                    }

                    return $expr;
                }, array($canViewDeletedTopics, $qb))
            )
        ;

        return $this->gateway->findPost($qb, $params);
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

        $qb = $this->createSelectQuery(array('p', 't'));

        $qb
            ->leftJoin('p.topic', 't')
            ->where(
                $expr = $qb->expr()->eq('t.id', ':topicId')
            )
			->orderBy('p.createdDate', 'ASC')
			->setMaxResults(1)
        ;

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

        $qb = $this->createSelectQuery(array('p', 't'));

        $qb
            ->leftJoin('p.topic', 't')
            ->where(
                $expr = $qb->expr()->eq('t.id', ':topicId')
            )
			->orderBy('p.createdDate', 'DESC')
			->setMaxResults(1)
        ;

        return $this->gateway->findPost($qb, $params);
	}

	/**
	 * 
	 * @access public
	 * @param  int   $topicId
	 * @return Array
	 */
	public function countPostsForTopicById($topicId)
	{
        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
        }

        $qb = $this->createCountQuery();

        $qb->where('p.topic = :topicId');

        return $this->gateway->countPosts($qb, array(':topicId' => $topicId));
	}

//    /**
//     *
//     * @access public
//     * @param  int                                $topicId
//     * @return \CCDNForum\ForumBundle\Entity\Post
//     */
//    public function getFirstPostForTopicById($topicId)
//    {
//        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
//            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
//        }
//
//        $params = array(':topicId' => $topicId);
//
//        $qb = $this->createSelectQuery(array('p'));
//
//        $qb
//            ->where('p.topic = :topicId')
//            ->orderBy('p.createdDate', 'ASC')
//            ->setMaxResults(1);
//
//        return $this->gateway->findPost($qb, $params);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  int                                $topicId
//     * @return \CCDNForum\ForumBundle\Entity\Post
//     */
//    public function getLastPostForTopicById($topicId)
//    {
//        if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
//            throw new \Exception('Topic id "' . $topicId . '" is invalid!');
//        }
//
//        $params = array(':topicId' => $topicId);
//
//        $qb = $this->createSelectQuery(array('p'));
//
//        $qb
//            ->where('p.topic = :topicId')
//            ->orderBy('p.createdDate', 'DESC')
//            ->setMaxResults(1);
//
//        return $this->gateway->findPost($qb, $params);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  Array                                        $postIds
//     * @return \Doctrine\Common\Collections\ArrayCollection
//     */
//    public function findThesePostsById($postIds = array())
//    {
//        if (! is_array($postIds) || count($postIds) < 1) {
//            throw new \Exception('Parameter 1 must be an array and contain at least 1 post id!');
//        }
//
//        $qb = $this->createSelectQuery(array('p'));
//
//        $qb
//            ->where($qb->expr()->in('p.id', $postIds))
//            ->orderBy('p.createdDate', 'ASC')
//        ;
//
//        return $this->gateway->findPosts($qb);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  int   $userId
//     * @return Array
//     */
//    public function getPostCountForUserById($userId)
//    {
//        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
//            throw new \Exception('User id "' . $userId . '" is invalid!');
//        }
//
//        $qb = $this->getQueryBuilder();
//
//        $topicEntityClass = $this->gateway->getEntityClass();
//
//        $qb
//            ->select('COUNT(DISTINCT p.id) AS postCount')
//            ->from($topicEntityClass, 'p')
//            ->where('p.createdBy = :userId')
//            ->setParameter(':userId', $userId);
//
//        try {
//            return $qb->getQuery()->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return array('postCount' => null);
//        } catch (\Exception $e) {
//            return array('postCount' => null);
//        }
//    }
}
