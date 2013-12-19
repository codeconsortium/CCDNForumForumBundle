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

use CCDNForum\ForumBundle\Tests\TestBase;

class TopicRepositoryTest extends TestBase
{
	public function testFindAllTopicsPaginatedByBoardId()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllTopicsPaginatedByBoardId');
		$category = $this->addNewCategory('testFindAllTopicsPaginatedByBoardId', 1, $forum);
		$board = $this->addNewBoard('testFindAllTopicsPaginatedByBoardId', 'testFindAllTopicsPaginatedByBoardId', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$this->addFixturesForPosts($topics, $users['tom']);

		$this->assertCount(3, $topics);
		$pager = $this->getTopicModel()->findAllTopicsPaginatedByBoardId($board->getId(), 1, 25, true);
		$foundTopics = $pager->getItems();
		$this->assertCount(3, $foundTopics);
	}

	public function testFindAllTopicsStickiedByBoardId()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllPostsPaginatedByTopicId');
		$category = $this->addNewCategory('testFindAllPostsPaginatedByTopicId', 1, $forum);
		$board = $this->addNewBoard('testFindAllPostsPaginatedByTopicId', 'testFindAllPostsPaginatedByTopicId', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$this->addFixturesForPosts($topics, $users['tom']);

		$this->assertCount(3, $topics);

		foreach ($topics as $topic) {
			$topic->setSticky(true);
			$this->em->persist($topic);
		}
		
		$this->em->flush();
		$foundTopics = $this->getTopicModel()->findAllTopicsStickiedByBoardId($board->getId(), true);
		$this->assertCount(3, $foundTopics);
	}

	public function testFindOneTopicByIdWithBoardAndCategory()
	{
		$this->purge();
		$board = $this->addNewBoard('testFindOneTopicByIdWithBoardAndCategory', 'testFindOneTopicByIdWithBoardAndCategory', 1);

		// Can view deleted topics.
		$topic1 = $this->addNewTopic('topic1', $board);
		$this->em->persist($topic1);
		$this->em->flush($topic1);
		$this->em->refresh($topic1);
		
		$foundTopic1 = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topic1->getId(), true);
		
		$this->assertNotNull($foundTopic1);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic1);
        
		// Can NOT view deleted topics.
		$topic2 = $this->addNewTopic('topic2', $board);
		$topic2->setDeleted(true);
		
		$this->em->persist($topic2);
		$this->em->flush();
		$this->em->refresh($topic2);
		$foundTopic2 = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topic2->getId(), false);
        
		$this->assertNull($foundTopic2);
	}

	public function testFindOneTopicByIdWithPosts()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$topic = $this->addNewTopic('NewTopicTest', null, true, true);
		$this->addNewPost('foobar1', $topic, $users['tom'], new \DateTime(), true, true);
		$this->addNewPost('foobar2', $topic, $users['tom'], new \DateTime(), true, true);
		$this->em->refresh($topic);
		$foundTopic = $this->getTopicModel()->findOneTopicByIdWithPosts($topic->getId(), true);
		
		$this->assertNotNull($foundTopic);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic);
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame('NewTopicTest', $foundTopic->getTitle());
		$this->assertCount(2, $foundTopic->getPosts());
	}

	public function testFindLastTopicForBoardByIdWithLastPost()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);

		$lastTopic = $this->getTopicModel()->findLastTopicForBoardByIdWithLastPost($topics[count($topics) - 1]->getBoard()->getId());

		$this->assertSame($topics[count($topics) - 1]->getId(), $lastTopic->getId());
	}

	public function testGetTopicAndPostCountForBoardById()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
	
		$count = $this->getTopicModel()->getTopicAndPostCountForBoardById($boards[0]->getId());
	
		$this->assertSame(3, (int) $count['topicCount']);
		$this->assertSame(9, (int) $count['postCount']);
	}
}