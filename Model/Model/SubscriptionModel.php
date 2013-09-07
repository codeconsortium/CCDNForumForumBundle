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

use CCDNForum\ForumBundle\Entity\Subscription;
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
class SubscriptionModel extends BaseModel implements BaseModelInterface
{
	/**
	 * 
	 * @access public
	 * @param  int                                                      $userId
	 * @param  bool                                                     $canViewDeletedTopics
	 * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
	 */
	public function findAllSubscriptionsForUserById($userId, $canViewDeletedTopics = false)
	{
		return $this->getRepository()->findAllSubscriptionsForUserById($userId, $canViewDeletedTopics);
	}

	/**
	 * 
	 * @access public
	 * @param  int                                                      $userId
	 * @param  int                                                      $page
	 * @param  string                                                   $filter
	 * @param  bool                                                     $canViewDeletedTopics
	 * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
	 */
	public function findAllSubscriptionsPaginatedForUserById($userId, $page, $filter, $canViewDeletedTopics = false)
	{
		return $this->getRepository()->findAllSubscriptionsPaginatedForUserById($userId, $page, $filter, $canViewDeletedTopics = false);
	}

	/**
	 * 
	 * @access public
	 * @param  int                                                      $forumId
	 * @param  int                                                      $userId
	 * @param  int                                                      $page
	 * @param  string                                                   $filter
	 * @param  bool                                                     $canViewDeletedTopics
	 * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
	 */
	public function findAllSubscriptionsPaginatedForUserByIdAndForumById($forumId, $userId, $page, $filter, $canViewDeletedTopics = false)
	{
		return $this->getRepository()->findAllSubscriptionsPaginatedForUserByIdAndForumById($forumId, $userId, $page, $filter, $canViewDeletedTopics = false);
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
        return $this->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topicId, $userId);
    }

    /**
     *
     * @access public
     * @param  int $topicId
     * @return int
     */
    public function countSubscriptionsForTopicById($topicId)
    {
        return $this->getRepository()->countSubscriptionsForTopicById($topicId);
    }

    /**
     *
     * @access public
     * @param  int                                                 $topicId
     * @param  \Symfony\Component\Security\Core\User\UserInterface  $userId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function subscribe(Topic $topic, UserInterface $user)
    {
        return $this->getManager()->subscribe($topic, $user);
    }

    /**
     *
     * @access public
     * @param  int                                                 $topicId
     * @param  int                                                 $userId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsubscribe(Topic $topic, $userId)
    {
        return $this->getManager()->unsubscribe($topic, $userId);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Subscription          $subscription
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function markAsRead(Subscription $subscription)
	{
		return $this->getManager()->markAsRead($subscription);
	}

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Subscription          $subscription
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function markAsUnread(Subscription $subscription)
	{
		return $this->getManager()->markAsUnread($subscription);
	}

//    /**
//     *
//     * @access public
//     * @param  int                    $page
//     * @return \Pagerfanta\Pagerfanta
//     */
//    public function findAllPaginated($page)
//    {
//        return $this->getRepository()->findAllPaginated($page);
//    }
//

//
//    /**
//     *
//     * @access public
//     * @param  int                                        $topicId
//     * @param  int                                        $userId  = null
//     * @return \CCDNForum\ForumBundle\Entity\Subscription
//     */
//    public function findSubscriptionForTopicByIdAndUserId($topicId, $userId = null)
//    {
//       return $this->getRepository()->findSubscriptionFortopicByIdAndUserId($topicId, $userId);
//    }
}