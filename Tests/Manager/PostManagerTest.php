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

class PostManagerTest extends TestBase
{
	public function testSavePost()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$topic = $this->addNewTopic('NewTopicTest', null, false, false);
		$post = $this->addNewPost('foobar', $topic, $users['tom'], new \DateTime(), false, false);
		$this->getPostModel()->savePost($post);
		$this->em->refresh($post);
		$topic->setFirstPost($post);
		$topic->setLastPost($post);
		$this->getTopicModel()->saveTopic($topic);
		$post2 = $this->addNewPost('foobar', $topic, $users['tom'], new \DateTime(), false, false);
		$this->getPostModel()->savePost($post2);
		$foundTopic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topic->getId(), true);

		$this->assertNotNull($foundTopic);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic);
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame('NewTopicTest', $foundTopic->getTitle());
	}

    public function testUpdatePost()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		$topic = $this->addNewTopic('NewTopicTest', null, false, false);;
		$post = $this->addNewPost('foobar', $topic, $users['tom'], new \DateTime(), false, false);
		$this->getPostModel()->savePost($post);
		$this->getTopicModel()->saveTopic($topic);
		$this->em->refresh($post);
		$post->setBody('edited post');
		$this->getPostModel()->updatePost($post);
		$this->em->refresh($post);

		$this->assertTrue(is_numeric($post->getId()));
		$this->assertSame('edited post', $post->getBody());
    }
	
	public function testSoftDeletePost()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$this->getPostModel()->softDelete($posts[0], $users['tom']);
		$this->em->refresh($posts[0]);
		
		$this->assertTrue($posts[0]->isDeleted());
	}

	public function testRestorePost()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$this->getPostModel()->softDelete($posts[0], $users['tom']);
		$this->em->refresh($posts[0]);
		$this->getPostModel()->restore($posts[0]);
		$this->em->refresh($posts[0]);
		
		$this->assertFalse($posts[0]->isDeleted());
	}
}