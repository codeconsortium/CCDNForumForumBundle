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

class ForumRepositoryTest extends TestBase
{
	public function testFindAllForums()
	{
		$this->purge();
		$this->addFixturesForForums();
		$forums = $this->getForumModel()->findAllForums();
		
		$this->assertCount(3, $forums);
	}

	public function testFindOneForumById()
	{
		$this->purge();
		$forum = $this->addNewForum('TestForumById');
		$foundForum = $this->getForumModel()->findOneForumById($forum->getId());
		
		$this->assertEquals($foundForum->getId(), $forum->getId());
	}

    public function testFindOneForumByName()
    {
		$this->purge();
		$forum = $this->addNewForum('TestForumByName');
		$foundForum = $this->getForumModel()->findOneForumByName($forum->getName());
		
		$this->assertEquals($foundForum->getId(), $forum->getId());
		$this->assertEquals($foundForum->getName(), $forum->getName());
    }
}