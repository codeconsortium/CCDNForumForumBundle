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

use CCDNForum\ForumBundle\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Subscription;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class SubscriptionManager extends BaseManager implements ManagerInterface
{

    /**
     *
     * @access public
     * @param int $topicId, $user
     * @return self
     */
    public function subscribe($topicId, $user)
    {
        $subscription = $this->container->get('ccdn_forum_forum.repository.subscription')->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());

        if (! $subscription) {
            $topic = $this->container->get('ccdn_forum_forum.repository.topic')->findOneById($topicId);

            if (! $topic) {
                $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('ccdn_forum_forum.flash.subscription.topic.not.found', array(), 'CCDNForumForumBundle'));

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

        	$this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('ccdn_forum_forum.flash.subscription.topic.subscribed', array('%topic_title%' => $subscription->getTopic()->getTitle()), 'CCDNForumForumBundle'));
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
        $subscription = $this->container->get('ccdn_forum_forum.repository.subscription')->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());

        if (! $subscription) {
            return $this;
        }

        $subscription->setIsSubscribed(false);
        $subscription->setIsRead(true);

        $this->persist($subscription);

        $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('ccdn_forum_forum.flash.subscription.topic.unsubscribed', array('%topic_title%' => $subscription->getTopic()->getTitle()), 'CCDNForumForumBundle'));

        return $this;
    }

}
