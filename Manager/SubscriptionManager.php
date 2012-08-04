<?php

/*
 * This file is part of the CCDN ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\Manager;

use CCDNComponent\CommonBundle\Manager\ManagerInterface;
use CCDNComponent\CommonBundle\Manager\BaseManager;

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
     * @param $topicId, $user
     * @return $this
     */
    public function subscribe($topicId, $user)
    {
        $subscription = $this->container->get('ccdn_forum_forum.subscription.repository')->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());

        if (! $subscription) {
            $topic = $this->container->get('ccdn_forum_forum.topic.repository')->findOneById($topicId);

            if (! $topic) {
                $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.subscription.topic.not.found', array(), 'CCDNForumForumBundle'));

                return $this;
            }

            $subscription = new Subscription();

            $subscription->setOwnedBy($user);
            $subscription->setTopic($topic);
            $subscription->setIsRead(true);
        }

        $subscription->setIsSubscribed(true);

        $this->persist($subscription);

        $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.subscription.topic.subscribed', array('%topic_title%' => $subscription->getTopic()->getTitle()), 'CCDNForumForumBundle'));

        return $this;
    }

    /**
     *
     * @access public
     * @param $topicId, $user
     * @return $this
     */
    public function unsubscribe($topicId, $user)
    {
        $subscription = $this->container->get('ccdn_forum_forum.subscription.repository')->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());

        if (! $subscription) {
            return $this;
        }

        $subscription->setIsSubscribed(false);
        $subscription->setIsRead(true);

        $this->persist($subscription);

        $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('flash.subscription.topic.unsubscribed', array('%topic_title%' => $subscription->getTopic()->getTitle()), 'CCDNForumForumBundle'));

        return $this;
    }

}
