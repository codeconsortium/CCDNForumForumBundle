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

class TopicManagerTest extends TestBase
{
	public function testSaveTopic()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$topic = $this->addNewTopic('NewTopicTest', null, false, false);
		$post = $this->addNewPost('foobar', $topic, $users['tom'], new \DateTime(), false, false);
		$this->getTopicModel()->saveTopic($topic);
		$this->getPostModel()->savePost($post);
		$foundTopic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topic->getId(), true);

		$this->assertNotNull($foundTopic);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic);
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame('NewTopicTest', $foundTopic->getTitle());
	}

	public function testUpdateTopic()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$topics[0]->setTitle('the_new_title');
        $this->getTopicModel()->updateTopic($topics[0]);
		$this->em->refresh($topics[0]);

		$this->assertSame('the_new_title', $topics[0]->getTitle());
	}

    public function testIncrementViewCounter()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$topic = $this->addNewTopic('NewTopicTest', null, false, false);
		$post = $this->addNewPost('foobar', $topic, $users['tom'], new \DateTime(), false, false);
		$this->getPostModel()->savePost($post);
		$this->getTopicModel()->saveTopic($topic);
		$this->getTopicModel()->incrementViewCounter($topic);
		$foundTopic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topic->getId(), true);
		
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame(1, $foundTopic->getCachedViewCount());
	}

    public function testRestore()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
        $this->getTopicModel()->softDelete($topics[0], $users['tom']);
		$this->em->refresh($topics[0]);

		$this->assertTrue($topics[0]->isDeleted());
        $this->getTopicModel()->restore($topics[0]);
		$this->em->refresh($topics[0]);
		$this->assertFalse($topics[0]->isDeleted());
    }

    public function testSoftDelete()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
        $this->getTopicModel()->softDelete($topics[0], $users['tom']);
		$this->em->refresh($topics[0]);

		$this->assertTrue($topics[0]->isDeleted());
    }

	public function testSticky()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
        $this->getTopicModel()->sticky($topics[0], $users['tom']);
		$this->em->refresh($topics[0]);

		$this->assertTrue($topics[0]->isSticky());
	}

	public function testUnsticky()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
        $this->getTopicModel()->unsticky($topics[0]);
		$this->em->refresh($topics[0]);

		$this->assertFalse($topics[0]->isSticky());
	}

	public function testClose()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
        $this->getTopicModel()->close($topics[0], $users['tom']);
		$this->em->refresh($topics[0]);

		$this->assertTrue($topics[0]->isClosed());
	}

	public function testReopen()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
        $this->getTopicModel()->reopen($topics[0]);
		$this->em->refresh($topics[0]);

		$this->assertFalse($topics[0]->isClosed());
	}
}