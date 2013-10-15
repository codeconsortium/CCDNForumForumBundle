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

class PostManagerTest extends TestBase
{
	public function testPostTopicReply()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		
		$topic = new Topic();
		$topic->setTitle('NewTopicTest');
        $topic->setCachedViewCount(0);
        $topic->setCachedReplyCount(0);
        $topic->setClosed(false);
        $topic->setDeleted(false);
        $topic->setSticky(false);
		
		$post = new Post();
		$post->setTopic($topic);
		$post->setBody('foobar');
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($users['tom']);
        $post->setDeleted(false);

		$this->getTopicModel()->saveNewTopic($post);

		$this->em->refresh($post);
		
		$post2 = new Post();
		$post2->setTopic($post->getTopic());
		$post2->setBody('foobar');
        $post2->setCreatedDate(new \DateTime());
        $post2->setCreatedBy($users['tom']);
        $post2->setDeleted(false);
		
		$this->getPostModel()->postTopicReply($post2);
		
		$foundTopic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($post->getTopic()->getId(), true);
		
		$this->assertNotNull($foundTopic);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic);
		
		$this->assertTrue(is_numeric($foundTopic->getId()));
		$this->assertSame('NewTopicTest', $foundTopic->getTitle());
		$this->assertSame(2, count($foundTopic->getPosts()));
	}

    public function testUpdatePost()
    {
		$this->purge();
		$users = $this->addFixturesForUsers();
		
		$topic = new Topic();
		$topic->setTitle('NewTopicTest');
        $topic->setCachedViewCount(0);
        $topic->setCachedReplyCount(0);
        $topic->setClosed(false);
        $topic->setDeleted(false);
        $topic->setSticky(false);
		
		$post = new Post();
		$post->setTopic($topic);
		$post->setBody('foobar');
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($users['tom']);
        $post->setDeleted(false);

		$this->getTopicModel()->saveNewTopic($post);

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