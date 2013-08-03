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
		$forums = $this->getForumModel()->getRepository()->findAllForums();
		
		$this->assertCount(3, $forums);
	}
	
	public function testFindOneForumById()
	{
		$forum = $this->addNewForum('TestForumById');
		
		$foundForum = $this->getForumModel()->getRepository()->findOneForumById($forum->getId());
		
		$this->assertNotNull($foundForum);
		$this->assertEquals($foundForum->getId(), $forum->getId());
	}

    public function testFindOneForumByName()
    {
		$forum = $this->addNewForum('TestForumByName');
		
		$foundForum = $this->getForumModel()->getRepository()->findOneForumByName($forum->getName());
		
		$this->assertNotNull($foundForum);
		$this->assertEquals($foundForum->getId(), $forum->getId());
		$this->assertEquals($foundForum->getName(), $forum->getName());
    }
}