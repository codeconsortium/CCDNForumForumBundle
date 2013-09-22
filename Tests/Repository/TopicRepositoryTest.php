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
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

		$this->assertCount(3, $topics);

		$pager = $this->getTopicModel()->getRepository()->findAllTopicsPaginatedByBoardId($board->getId(), 1, true);
	
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

		$this->assertCount(3, $topics);

		foreach ($topics as $topic) {
			$topic->setIsSticky(true);
			$this->em->persist($topic);
		}
		
		$this->em->flush();
		
		$foundTopics = $this->getTopicModel()->getRepository()->findAllTopicsStickiedByBoardId($board->getId(), true);
		
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
		
		$foundTopic1 = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($topic1->getId(), true);
		
		$this->assertNotNull($foundTopic1);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic1);
        
		// Can NOT view deleted topics.
		$topic2 = $this->addNewTopic('topic2', $board);
		$topic2->setIsDeleted(true);
		
		$this->em->persist($topic2);
		$this->em->flush();
		$this->em->refresh($topic2);
		
		$foundTopic2 = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($topic2->getId(), false);
        
		$this->assertNull($foundTopic2);
	}

	public function testFindOneTopicByIdWithPosts()
	{
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		
		$topic = new Topic();
		$topic->setTitle('NewTopicTest');
        $topic->setCachedViewCount(0);
        $topic->setCachedReplyCount(0);
        $topic->setIsClosed(false);
        $topic->setIsDeleted(false);
        $topic->setIsSticky(false);
		
		$post = new Post();
		$post->setTopic($topic);
		$post->setBody('foobar');
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($users['tom']);
        $post->setIsDeleted(false);

		$this->getTopicModel()->getManager()->saveNewTopic($post);
		
		$this->em->refresh($post);
		
		$post2 = new Post();
		$post2->setTopic($post->getTopic());
		$post2->setBody('foobar');
        $post2->setCreatedDate(new \DateTime());
        $post2->setCreatedBy($users['tom']);
        $post2->setIsDeleted(false);
		
		$this->getPostModel()->getManager()->postTopicReply($post2);
		
		$foundTopic = $this->getTopicModel()->getRepository()->findOneTopicByIdWithPosts($post->getTopic()->getId(), true);
		
		$this->assertNotNull($foundTopic);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic);
		
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame('NewTopicTest', $foundTopic->getTitle());
		$this->assertCount(2, $foundTopic->getPosts());
	}
}