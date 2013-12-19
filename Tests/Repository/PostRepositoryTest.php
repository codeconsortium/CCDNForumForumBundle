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

class PostRepositoryTest extends TestBase
{
	public function testFindAllPostsPaginatedByTopicId()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllPostsPaginatedByTopicId');
		$category = $this->addNewCategory('testFindAllPostsPaginatedByTopicId', 1, $forum);
		$board = $this->addNewBoard('testFindAllPostsPaginatedByTopicId', 'testFindAllPostsPaginatedByTopicId', 1, $category);
		$topic = $this->addNewTopic('testFindAllPostsPaginatedByTopicId', $board);
		$posts = $this->addFixturesForPosts(array($topic), $users['tom']);
		$pager = $this->getPostModel()->findAllPostsPaginatedByTopicId($topic->getId(), 1, 25, true);
		$posts = $pager->getItems();
	
		$this->assertSame(3, count($posts));
	}

	public function testFindOnePostByIdWithTopicAndBoard()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$board = $this->addNewBoard('testFindOneTopicByIdWithBoardAndCategory', 'testFindOneTopicByIdWithBoardAndCategory', 1);

		// Can view deleted topics.
		$topic1 = $this->addNewTopic('topic1', $board);
		$posts = $this->addFixturesForPosts(array($topic1), $users['harry']);
		
		foreach ($posts as $post) {
			$this->em->refresh($post);
			$foundPost = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($post->getId(), true);
			$this->assertNotNull($foundPost->getId());
			$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Post', $foundPost);
		}
		
		// Can NOT view deleted topics.
		$topic2 = $this->addNewTopic('topic2', $board);
		$posts = $this->addFixturesForPosts(array($topic2), $users['harry']);
		$topic2->setDeleted(true);
		$this->em->persist($topic2);
		$this->em->flush();
		$this->em->refresh($topic2);
		
		foreach ($posts as $post) {
			$this->em->refresh($post);
			$foundPost = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($post->getId(), false);
			
			$this->assertNull($foundPost);
		}
	}

	public function testGetFirstPostForTopicById()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$firstPost = $this->getPostModel()->getFirstPostForTopicById($topics[0]->getId());

		$this->assertSame($firstPost->getId(), $topics[0]->getFirstPost()->getId());
	}

	public function testGetLastPostForTopicById()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$lastPost = $this->getPostModel()->getLastPostForTopicById($topics[0]->getId());
		
		$this->assertSame($lastPost->getId(), $topics[0]->getLastPost()->getId());
	}

	public function testCountPostsForTopicById()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$count = $this->getPostModel()->countPostsForTopicById($topics[0]->getId());
		
		$this->assertSame(3, (int) $count);
	}

    public function testCountPostsForUserById()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$this->addFixturesForPosts($topics, $users['tom']);
		$count = $this->getPostModel()->countPostsForUserById($users['tom']->getId());
		
		$this->assertSame(243, (int) $count);
	}
}