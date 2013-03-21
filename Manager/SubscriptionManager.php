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

use CCDNForum\ForumBundle\Entity\Subscription;
use CCDNForum\ForumBundle\Entity\Topic;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class SubscriptionManager extends BaseManager implements BaseManagerInterface
{
	/**
	 *
	 * @access public
	 * @param int $topicId
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
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 * @return \CCDNForum\ForumBundle\Entity\Subscription
	 */	
	public function findSubscriptionForTopicById($topicId)
	{
		if (! $this->isGranted('ROLE_USER')) {
			return null;
		}
		
		return $this->findSubscriptionForTopicByIdAndUserId($topicId, $this->getUser()->getId());
	}
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 * @param int $userId = null
	 * @return \CCDNForum\ForumBundle\Entity\Subscription
	 */	
	public function findSubscriptionForTopicByIdAndUserId($topicId, $userId = null)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}
		
		if (null == $userId || ! is_numeric($userId) || $userId == 0) {
			if ($this->isGranted('ROLE_USER')) {
				$userId = $this->getUser()->getId();
			} else {
				throw new \Exception('User id "' . $userId . '" is invalid!');
			}	
		}
		
		$qb = $this->createSelectQuery(array('s'));
		
		$qb
			->where(
				$qb->expr()->andX(
					$qb->expr()->eq('s.topic', ':topicId'),
					$qb->expr()->eq('s.ownedBy', ':userId')
				)
			);
		
		return $this->gateway->findSubscription($qb, array(':topicId' => $topicId, ':userId' => $userId));
	}
	
	/**
	 *
	 * @access public
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */
	public function findAllPaginated($page)
	{				
		$qb = $this->createSelectQuery(array('s', 't', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author'));
		
		$params = array(':userId' => $this->getUser()->getId());
		
		$qb
			->innerJoin('s.topic', 't')
			->innerJoin('t.firstPost', 'fp')
			->leftJoin('t.lastPost', 'lp')
			->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where('s.ownedBy = :userId')
			->setParameters($params)
			->orderBy('lp.createdDate', 'DESC');

		return $this->gateway->paginateQuery($qb, $this->getTopicsPerPageOnSubscriptions(), $page);
	}
	
    /**
     *
     * @access public
     * @param int $topicId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function subscribe(Topic $topic)
    {
		$subscription = $this->findSubscriptionForTopicById($topic->getId());

        if (! $subscription) {
            $subscription = new Subscription();
		}
		
		if (! $subscription->getIsSubscribed())
		{
	        $subscription->setIsSubscribed(true);

            $subscription->setOwnedBy($this->getUser());
            $subscription->setTopic($topic);
            $subscription->setIsRead(true);

	        $this->persist($subscription)->flush();
		}
		
        return $this;
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsubscribe(Topic $topic)
    {
		$subscription = $this->findSubscriptionForTopicById($topic->getId());

        if (! $subscription) {
            return $this;
        }

        $subscription->setIsSubscribed(false);
        $subscription->setIsRead(true);

        $this->persist($subscription)->flush();

        return $this;
    }
}