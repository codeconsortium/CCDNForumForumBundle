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

namespace CCDNForum\ForumBundle\features\bootstrap;

//use Behat\Behat\Context\ClosuredContextInterface,
//    Behat\Behat\Context\TranslatedContextInterface,
//    Behat\Behat\Context\BehatContext,
//    Behat\Behat\Exception\PendingException;
//use Behat\Gherkin\Node\PyStringNode,
//    Behat\Gherkin\Node\TableNode;
//
//use Behat\MinkExtension\Context\RawMinkContext;
//
//use Behat\Symfony2Extension\Context\KernelAwareInterface;
//use Doctrine\Common\DataFixtures\Purger\ORMPurger;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpKernel\KernelInterface;



use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
//use Faker\Factory as FakerFactory;
//use Sylius\Bundle\AddressingBundle\Model\ZoneInterface;
//use Sylius\Bundle\CoreBundle\Entity\User;
//use Sylius\Bundle\ShippingBundle\Calculator\DefaultCalculators;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Locale\Locale;
use Symfony\Component\PropertyAccess\StringUtil;

use CCDNUser\UserBundle\Entity\User;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 *
 * Features context.
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class DataContext extends BehatContext implements KernelAwareInterface
{
    /**
     * 
     * Kernel.
     *
     * @var KernelInterface
     */
    protected $kernel;


    public function __construct()
    {
		
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * 
     * @Given /^there are following users defined:$/
     */
    public function thereAreFollowingUsersDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $this->thereIsUser(
				isset($data['username']) ? $data['username'] : sha1(uniqid(mt_rand(), true)),
                $data['email'],
                isset($data['password']) ? $data['password'] : 'password',
                isset($data['role']) ? $data['role'] : 'ROLE_USER',
                isset($data['enabled']) ? $data['enabled'] : true
            );
        }
    }

    public function thereIsUser($username, $email, $password, $role = 'ROLE_USER', $enabled = true)
    {
        $user = new User();

		$user->setUsername($username);
        $user->setEmail($email);
        $user->setEnabled($enabled);
		$user->setLocked(false);
        $user->setPlainPassword($password);

        if (null !== $role) {
            $user->addRole($role);
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    /**
     * 
     * @Given /^there are following forums defined:$/
     */
    public function thereAreFollowingForumsDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $this->thereIsForum(
				isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true))
            );
        }
    }

    public function thereIsForum($name)
    {
        $forum = new Forum();

		$forum->setName($name);

        $this->getEntityManager()->persist($forum);
        $this->getEntityManager()->flush();

        return $forum;
    }
	
    /**
     * 
     * @Given /^there are following categories defined:$/
     */
    public function thereAreFollowingCategoriesDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $index => $data) {
            $this->thereIsCategory(
				isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true)),
				isset($data['order']) ? $data['order'] : $index
            );
        }
    }

    public function thereIsCategory($name, $order)
    {
        $category = new Category();

		$category->setName($name);
		$category->setListOrderPriority($order);
		
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();

        return $category;
    }
	
    /**
     * 
     * @Given /^there are following boards defined:$/
     */
    public function thereAreFollowingBoardsDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $index => $data) {
            $this->thereIsBoard(
				isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true)),
				isset($data['description']) ? $data['description'] : sha1(uniqid(mt_rand(), true)),
				isset($data['order']) ? $data['order'] : $index
            );
        }
    }

    public function thereIsBoard($name, $description, $order)
    {
        $board = new Board();

		$board->setName($name);
        $board->setDescription($description);
		$board->setListOrderPriority($order);
		
        $this->getEntityManager()->persist($board);
        $this->getEntityManager()->flush();

        return $board;
    }
	
    /**
     * 
     * Get repository by resource name.
     *
     * @param string $resource
     *
     * @return ObjectRepository
     */
    public function getRepository($resource)
    {
        return $this->getService('sylius.repository.'.$resource);
    }

    /**
     * 
     * Get entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * 
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * 
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return $this->getContainer()->get($id);
    }
}