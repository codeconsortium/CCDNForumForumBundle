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
	
	public function testFindAllCategoriesForForum()
	{
		$forums = $this->addFixturesForForums();
		$this->addFixturesForCategories($forums);

		foreach ($forums as $forum) {
			$categories = $this->getCategoryModel()->getRepository()->findAllCategoriesForForum($forum->getId());
	
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
}