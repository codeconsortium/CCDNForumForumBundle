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

namespace CCDNForum\ForumBundle\Tests\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use CCDNForum\ForumBundle\Tests\TestBase;

class SubscriptionManagerTest extends TestBase
{
    public function testSubscribe()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['tom']);
	    $subscriptionFound = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		
		$this->assertTrue($subscriptionFound->isSubscribed());
		$this->assertInternalType('integer', $subscriptionFound->getId());
    }

    public function testUnsubscribe()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['tom']);
		$this->getSubscriptionModel()->unsubscribe($topics[0], $users['tom']->getId());
	    $subscriptionFound = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		
		$this->assertFalse($subscriptionFound->isSubscribed());
		$this->assertInternalType('integer', $subscriptionFound->getId());
    }
	
	public function testMarkAsRead()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['tom']);
	    $subscriptionFound = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		$this->getSubscriptionModel()->markAsRead($subscriptionFound);
		$subscriptionsFound = $this->getSubscriptionModel()->findAllSubscriptionsForUserById($users['tom']->getId(), true);
		
		foreach ($subscriptionsFound as $subscription) {
			if ($subscription->getTopic()->getId() == $topics[0]->getId()) {
				$this->assertTrue($subscription->isSubscribed());
				$this->assertTrue($subscription->isRead());
				$this->assertInternalType('integer', $subscription->getId());
			}
		}
	}

	public function testMarkAsUnread()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['tom']);
	    $subscriptionFound = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		$this->getSubscriptionModel()->markAsUnread($subscriptionFound);
		$subscriptionsFound = $this->getSubscriptionModel()->findAllSubscriptionsForUserById($users['tom']->getId(), true);
		
		foreach ($subscriptionsFound as $subscription) {
			if ($subscription->getTopic()->getId() == $topics[0]->getId()) {
				$this->assertTrue($subscription->isSubscribed());
				$this->assertFalse($subscription->isRead());
				$this->assertInternalType('integer', $subscription->getId());
			}
		}
	}
	
    public function testMarkTheseAsUnread()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['tom']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['dick']);
		$this->getSubscriptionModel()->subscribe($topics[0], $users['harry']);
		$subscriptions = array();
	    $subscriptions[0] = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
	    $subscriptions[1] = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['dick']->getId());
	    $subscriptions[2] = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['harry']->getId());
		$this->getSubscriptionModel()->markTheseAsUnread($subscriptions, $users['dick']);
		$subscriptionsFound = $this->getSubscriptionModel()->findAllSubscriptionsForTopicById($topics[0]->getId(), true);
		
		foreach ($subscriptionsFound as $subscription) {
			if ($subscription->getTopic()->getId() == $topics[0]->getId()) {
				$this->assertTrue($subscription->isSubscribed());
				$this->assertInternalType('integer', $subscription->getId());
				
				if ($subscription->getOwnedBy()->getId() == $users['dick']->getId()) {
					$this->assertTrue($subscription->isRead());
				} else {
					$this->assertFalse($subscription->isRead());
				}
			}
		}
	}
}