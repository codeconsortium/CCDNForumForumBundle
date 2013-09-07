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
	
		$pager = $this->getPostModel()->getRepository()->findAllPostsPaginatedByTopicId($topic->getId(), 1, true);
		
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

			$foundPost = $this->getPostModel()->getRepository()->findOnePostByIdWithTopicAndBoard($post->getId(), true);
			
			$this->assertNotNull($foundPost->getId());
			$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Post', $foundPost);
		}
		
		// Can NOT view deleted topics.
		$topic2 = $this->addNewTopic('topic2', $board);
		$posts = $this->addFixturesForPosts(array($topic2), $users['harry']);
		
		$topic2->setIsDeleted(true);
		$this->em->persist($topic2);
		$this->em->flush();
		$this->em->refresh($topic2);
		
		foreach ($posts as $post) {
			$this->em->refresh($post);

			$foundPost = $this->getPostModel()->getRepository()->findOnePostByIdWithTopicAndBoard($post->getId(), false);
			
			$this->assertNull($foundPost);
		}
	}
}