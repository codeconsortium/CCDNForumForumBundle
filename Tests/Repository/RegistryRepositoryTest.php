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

class RegistryRepositoryTest extends TestBase
{
	public function testFindOneRegistryForUserById()
	{
		$this->purge();
		$users = $this->addFixturesForUsers();
		$registry = $this->getRegistryModel()->createRegistry();
		$registry->setOwnedBy($users['tom']);
		$this->getRegistryModel()->saveRegistry($registry);
		$foundRegistry = $this->getRegistryModel()->findOneRegistryForUserById($users['tom']->getId());
		
		$this->assertNotNull($foundRegistry);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Registry', $foundRegistry);
	}
}
