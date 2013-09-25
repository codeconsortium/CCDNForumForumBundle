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

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

//use CCDNUser\UserBundle\Entity\User;
use CCDNForum\ForumBundle\Tests\Functional\src\Entity\User;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Subscription;

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

    protected $users = array();

    /**
     *
     * @Given /^there are following users defined:$/
     */
    public function thereAreFollowingUsersDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $this->users[] = $this->thereIsUser(
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
        $user->setPlainPassword($password);

        if (null !== $role) {
            $user->addRole($role);
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    protected $forums = array();

    /**
     *
     * @Given /^there are following forums defined:$/
     */
    public function thereAreFollowingForumsDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $this->forums[] = $this->thereIsForum(
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

    protected $categories = array();

    /**
     *
     * @Given /^there are following categories defined:$/
     */
    public function thereAreFollowingCategoriesDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $index => $data) {
            $this->categories[] = $this->thereIsCategory(
                isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true)),
                isset($data['order']) ? $data['order'] : $index,
                isset($data['forum']) ? $data['forum'] : null
            );
        }
    }

    public function thereIsCategory($name, $order, $forumName = null)
    {
        $category = new Category();

        $category->setName($name);
        $category->setListOrderPriority($order);

        foreach ($this->forums as $forum) {
            if ($forum->getName() == $forumName) {
                $category->setForum($forum);
            }
        }

        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();

        return $category;
    }

    protected $boards = array();

    /**
     *
     * @Given /^there are following boards defined:$/
     */
    public function thereAreFollowingBoardsDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $index => $data) {
            $this->boards[] = $this->thereIsBoard(
                isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true)),
                isset($data['description']) ? $data['description'] : sha1(uniqid(mt_rand(), true)),
                isset($data['order']) ? $data['order'] : $index,
                isset($data['category']) ? $data['category'] : null
            );
        }
    }

    public function thereIsBoard($name, $description, $order, $categoryName = null)
    {
        $board = new Board();

        $board->setName($name);
        $board->setDescription($description);
        $board->setListOrderPriority($order);

        foreach ($this->categories as $category) {
            if ($category->getName() == $categoryName) {
                $board->setCategory($category);
            }
        }

        $this->getEntityManager()->persist($board);
        $this->getEntityManager()->flush();

        return $board;
    }

    protected $topics = array();

    /**
     *
     * @Given /^there are following topics defined:$/
     */
    public function thereAreFollowingTopicsDefined(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $index => $data) {
            $this->topics[] = $this->thereIsTopic(
                isset($data['title']) ? $data['title'] : sha1(uniqid(mt_rand(), true)),
                isset($data['body']) ? $data['body'] : sha1(uniqid(mt_rand(), true)),
                isset($data['board']) ? $data['board'] : null,
                isset($data['user']) ? $data['user'] : null,
                isset($data['subscribed']) ? $data['subscribed'] : false
            );
        }
    }

    public function thereIsTopic($title, $body, $boardName, $userEmail, $subscribed = false)
    {
        $user = null;

        foreach ($this->users as $userScan) {
            if ($userScan->getEmail() == $userEmail) {
                $user = $userScan;
            }
        }

        $board = null;

        foreach ($this->boards as $boardScan) {
            if ($boardScan->getName() == $boardName) {
                $board = $boardScan;
            }
        }

        $topic = new Topic();
        $topic->setTitle($title);
        $topic->setBoard($board);

        $post = new Post();
        $post->setBody($body);
        $post->setCreatedDate(new \DateTime('now'));
        $post->setCreatedBy($user);
        $post->setTopic($topic);

        $topic->setFirstPost($post);
        $topic->setLastPost($post);

        if ($subscribed) {
            $subscription = new Subscription();
            $subscription->setForum($board->getCategory()->getForum());
            $subscription->setTopic($topic);
            $subscription->setOwnedBy($user);
            $subscription->setIsRead(false);
            $subscription->setIsSubscribed(true);

            $this->getEntityManager()->persist($subscription);
        }

        $this->getEntityManager()->persist($topic);
        $this->getEntityManager()->flush();

        return $topic;
    }
}
