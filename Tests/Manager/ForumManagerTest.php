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

class ForumManagerTest extends TestBase
{
	public function testSaveForum()
	{
		$this->purge();
		$forum = $this->addNewForum('NewForumTest', false, false);
		$this->getForumModel()->saveForum($forum);
		
		$this->assertTrue(is_numeric($forum->getId()));
		$this->assertSame('NewForumTest', $forum->getName());
	}

	public function testUpdateForum()
	{
		$this->purge();
		$forum = $this->addNewForum('TestUpdateForum', true, true);
		$forum->setName('TestForumUpdated');
		$this->getForumModel()->updateForum($forum);
		
		$this->assertTrue(is_numeric($forum->getId()));
		$this->assertEquals('TestForumUpdated', $forum->getName());
	}

	public function testDeleteForum()
	{
		$this->purge();
		$forum = $this->addNewForum('FooBar', true, true);
		$forumId = $forum->getId();
		$this->getForumModel()->deleteForum($forum);
		$foundForum = $this->getForumModel()->findOneForumById($forumId);
		
		$this->assertNull($foundForum);
	}

	public function testReassignCategoriesToForum()
	{
		$this->purge();
		$forums = array();
		$forums[0] = $this->addNewForum('testReassignCategoriesToForum0', true, true);
		$forums[1] = $this->addNewForum('testReassignCategoriesToForum1', true, true);
		$this->addFixturesForCategories($forums);
		$forum1 = $forums[0];
		$forum2 = $forums[1];
		$this->em->refresh($forum1);
		$this->em->refresh($forum2);
		$categories = new ArrayCollection($forum1->getCategories()->toArray());

		$this->assertCount(3, $forum1->getCategories());
		$this->getForumModel()->reassignCategoriesToForum($categories, null);
		$this->em->refresh($forum1);
		$this->assertCount(0, $forum1->getCategories());
		
		$this->getForumModel()->reassignCategoriesToForum($categories, $forum2);
		$this->em->refresh($forum2);
		$this->assertCount(6, $forum2->getCategories());
	}
}