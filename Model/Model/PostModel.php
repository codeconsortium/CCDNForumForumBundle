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

namespace CCDNForum\ForumBundle\Model\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\BaseModelInterface;

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
class PostModel extends BaseModel implements BaseModelInterface
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
        return $this->getRepository()->findAllPostsPaginatedByTopicId($topicId, $page, $canViewDeletedTopics);
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
        return $this->getRepository()->findOnePostByIdWithTopicAndBoard($postId, $canViewDeletedTopics);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function postTopicReply(Post $post)
    {
        return $this->getManager()->postTopicReply($post);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updatePost(Post $post)
    {
        return $this->getManager()->updatePost($post);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function lock(Post $post)
    {
		return $this->getManager()->lock($post);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function restore(Post $post)
    {
        return $this->getManager()->restore($post);
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
        return $this->getManager()->softDelete($post, $user);
    }













//    /**
//     *
//     * @access public
//     * @return bool
//     */
//    public function allowedToViewDeletedTopics()
//    {
//        return $this->managerBag->getPolicyManager()->allowedToViewDeletedTopics();
//    }
//
//    /**
//     *
//     * @access public
//     * @param  int                                $topicId
//     * @return \CCDNForum\ForumBundle\Entity\Post
//     */
//    public function getFirstPostForTopicById($topicId)
//    {
//        return $this->getRepository()->getFirstPostForTopicById($topicId);
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
//        return $this->getRepository()->getLastPostForTopicById($topicId);
//    }
//
//
//
//
//    /**
//     *
//     * @access public
//     * @param  Array                                        $postIds
//     * @return \Doctrine\Common\Collections\ArrayCollection
//     */
//    public function findThesePostsById($postIds = array())
//    {
//        return $this->getRepository()->findThesePostsById($postIds);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  int                    $page
//     * @return \Pagerfanta\Pagerfanta
//     */
//    public function findLockedPostsForModeratorsPaginated($page)
//    {
//        return $this->getRepository()->findLockedPostsForModeratorsPaginated($page);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  int                    $page
//     * @return \Pagerfanta\Pagerfanta
//     */
//    public function findDeletedPostsForAdminsPaginated($page)
//    {
//        return $this->getRepository()->findDeletedPostsForAdminsPaginated($page);
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
//        return $this->getRepository()->getPostCountForUserById($userId);
//    }
//
//
//
//    /**
//     *
//     * @access public
//     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
//     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function lock(Post $post, UserInterface $user)
//    {
//        return $this->getManager()->lock($post, $user);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  Array                                               $posts
//     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkLock($posts, UserInterface $user)
//    {
//        return $this->getManager()->bulkLock($posts, $user);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function unlock(Post $post)
//    {
//        return $this->getManager()->unlock($post);
//    }
//
//    /**
//     *
//     * @access public
//     * @param  Array                                               $posts
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkUnlock($posts)
//    {
//        return $this->getManager()->bulkUnlock($posts);
//    }
//
//
//    /**
//     *
//     * @access public
//     * @param  Array                                               $posts
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function bulkRestore($posts)
//    {
//        return $this->getManager()->bulkRestore($posts);
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
//        return $his->getManager()->bulkSoftDelete($posts, $user);
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
//        return $this->getManager()->bulkHardDelete($posts);
//    }
}