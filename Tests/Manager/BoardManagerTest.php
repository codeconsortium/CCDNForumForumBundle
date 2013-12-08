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

class BoardManagerTest extends TestBase
{
	public function testSaveBoard()
	{
		$this->purge();
		$board = $this->addNewBoard('NewBoardTest', 'Generic description', 1, null, false, false);
		$this->getBoardModel()->saveBoard($board);
		
		$this->assertTrue(is_numeric($board->getId()));
		$this->assertSame('NewBoardTest', $board->getName());
	}

	public function testUpdateBoard()
	{
		$this->purge();
		$board = $this->addNewBoard('UpdateBoardTest', 'Generic Description', 1, null, true, true);
		$board->setName('BoardTestUpdated');
		$this->getBoardModel()->updateBoard($board);
		
		$this->assertTrue(is_numeric($board->getId()));
		$this->assertEquals('BoardTestUpdated', $board->getName());
	}

	public function testDeleteBoard()
	{
		$this->purge();
		$board = $this->addNewBoard('DeleteBoardTest', 'Generic Description', 1, null, true, true);
		$boardId = $board->getId();
		$this->getBoardModel()->deleteBoard($board);
		$foundBoard = $this->getBoardModel()->findOneBoardById($boardId);
		
		$this->assertNull($foundBoard);
	}

	public function testReassignTopicsToBoard()
	{
		$this->purge();
		$forum = $this->addNewForum('testReassignTopicsToBoard');
		$categories = $this->addFixturesForCategories(array($forum));
		$boards = $this->addFixturesForBoards($categories);
		$topics = $this->addFixturesForTopics($boards);
		$board1 = $boards[0];
		$board2 = $boards[1];
		$topics = new ArrayCollection($board1->getTopics()->toArray());
		
		$this->assertCount(3, $board1->getTopics());
		$this->getBoardModel()->reassignTopicsToBoard($topics, null);
		$this->em->refresh($board1);
		$this->assertCount(0, $board1->getTopics());
		
		$this->getBoardModel()->reassignTopicsToBoard($topics, $board2);
		$this->em->refresh($board2);
		$this->assertCount(6, $board2->getTopics());
	}

	const REORDER_UP = 0;
	const REORDER_DOWN = 1;

	public function testReorderBoards()
	{
		$this->purge();
		$forum = $this->addNewForum('testReorderBoards');
		$categories = $this->addFixturesForCategories(array($forum));
		$this->addFixturesForBoards($categories);
		
		$category = $categories[count($categories) - 1];
		$this->em->refresh($category);
		$boards = $category->getBoards();
		$this->assertCount(3, $boards);

		// 123 - Initial order.
		$this->assertSame('test_board_1', $boards[0]->getName());
		$this->assertSame('test_board_2', $boards[1]->getName());
		$this->assertSame('test_board_3', $boards[2]->getName());
		
		// 123 -> 213
		$this->getBoardModel()->reorderBoards($boards, $boards[0], $this::REORDER_DOWN);
		$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
		$this->assertSame('test_board_2', $boards[0]->getName());
		$this->assertSame('test_board_1', $boards[1]->getName());
		$this->assertSame('test_board_3', $boards[2]->getName());

		// 213 -> 231
		$this->getBoardModel()->reorderBoards($boards, $boards[1], $this::REORDER_DOWN);
		$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
		$this->assertSame('test_board_2', $boards[0]->getName());
		$this->assertSame('test_board_3', $boards[1]->getName());
		$this->assertSame('test_board_1', $boards[2]->getName());

		// 231 -> 123
		$this->getBoardModel()->reorderBoards($boards, $boards[2], $this::REORDER_DOWN);
		$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
		$this->assertSame('test_board_1', $boards[0]->getName());
		$this->assertSame('test_board_2', $boards[1]->getName());
		$this->assertSame('test_board_3', $boards[2]->getName());
		
		// 123 <- 231
		$this->getBoardModel()->reorderBoards($boards, $boards[0], $this::REORDER_UP);
		$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
		$this->assertSame('test_board_2', $boards[0]->getName());
		$this->assertSame('test_board_3', $boards[1]->getName());
		$this->assertSame('test_board_1', $boards[2]->getName());
		
		// 231 <- 213
		$this->getBoardModel()->reorderBoards($boards, $boards[2], $this::REORDER_UP);
		$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
		$this->assertSame('test_board_2', $boards[0]->getName());
		$this->assertSame('test_board_1', $boards[1]->getName());
		$this->assertSame('test_board_3', $boards[2]->getName());
		
		// 213 <- 123
		$this->getBoardModel()->reorderBoards($boards, $boards[1], $this::REORDER_UP);
		$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
		$this->assertSame('test_board_1', $boards[0]->getName());
		$this->assertSame('test_board_2', $boards[1]->getName());
		$this->assertSame('test_board_3', $boards[2]->getName());
	}
}