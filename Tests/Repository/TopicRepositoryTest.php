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
use CCDNForum\ForumBundle\Entity\Topic;

class TopicRepositoryTest extends TestBase
{
	public function testFindOneTopicByIdWithBoardAndCategory()
	{
		$board = $this->addNewBoard('testFindOneTopicByIdWithBoardAndCategory', 'testFindOneTopicByIdWithBoardAndCategory', 1);

		// Can view deleted topics.
		$topic1 = $this->addNewTopic('topic1', $board);
		$this->em->persist($topic1);
		$this->em->flush($topic1);
		$this->em->refresh($topic1);
		$foundTopic1 = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($topic1->getId(), true);
		
		$this->assertNotNull($foundTopic1);
		$this->assertInstanceOf('CCDNForum\ForumBundle\Entity\Topic', $foundTopic1);
        
		// Can NOT view deleted topics.
		$topic2 = $this->addNewTopic('topic2', $board);
		$topic2->setIsDeleted(true);
		$this->em->persist($topic2);
		$this->em->flush();
		$this->em->refresh($topic2);
		$foundTopic2 = $this->getTopicModel()->getRepository()->findOneTopicByIdWithBoardAndCategory($topic2->getId(), false);
        
		$this->assertNull($foundTopic2);
	}
}