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

namespace CCDNForum\ForumBundle\Tests\Repository;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Tests\TestBase;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['tom']);
		
	    $subscriptionFound = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		
		$this->assertNotNull($subscriptionFound);
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['tom']);
		
		$this->getSubscriptionModel()->getManager()->unsubscribe($topics[0], $users['tom']->getId());
	
	    $subscriptionFound = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		
		$this->assertNotNull($subscriptionFound);
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['tom']);

	    $subscriptionFound = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		
		$this->getSubscriptionModel()->getManager()->markAsRead($subscriptionFound);
	
		$subscriptionsFound = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsForUserById($users['tom']->getId(), true);
		
		foreach ($subscriptionsFound as $subscription) {
			if ($subscriptionFound->getTopic()->getId() == $topics[0]->getId()) {
				$this->assertTrue($subscriptionFound->isSubscribed());
				$this->assertTrue($subscriptionFound->isRead());
				$this->assertInternalType('integer', $subscriptionFound->getId());
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['tom']);

	    $subscriptionFound = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
		
		$this->getSubscriptionModel()->getManager()->markAsUnread($subscriptionFound);
	
		$subscriptionsFound = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsForUserById($users['tom']->getId(), true);
		
		foreach ($subscriptionsFound as $subscription) {
			if ($subscriptionFound->getTopic()->getId() == $topics[0]->getId()) {
				$this->assertTrue($subscriptionFound->isSubscribed());
				$this->assertFalse($subscriptionFound->isRead());
				$this->assertInternalType('integer', $subscriptionFound->getId());
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['tom']);
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['dick']);
		$this->getSubscriptionModel()->getManager()->subscribe($topics[0], $users['harry']);

		$subscriptions = array();
	    $subscriptions[0] = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());
	    $subscriptions[1] = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['dick']->getId());
	    $subscriptions[2] = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['harry']->getId());
		
		$this->getSubscriptionModel()->getManager()->markTheseAsUnread($subscriptions, $users['dick']);
	
		$subscriptionsFound = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsForTopicById($topics[0]->getId(), true);
		
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