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

namespace CCDNForum\ForumBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardControllerTest extends WebTestCase
{
		
	
	
    /**
	 *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
	
	/**
	 *
	 * @var $container
	 */
	private $container;
	
	/**
	 *
	 * @access public
	 */
    public function setUp()
    {
        $kernel = static::createKernel();

        $kernel->boot();
		
		$this->container = $kernel->getContainer();

        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }
	
	public function testBoardShow()
	{
		$client = static::createClient();
		$client->followRedirects();
		
        $query = $this->em->createQuery('
                SELECT b
                FROM CCDNForumForumBundle:Board b
                WHERE b.name = :name
            ')
			->setParameter('name', 'introductions');

        try {
            $board = $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
			throw new \Exception('Could not retrieve "introductions" board!');
			
			return;
        }
		
		$crawler = $client->request('GET', '/en/forum/board/' . $board->getId());
		
		$this->assertGreaterThan(0, $crawler->filter('a:contains("General")')->count());
		$this->assertGreaterThan(0, $crawler->filter('a:contains("Introductions")')->count());
		$this->assertGreaterThan(0, $crawler->filter('a:contains("Welcome Topic")')->count());

	}
}