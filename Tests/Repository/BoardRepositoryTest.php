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
		$boards = $this->getBoardModel()->getRepository()->findAllBoards();
		
		// 3 Forums, with 3 categories each respectively, 3x3 = 9 Categories total.
		// 9 Categories x 3 Boards each respectively is 27
		$this->assertCount(27, $boards);
	}
	
	public function testFindOneBoardById()
	{
		$board = $this->addNewBoard('TestBoard', 'generic description', 1);
		
		$foundBoard = $this->getBoardModel()->getRepository()->findOneBoardById($board->getId());
		
		$this->assertNotNull($foundBoard);
		$this->assertEquals($foundBoard->getId(), $board->getId());
	}
}