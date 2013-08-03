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
use CCDNForum\ForumBundle\Entity\Forum;

class ForumManagerTest extends TestBase
{
	public function testSaveNewForum()
	{
		$forum = new Forum();
		$forum->setName('NewForumTest');
		
		$this->getForumModel()->getManager()->saveNewForum($forum);
		
		$this->assertTrue(is_numeric($forum->getId()));
		$this->assertSame('NewForumTest', $forum->getName());
	}
	
	public function testUpdateForum()
	{
		$forum = $this->addNewForum('TestUpdateForum');
		
		$forum->setName('TestForumUpdated');
		
		$this->getForumModel()->getManager()->updateForum($forum);
		
		$this->assertTrue(is_numeric($forum->getId()));
		$this->assertEquals('TestForumUpdated', $forum->getName());
	}
	
	public function testDeleteForum()
	{
		$forum = $this->addNewForum('FooBar');
		
		$forumId = $forum->getId();
		$this->getForumModel()->getManager()->deleteForum($forum);
		
		$foundForum = $this->getForumModel()->getRepository()->findOneForumById($forumId);
		
		$this->assertNull($foundForum);
	}
	
	public function testReassignCategoriesToForum()
	{
		$forums = array();
		$forums[0] = $this->addNewForum('testReassignCategoriesToForum0');
		$forums[1] = $this->addNewForum('testReassignCategoriesToForum1');
		$this->addFixturesForCategories($forums);
		
		$forum1 = $forums[0];
		$forum2 = $forums[1];
		$this->em->refresh($forum1);
		$this->em->refresh($forum2);
		$categories = new ArrayCollection($forum1->getCategories()->toArray());

		$this->assertCount(3, $forum1->getCategories());
		$this->getForumModel()->getManager()->reassignCategoriesToForum($categories, null);
		$this->em->refresh($forum1);
		$this->assertCount(0, $forum1->getCategories());
		
		$this->getForumModel()->getManager()->reassignCategoriesToForum($categories, $forum2);
		$this->em->refresh($forum2);
		$this->assertCount(6, $forum2->getCategories());
	}
}