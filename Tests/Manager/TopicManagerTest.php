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
        $post->setCreatedBy($this->users['tom']);
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
	
//	public function testUpdateBoard()
//	{
//		$board = $this->addNewBoard('UpdateBoardTest', 'Generic Description', 1);
//		
//		$board->setName('BoardTestUpdated');
//		
//		$this->getBoardModel()->getManager()->updateBoard($board);
//		
//		$this->assertTrue(is_numeric($board->getId()));
//		$this->assertEquals('BoardTestUpdated', $board->getName());
//	}
//	
//	public function testDeleteBoard()
//	{
//		$board = $this->addNewBoard('DeleteBoardTest', 'Generic Description', 1);
//		
//		$boardId = $board->getId();
//		$this->getBoardModel()->getManager()->deleteBoard($board);
//		
//		$foundBoard = $this->getBoardModel()->getRepository()->findOneBoardById($boardId);
//		
//		$this->assertNull($foundBoard);
//	}
//	
//	public function testReassignTopicsToBoard()
//	{
//		$forum = $this->addNewForum('testReassignTopicsToBoard');
//		$categories = $this->addFixturesForCategories(array($forum));
//		$boards = $this->addFixturesForBoards($categories);
//		$topics = $this->addFixturesForTopics($boards);
//		
//		$board1 = $boards[0];
//		$board2 = $boards[1];
//		$topics = new ArrayCollection($board1->getTopics()->toArray());
//		
//		$this->assertCount(3, $board1->getTopics());
//		$this->getBoardModel()->getManager()->reassignTopicsToBoard($topics, null);
//		$this->em->refresh($board1);
//		$this->assertCount(0, $board1->getTopics());
//		
//		$this->getBoardModel()->getManager()->reassignTopicsToBoard($topics, $board2);
//		$this->em->refresh($board2);
//		$this->assertCount(6, $board2->getTopics());
//	}
}