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

		$this->em->refresh($post);
		
		$post2 = new Post();
		$post2->setTopic($post->getTopic());
		$post2->setBody('foobar');
        $post2->setCreatedDate(new \DateTime());
        $post2->setCreatedBy($users['tom']);
        $post2->setIsLocked(false);
        $post2->setIsDeleted(false);
		
		$this->getPostModel()->getManager()->postTopicReply($post2);
		
		$foundTopic = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($post->getTopic()->getId(), true);
		
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

		$this->em->refresh($post);

		$post->setBody('edited post');
		$this->getPostModel()->getManager()->updatePost($post);

		$this->em->refresh($post);

		$this->assertTrue(is_numeric($post->getId()));
		$this->assertSame('edited post', $post->getBody());
    }
}