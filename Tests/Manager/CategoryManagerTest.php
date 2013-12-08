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

class CategoryManagerTest extends TestBase
{
	public function testSaveCategory()
	{
		$this->purge();
		$category = $this->addNewCategory('NewCategoryTest', 1, null, false, false);
		$this->getCategoryModel()->saveCategory($category);
		
		$this->assertTrue(is_numeric($category->getId()));
		$this->assertSame('NewCategoryTest', $category->getName());
	}

	public function testUpdateCategory()
	{
		$this->purge();
		$category = $this->addNewCategory('UpdateCategoryTest', 1, null, true, true);
		$category->setName('CategoryTestUpdated');
		$this->getCategoryModel()->updateCategory($category);
		
		$this->assertTrue(is_numeric($category->getId()));
		$this->assertEquals('CategoryTestUpdated', $category->getName());
	}

	public function testDeleteCategory()
	{
		$this->purge();
		$category = $this->addNewCategory('DeleteCategoryTest', 1, null, true, true);
		$categoryId = $category->getId();
		$this->getCategoryModel()->deleteCategory($category);
		$foundCategory = $this->getCategoryModel()->findOneCategoryById($categoryId);
		
		$this->assertNull($foundCategory);
	}

	public function testReassignBoardsToCategory()
	{
		$this->purge();
		$forum = $this->addNewForum('testReassignBoardsToCategory');
		$categories = $this->addFixturesForCategories(array($forum));
		$boards = $this->addFixturesForBoards($categories);
		$category1 = $categories[0];
		$category2 = $categories[1];
		$boards = new ArrayCollection($category1->getBoards()->toArray());

		$this->assertCount(3, $category1->getBoards());
		$this->getCategoryModel()->reassignBoardsToCategory($boards, null);
		$this->em->refresh($category1);
		$this->assertCount(0, $category1->getBoards());
		
		$this->getCategoryModel()->reassignBoardsToCategory($boards, $category2);
		$this->em->refresh($category2);
		$this->assertCount(6, $category2->getBoards());
	}

	const REORDER_UP = 0;
	const REORDER_DOWN = 1;

	public function testReorderCategories()
	{
		$this->purge();
		$forum = $this->addNewForum('testReorderCategories');
		$this->addFixturesForCategories(array($forum));
		
		$this->em->refresh($forum);
		$categories = $forum->getCategories();
		$this->assertCount(3, $categories);

		// 123 - Initial order.
		$this->assertSame('test_category_1', $categories[0]->getName());
		$this->assertSame('test_category_2', $categories[1]->getName());
		$this->assertSame('test_category_3', $categories[2]->getName());
		
		// 123 -> 213
		$this->getCategoryModel()->reorderCategories($categories, $categories[0], $this::REORDER_DOWN);
		$categories = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
		$this->assertSame('test_category_2', $categories[0]->getName());
		$this->assertSame('test_category_1', $categories[1]->getName());
		$this->assertSame('test_category_3', $categories[2]->getName());

		// 213 -> 231
		$this->getCategoryModel()->reorderCategories($categories, $categories[1], $this::REORDER_DOWN);
		$categories = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
		$this->assertSame('test_category_2', $categories[0]->getName());
		$this->assertSame('test_category_3', $categories[1]->getName());
		$this->assertSame('test_category_1', $categories[2]->getName());

		// 231 -> 123
		$this->getCategoryModel()->reorderCategories($categories, $categories[2], $this::REORDER_DOWN);
		$categories = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
		$this->assertSame('test_category_1', $categories[0]->getName());
		$this->assertSame('test_category_2', $categories[1]->getName());
		$this->assertSame('test_category_3', $categories[2]->getName());
		
		// 123 <- 231
		$this->getCategoryModel()->reorderCategories($categories, $categories[0], $this::REORDER_UP);
		$categories = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
		$this->assertSame('test_category_2', $categories[0]->getName());
		$this->assertSame('test_category_3', $categories[1]->getName());
		$this->assertSame('test_category_1', $categories[2]->getName());
		
		// 231 <- 213
		$this->getCategoryModel()->reorderCategories($categories, $categories[2], $this::REORDER_UP);
		$categories = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
		$this->assertSame('test_category_2', $categories[0]->getName());
		$this->assertSame('test_category_1', $categories[1]->getName());
		$this->assertSame('test_category_3', $categories[2]->getName());
		
		// 213 <- 123
		$this->getCategoryModel()->reorderCategories($categories, $categories[1], $this::REORDER_UP);
		$categories = $this->getCategoryModel()->findAllCategoriesForForumById($forum->getId());
		$this->assertSame('test_category_1', $categories[0]->getName());
		$this->assertSame('test_category_2', $categories[1]->getName());
		$this->assertSame('test_category_3', $categories[2]->getName());
	}
}