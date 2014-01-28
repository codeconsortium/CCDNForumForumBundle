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
use CCDNForum\ForumBundle\Tests\Functional\src\Entity\User;

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
     * @param string $serviceName
     *
     * @return object
     */
    protected function getService($serviceName)
    {
        return $this->getContainer()->get($serviceName);
    }

    protected $users = array();

    /**
     *
     * @Given /^there are following users defined:$/
     */
    public function thereAreFollowingUsersDefined(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->users[] = $this->thereIsUser(
                $data['email'],
                $data['email'],
                isset($data['password']) ? $data['password'] : 'password',
                isset($data['role']) ? $data['role'] : 'ROLE_USER',
                isset($data['enabled']) ? $data['enabled'] : true
            );
        }

        $this->getEntityManager()->flush();
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

        return $user;
    }

    protected $forums = array();

    /**
     *
     * @Given /^there are following forums defined:$/
     */
    public function thereAreFollowingForumsDefined(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->forums[] = $this->thereIsForum(
                isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true))
            );
        }

        $this->getEntityManager()->flush();
    }

    public function thereIsForum($name)
    {
        $forum = $this->getForumModel()->createForum();

        $forum->setName($name);

        $this->getEntityManager()->persist($forum);

        return $forum;
    }

    protected $categories = array();

    /**
     *
     * @Given /^there are following categories defined:$/
     */
    public function thereAreFollowingCategoriesDefined(TableNode $table)
    {
        foreach ($table->getHash() as $index => $data) {
            $this->categories[] = $this->thereIsCategory(
                isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true)),
                isset($data['order']) ? $data['order'] : $index,
                isset($data['forum']) ? $data['forum'] : null
            );
        }

        $this->getEntityManager()->flush();
    }

    public function thereIsCategory($name, $order, $forumName = null)
    {
        $category = $this->getCategoryModel()->createCategory();

        $category->setName($name);
        $category->setListOrderPriority($order);

        foreach ($this->forums as $forum) {
            if ($forum->getName() == $forumName) {
                $category->setForum($forum);
            }
        }

        $this->getEntityManager()->persist($category);

        return $category;
    }

    protected $boards = array();

    /**
     *
     * @Given /^there are following boards defined:$/
     */
    public function thereAreFollowingBoardsDefined(TableNode $table)
    {
        foreach ($table->getHash() as $index => $data) {
            $this->boards[] = $this->thereIsBoard(
                isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true)),
                isset($data['description']) ? $data['description'] : sha1(uniqid(mt_rand(), true)),
                isset($data['order']) ? $data['order'] : $index,
                isset($data['category']) ? $data['category'] : null
            );
        }

        $this->getEntityManager()->flush();
    }

    public function thereIsBoard($name, $description, $order, $categoryName = null)
    {
        $board = $this->getBoardModel()->createBoard();

        $board->setName($name);
        $board->setDescription($description);
        $board->setListOrderPriority($order);

        foreach ($this->categories as $category) {
            if ($category->getName() == $categoryName) {
                $board->setCategory($category);
            }
        }

        $this->getEntityManager()->persist($board);

        return $board;
    }

    protected $topics = array();

    /**
     *
     * @Given /^there are following topics defined:$/
     */
    public function thereAreFollowingTopicsDefined(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->topics[] = $this->thereIsTopic(
                isset($data['title']) ? $data['title'] : sha1(uniqid(mt_rand(), true)),
                isset($data['body']) ? $data['body'] : sha1(uniqid(mt_rand(), true)),
                isset($data['board']) ? $data['board'] : null,
                isset($data['user']) ? $data['user'] : null,
                isset($data['subscribed']) ? $data['subscribed'] : false
            );
        }

        $this->getEntityManager()->flush();
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

        $topic = $this->getTopicModel()->createTopic();
        $topic->setTitle($title);
        $topic->setBoard($board);

        $post = $this->getPostModel()->createPost();
        $post->setBody($body);
        $post->setCreatedDate(new \DateTime('now'));
        $post->setCreatedBy($user);
        $post->setTopic($topic);

        $topic->setFirstPost($post);
        $topic->setLastPost($post);

        if ($subscribed) {
            $subscription = $this->getSubscriptionModel()->createSubscription();
            $subscription->setForum($board->getCategory()->getForum());
            $subscription->setTopic($topic);
            $subscription->setOwnedBy($user);
            $subscription->setRead(false);
            $subscription->setSubscribed(true);

            $this->getEntityManager()->persist($subscription);
        }

        $this->getEntityManager()->persist($topic);

        return $topic;
    }

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     */
    private $forumModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel $categoryModel
     */
    private $categoryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     */
    private $boardModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     */
    private $topicModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
     */
    private $postModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel $registryModel
     */
    private $registryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel $subscriptionModel
     */
    private $subscriptionModel;

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\ForumModel
     */
    protected function getForumModel()
    {
        if (null == $this->forumModel) {
            $this->forumModel = $this->getService('ccdn_forum_forum.model.forum');
        }

        return $this->forumModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel
     */
    protected function getCategoryModel()
    {
        if (null == $this->categoryModel) {
            $this->categoryModel = $this->getService('ccdn_forum_forum.model.category');
        }

        return $this->categoryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\BoardModel
     */
    protected function getBoardModel()
    {
        if (null == $this->boardModel) {
            $this->boardModel = $this->getService('ccdn_forum_forum.model.board');
        }

        return $this->boardModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\TopicModel
     */
    protected function getTopicModel()
    {
        if (null == $this->topicModel) {
            $this->topicModel = $this->getService('ccdn_forum_forum.model.topic');
        }

        return $this->topicModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\PostModel
     */
    protected function getPostModel()
    {
        if (null == $this->postModel) {
            $this->postModel = $this->getService('ccdn_forum_forum.model.post');
        }

        return $this->postModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel
     */
    protected function getRegistryModel()
    {
        if (null == $this->registryModel) {
            $this->registryModel = $this->getService('ccdn_forum_forum.model.registry');
        }

        return $this->registryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel
     */
    protected function getSubscriptionModel()
    {
        if (null == $this->subscriptionModel) {
            $this->subscriptionModel = $this->getService('ccdn_forum_forum.model.subscription');
        }

        return $this->subscriptionModel;
    }
}
