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

class BoardRepositoryTest extends TestBase
{
	public function testFindAllBoards()
	{
		$this->purge();
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$this->addFixturesForBoards($categories);
		$boardsFound = $this->getBoardModel()->findAllBoards();
		
		// 3 Forums, with 3 categories each respectively, 3x3 = 9 Categories total.
		// 9 Categories x 3 Boards each respectively is 27
		$this->assertCount(27, $boardsFound);
	}

	public function testFindAllBoardsForCategoryById()
	{
		$this->purge();
		$forum = $this->addNewForum('testFindAllBoardsForCategoryById');
		$categories = $this->addFixturesForCategories(array($forum));
		$boards = $this->addFixturesForBoards($categories);
		
		foreach ($categories as $category) {
			$boards = $this->getBoardModel()->findAllBoardsForCategoryById($category->getId());
	
			$this->assertCount(3, $boards);
		}
	}

	public function testFindAllBoardsForForumById()
	{
		$this->purge();
		$forum = $this->addNewForum('testFindAllBoardsForForumById');
		$categories = $this->addFixturesForCategories(array($forum));
		$this->addFixturesForBoards($categories);
		$foundBoards = $this->getBoardModel()->findAllBoardsForForumById($forum->getId());
	
		$this->assertCount(9, $foundBoards);
	}

	public function testFindOneBoardById()
	{
		$this->purge();
		$board = $this->addNewBoard('TestBoard', 'generic description', 1);
		$foundBoard = $this->getBoardModel()->findOneBoardById($board->getId());
		
		$this->assertEquals($foundBoard->getId(), $board->getId());
	}

	public function testFindOneBoardByIdWithCategory()
	{
		$this->purge();
		$category = $this->addNewCategory('TestCategory', 1);
		$board = $this->addNewBoard('TestBoard', 'generic description', 1);
		$board->setCategory($category);
		$this->em->persist($board);
		$this->em->flush();
		$foundBoard = $this->getBoardModel()->findOneBoardByIdWithCategory($board->getId());
		
		$this->assertEquals($foundBoard->getId(), $board->getId());
		$this->assertNotNull($foundBoard->getCategory()->getId());
		$this->assertEquals($category->getId(), $foundBoard->getCategory()->getId());
	}

	public function testGetBoardCount()
	{
		$this->purge();
		$forum = $this->addNewForum('TestForum');
		$categories = $this->addFixturesForCategories(array($forum));
		$this->addFixturesForBoards($categories);
		$count = $this->getBoardModel()->getBoardCount();
		
		$this->assertSame(9, (int) $count);
	}
}
