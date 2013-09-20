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

class TopicManagerTest extends TestBase
{
	public function testSaveNewTopic()
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
        $post->setIsLocked(false);
        $post->setIsDeleted(false);

		$this->getTopicModel()->getManager()->saveNewTopic($post);
		
		$foundTopic = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($post->getTopic()->getId(), true);
		
		$this->assertNotNull($foundTopic);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic);
		
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame('NewTopicTest', $foundTopic->getTitle());
		$this->assertSame(1, count($foundTopic->getPosts()));
	}

    public function testIncrementViewCounter()
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
        $post->setIsLocked(false);
        $post->setIsDeleted(false);

		$this->getTopicModel()->getManager()->saveNewTopic($post);
		
		$this->getTopicModel()->getManager()->incrementViewCounter($topic);
		
		$foundTopic = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($topic->getId(), true);
		
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertTrue(is_numeric($foundTopic->getCachedViewCount()));
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

        $this->getTopicModel()->getManager()->softDelete($topics[0], $users['tom']);
		
		$this->em->refresh($topics[0]);
		$this->assertTrue($topics[0]->isDeleted());

        $this->getTopicModel()->getManager()->restore($topics[0]);
		
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

        $this->getTopicModel()->getManager()->softDelete($topics[0], $users['tom']);
		
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

        $this->getTopicModel()->getManager()->sticky($topics[0], $users['tom']);
		
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

        $this->getTopicModel()->getManager()->unsticky($topics[0]);
		
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

        $this->getTopicModel()->getManager()->close($topics[0], $users['tom']);
		
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
		$posts = $this->addFixturesForPosts($topics, $users['tom']);

        $this->getTopicModel()->getManager()->reopen($topics[0]);
		
		$this->em->refresh($topics[0]);
		$this->assertFalse($topics[0]->isClosed());
	}
}