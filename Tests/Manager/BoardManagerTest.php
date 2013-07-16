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
use CCDNForum\ForumBundle\Entity\Board;

class BoardManagerTest extends TestBase
{
	public function testSaveNewBoard()
	{
		$board = new Board();
		$board->setName('NewBoardTest');
		$board->setDescription('Generic description');
		$board->setListOrderPriority(1);
		
		$this->getBoardModel()->getManager()->saveNewBoard($board);
		
		$this->assertTrue(is_numeric($board->getId()));
		$this->assertSame('NewBoardTest', $board->getName());
	}
	
	public function testUpdateBoard()
	{
		$board = $this->addNewBoard('UpdateBoardTest', 'Generic Description', 1);
		
		$board->setName('BoardTestUpdated');
		
		$this->getBoardModel()->getManager()->updateBoard($board);
		
		$this->assertTrue(is_numeric($board->getId()));
		$this->assertEquals('BoardTestUpdated', $board->getName());
	}
	
	public function testDeleteBoard()
	{
		$board = $this->addNewBoard('DeleteBoardTest', 'Generic Description', 1);
		
		$boardId = $board->getId();
		$this->getBoardModel()->getManager()->deleteBoard($board);
		
		$foundBoard = $this->getBoardModel()->getRepository()->findOneBoardById($boardId);
		
		$this->assertNull($foundBoard);
	}
	
	public function testReassignTopicsToBoard()
	{
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		
		$board1 = $boards[0];
		$board2 = $boards[1];
		$topics = new ArrayCollection($board1->getTopics()->toArray());
		
		$this->assertCount(3, $board1->getTopics());
		$this->getBoardModel()->getManager()->reassignTopicsToBoard($topics, null);
		$this->em->refresh($board1);
		$this->assertCount(0, $board1->getTopics());
		
		$this->getBoardModel()->getManager()->reassignTopicsToBoard($topics, $board2);
		$this->em->refresh($board2);
		$this->assertCount(6, $board2->getTopics());
	}
}