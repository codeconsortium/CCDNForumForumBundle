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
use CCDNForum\ForumBundle\Entity\Category;

class CategoryManagerTest extends TestBase
{
	public function testSaveNewCategory()
	{
		$category = new Category();
		$category->setName('NewCategoryTest');
		$category->setListOrderPriority(1);
		
		$this->getCategoryModel()->getManager()->saveNewCategory($category);
		
		//$this->em->refresh($category);
		
		$this->assertTrue(is_numeric($category->getId()));
		$this->assertSame('NewCategoryTest', $category->getName());
	}
	
	public function testUpdateCategory()
	{
		$category = $this->addNewCategory('UpdateCategoryTest', 1);
		
		$category->setName('CategoryTestUpdated');
		
		$this->getCategoryModel()->getManager()->updateCategory($category);
		
		$this->assertTrue(is_numeric($category->getId()));
		$this->assertEquals('CategoryTestUpdated', $category->getName());
	}
	
	public function testDeleteCategory()
	{
		$category = $this->addNewCategory('DeleteCategoryTest', 1);
		
		$categoryId = $category->getId();
		$this->getCategoryModel()->getManager()->deleteCategory($category);
		
		$foundCategory = $this->getCategoryModel()->getRepository()->findOneCategoryById($categoryId);
		
		$this->assertNull($foundCategory);
	}
	
	public function testReassignBoardsToCategory()
	{
		$forums = $this->addFixturesForForums();
		$categories = $this->addFixturesForCategories($forums);
		$boards = $this->addFixturesForBoards($categories);
		
		$category1 = $categories[0];
		$category2 = $categories[1];
		$boards = new ArrayCollection($category1->getBoards()->toArray());
		
		$this->assertCount(3, $category1->getBoards());
		$this->getCategoryModel()->getManager()->reassignBoardsToCategory($boards, null);
		$this->em->refresh($category1);
		$this->assertCount(0, $category1->getBoards());
		
		$this->getCategoryModel()->getManager()->reassignBoardsToCategory($boards, $category2);
		$this->em->refresh($category2);
		$this->assertCount(3, $category2->getBoards());
	}
}