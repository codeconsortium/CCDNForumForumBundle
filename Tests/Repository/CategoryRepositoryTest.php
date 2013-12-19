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
		$this->purge();
		$forum = $this->addFixturesForForums();
		$this->addFixturesForCategories($forum);
		$categoriesFound = $this->getCategoryModel()->findAllCategories();
		
		// 3 Forums, with 3 categories each respectively, 3x3 = 9 Categories total.
		$this->assertCount(9, $categoriesFound);
	}

	public function testFindAllCategoriesForForumById()
	{
		$this->purge();
		$forums = $this->addFixturesForForums();
		$this->addFixturesForCategories($forums);
		
		foreach ($forums as $forum) {
			$categoriesFound = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
	
			$this->assertCount(3, $categoriesFound);
		}
	}

	public function testFindAllCategoriesWithBoardsForForumByName()
	{
		$this->purge();
		$forum = $this->addNewForum('testFindAllCategoriesWithBoardsForForumByName');
		$categories = $this->addFixturesForCategories(array($forum));
		$this->addFixturesForBoards($categories);
		$foundCategories = $this->getCategoryModel()->findAllCategoriesWithBoardsForForumByName($forum->getName());

		$this->assertCount(3, $foundCategories);
		$this->assertCount(3, $foundCategories[0]->getBoards());
		$this->assertCount(3, $foundCategories[1]->getBoards());
		$this->assertCount(3, $foundCategories[2]->getBoards());
	}

	public function testFindOneCategoryById()
	{
		$this->purge();
		$forum = $this->addNewForum('testFindOneCategoryById');
		$category = $this->addNewCategory('testFindOneCategoryById', 1, $forum);
		$foundCategory = $this->getCategoryModel()->findOneCategoryById($category->getId());
		
		$this->assertEquals($foundCategory->getId(), $category->getId());
	}

	public function testFindOneCategoryByIdWithBoards()
	{
		$this->purge();
		$forum = $this->addNewForum('testFindOneCategoryByIdWithBoards');
		$category = $this->addNewCategory('testFindOneCategoryByIdWithBoards', 1, $forum);
		$foundCategory = $this->getCategoryModel()->findOneCategoryByIdWithBoards($category->getId());
		
		$this->assertEquals($foundCategory->getId(), $category->getId());
	}
}