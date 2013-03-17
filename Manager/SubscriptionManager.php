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
     * @param int $topicId, $user
     * @return self
     */
    public function subscribe($topicId, $user)
    {
        $subscription = $this->repository->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());

        if (! $subscription) {
            $topic = $this->managerBag->getTopicManager()->getRepository()->findOneById($topicId);

            if (! $topic) {
                return $this;
            }

            $subscription = new Subscription();
		}
		
		if ( ! $subscription->getIsSubscribed())
		{
	        $subscription->setIsSubscribed(true);

            $subscription->setOwnedBy($user);
            $subscription->setTopic($topic);
            $subscription->setIsRead(true);

	        $this->persist($subscription)->flush();
		}
		
        return $this;
    }

    /**
     *
     * @access public
     * @param int $topicId, $user
     * @return self
     */
    public function unsubscribe($topicId, $user)
    {
        $subscription = $this->repository->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());

        if (! $subscription) {
            return $this;
        }

        $subscription->setIsSubscribed(false);
        $subscription->setIsRead(true);

        $this->persist($subscription);

        return $this;
    }
}