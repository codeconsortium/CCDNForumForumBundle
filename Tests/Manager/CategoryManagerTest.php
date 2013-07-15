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
//	
//	public function testDeleteForum()
//	{
//		$forum = $this->addNewForum('FooBar');
//		
//		$this->getForumModel()->getManager()->deleteForum($forum);
//		
//		$foundForum = $this->getForumModel()->getRepository()->findOneForumById($forum->getId());
//		
//		$this->assertNull($foundForum);
//	}
//	
//	public function testReassignCategoriesToForum()
//	{
//		$forums = $this->addFixturesForForums();
//		$this->addFixturesForCategories($forums);
//		
//		$forum1 = $forums[0];
//		$forum2 = $forums[1];
//		$categories = new ArrayCollection($forum1->getCategories()->toArray());
//		
//		$this->assertCount(3, $forum1->getCategories());
//		$this->getForumModel()->getManager()->reassignCategoriesToForum($categories, null);
//		$this->em->refresh($forum1);
//		$this->assertCount(0, $forum1->getCategories());
//		
//		$this->getForumModel()->getManager()->reassignCategoriesToForum($categories, $forum2);
//		$this->em->refresh($forum2);
//		$this->assertCount(3, $forum2->getCategories());
//	}
}