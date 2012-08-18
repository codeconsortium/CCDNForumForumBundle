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

namespace CCDNForum\ForumBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
		//
		// Category.
		//
		$category = new Category();
		
		$category->setName('General');
		$category->setListOrderPriority(1);
		
		$manager->persist($category);
		$manager->flush();
		$manager->refresh($category);

		//
		// Board.
		//
		$board = new Board();		
		
		$board->setCategory($category);
		$board->setName('Introductions');
		$board->setDescription('Say hello and introduce yoursel!\nTell us a little about yourself.');
		$board->setCachedTopicCount(1);
		$board->setCachedPostCount(1);
		$board->setListOrderPriority(1);
		
		$manager->persist($board);
		$manager->flush();
		$manager->refresh($board);
		
		//
		// Topic.
		//
		$topic = new Topic();
		
		$topic->setBoard($board);
		$topic->setTitle('Welcome Topic.');
		$topic->setCachedViewCount(0);
		$topic->setCachedReplyCount(0);
		$topic->setIsSticky(false);
		$topic->setIsClosed(false);
		$topic->setIsDeleted(false);
				
		$manager->persist($topic);
		$manager->flush();
		$manager->refresh($topic);
		
		//
		// Post.
		//
		$post = new Post();
		
		$post->setTopic($topic);
		$post->setCreatedBy($manager->merge($this->getReference('user-admin')));
		$post->setCreatedDate(new \DateTime());
		$post->setIsDeleted(false);
		$post->setIsLocked(false);
		$post->setBody('Welcome to the CodeConsortium Forum. Please introduce yourself and make yourself at home. :)');

		$manager->persist($post);
		$manager->flush();
		$manager->refresh($post);
		
		//
		// Set last post references.
		//
		$topic->setFirstPost($post);
		$topic->setLastPost($post);
		$board->setLastPost($post);

		$manager->persist($topic, $board);
		$manager->flush();
		
		$this->addReference('forum-post', $post);
    }

	public function getOrder()
	{
		return 4;
	}
	
}
