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

class CategoryRepositoryTest extends TestBase
{
	public function testFindAllCategories()
	{
		$categories = $this->getCategoryModel()->getRepository()->findAllCategories();
		
		// 3 Forums, with 3 categories each respectively, 3x3 = 9 Categories total.
		$this->assertCount(9, $categories);
	}

	public function testFindAllCategoriesForForumById()
	{
		foreach ($this->forums as $forum) {
			$categories = $this->getCategoryModel()->getRepository()->findAllCategoriesForForumById($forum->getId());
	
			$this->assertCount(3, $categories);
		}
	}

	public function testFindOneCategoryById()
	{
		$category = $this->addNewCategory('TestCategory', 1);
		
		$foundCategory = $this->getCategoryModel()->getRepository()->findOneCategoryById($category->getId());
		
		$this->assertNotNull($foundCategory);
		$this->assertEquals($foundCategory->getId(), $category->getId());
	}

	public function testFindOneCategoryByIdWithBoards()
	{
		$category = $this->addNewCategory('testFindOneCategoryByIdWithBoards', 1);
		
		$foundCategory = $this->getCategoryModel()->getRepository()->findOneCategoryByIdWithBoards($category->getId());
		
		$this->assertNotNull($foundCategory);
		$this->assertEquals($foundCategory->getId(), $category->getId());
	}

	public function testFindAllCategoriesWithBoardsForForumByName()
	{
		$forum = $this->addNewForum('testFindAllCategoriesWithBoardsForForumByName1');
		$categories = $this->addFixturesForCategories(array($forum));
		$boards = $this->addFixturesForBoards($categories);
		
		$foundCategories = $this->getCategoryModel()->getRepository()->findAllCategoriesWithBoardsForForumByName($forum->getName());
			
		$this->assertNotNull($foundCategories);
		$this->assertCount(3, $foundCategories);
		$this->assertCount(3, $foundCategories[0]->getBoards());
		$this->assertCount(3, $foundCategories[1]->getBoards());
		$this->assertCount(3, $foundCategories[2]->getBoards());
	}
}