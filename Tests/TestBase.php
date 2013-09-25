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

namespace CCDNForum\ForumBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

use CCDNUser\UserBundle\Entity\User;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Subscription;

class TestBase extends \PHPUnit_Framework_TestCase //WebTestCase
{
    /**
	 *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
	
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
		
		$this->purge();
    }

    protected function purge()
    {
        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->purge();
	}

	protected function addNewUser($username, $email, $password)
	{
		$user = new User();
		
		$user->setUsername($username);
		$user->setEmail($email);
		$user->setPlainPassword($password);
		
		$this->em->persist($user);
		$this->em->flush();
		
		$this->em->refresh($user);
		
		return $user;
	}

	protected function addFixturesForUsers()
	{
		$userNames = array('admin', 'tom', 'dick', 'harry');
		$users = array();
		
		foreach ($userNames as $username) {
			$users[$username] = $this->addNewUser($username, $username . '@foobar.com', 'password');
		}
	
		return $users;
	}

	protected function addNewForum($forumName)
	{
		$forum = new Forum();
		
		$forum->setName($forumName);
		
		$this->em->persist($forum);
		$this->em->flush();

		$this->em->refresh($forum);
		
		return $forum;
	}

	protected function addFixturesForForums()
	{
		$forumNames = array('test_forum_1', 'test_forum_2', 'test_forum_3');
		$forums = array();
		
		foreach ($forumNames as $forumName) {
			$forums[] = $this->addNewForum($forumName);
		}
		
		return $forums;
	}

	protected function addNewCategory($categoryName, $order, Forum $forum = null)
	{
		$category = new Category();
		
		$category->setName($categoryName);
		$category->setListOrderPriority($order);
		$category->setForum($forum);
		
		$this->em->persist($category);
		$this->em->flush();
		
		$this->em->refresh($category);
		
		return $category;
	}

	protected function addFixturesForCategories($forums)
	{
		$categoryNames = array('test_category_1', 'test_category_2', 'test_category_3');
		$categories = array();
		
		foreach ($forums as $forum) {
			foreach ($categoryNames as $index => $categoryName) {
				$categories[] = $this->addNewCategory($categoryName, $index, $forum);
			}
		}
		
		return $categories;
	}

	protected function addNewBoard($boardName, $boardDescription, $order, Category $category = null)
	{
		$board = new Board();
		
		$board->setName($boardName);
		$board->setDescription($boardDescription);
		$board->setListOrderPriority($order);
		$board->setCategory($category);
		
		$this->em->persist($board);
		$this->em->flush();
		
		$this->em->refresh($board);
		
		return $board;
	}

	protected function addFixturesForBoards($categories)
	{
		$boardNames = array('test_board_1', 'test_board_2', 'test_board_3');
		$boards = array();
		
		foreach ($categories as $category) {
			foreach ($boardNames as $index => $boardName) {
				$boards[] = $this->addNewBoard($boardName, $boardName, $index, $category);
			}
		}
		
		return $boards;
	}

	protected function addNewTopic($title, Board $board = null)
	{
		$topic = new Topic();
		
		$topic->setTitle($title);
		$topic->setBoard($board);
		
		$this->em->persist($topic);
		$this->em->flush();
		
		$this->em->refresh($topic);
		
		return $topic;
	}

	protected function addFixturesForTopics($boards)
	{
		$topicTitles = array('test_topic_1', 'test_topic_2', 'test_topic_3');
		$topics = array();
		
		foreach ($boards as $board) {
			foreach ($topicTitles as $index => $topicTitle) {
				$topics[] = $this->addNewTopic($topicTitle, $board);
			}
		}
		
		return $topics;
	}

	protected function addNewPost($body, $topic, $user, \Datetime $createdDate = null)
	{
		$post = new Post();

		$post->setTopic($topic);
		$post->setBody($body);
        $post->setCreatedDate($createdDate ?: new \DateTime());
        $post->setCreatedBy($user);
        $post->setIsDeleted(false);
		
		$this->em->persist($post);
		$this->em->flush();
		
		$this->em->refresh($post);
		
		return $post;
	}

	protected function addFixturesForPosts($topics, $user)
	{
		$postBodies = array('test_post_1', 'test_post_2', 'test_post_3');
		$posts = array();
		
		foreach ($topics as $topicIndex => $topic) {
			foreach ($postBodies as $postIndex => $postBody) {
				$posts[] = $this->addNewPost($postBody, $topics[$topicIndex], $user, new \DateTime('now + ' . (int)(($topicIndex + 1) . ($postIndex + 1)) . ' minutes'));
				
				if ($postIndex == 0) {
					$topics[$topicIndex]->setFirstPost($posts[count($posts) - 1]);
				}
			}
			
			$topics[$topicIndex]->setLastPost($posts[count($posts) - 1]);
			
			$this->em->persist($topic);
		}
		
		$this->em->flush();
		
		return $posts;
	}

	protected function addNewSubscription($forum, $topic, $user, $isRead = false)
	{
		$subscription = new Subscription();

		$subscription->setTopic($topic);
        $subscription->setOwnedBy($user);
		$subscription->setForum($forum);
        $subscription->setIsRead($isRead);
        $subscription->setIsSubscribed(true);
		
		$this->em->persist($subscription);
		$this->em->flush();
		
		$this->em->refresh($subscription);
		
		return $subscription;
	}

	protected function addFixturesForSubscriptions($forum, $topics, $user, $isRead = false)
	{
		$subscriptions = array();
		
		foreach ($topics as $topic) {
			$subscriptions[] = $this->addNewSubscription($forum, $topic, $user, $isRead);
		}
		
		return $subscriptions;
	}

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\ForumModel $forumModel
     */
    private $forumModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\CategoryModel $categoryModel
     */
    private $categoryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\BoardModel $boardModel
     */
    private $boardModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\TopicModel $topicModel
     */
    private $topicModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\PostModel $postModel
     */
    private $postModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\RegistryModel $registryModel
     */
    private $registryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\Model\SubscriptionModel $subscriptionModel
     */
    private $subscriptionModel;

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\ForumModel
     */
    protected function getForumModel()
    {
        if (null == $this->forumModel) {
            $this->forumModel = $this->container->get('ccdn_forum_forum.model.forum');
        }

        return $this->forumModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\CategoryModel
     */
    protected function getCategoryModel()
    {
        if (null == $this->categoryModel) {
            $this->categoryModel = $this->container->get('ccdn_forum_forum.model.category');
        }

        return $this->categoryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\BoardModel
     */
    protected function getBoardModel()
    {
        if (null == $this->boardModel) {
            $this->boardModel = $this->container->get('ccdn_forum_forum.model.board');
        }

        return $this->boardModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\TopicModel
     */
    protected function getTopicModel()
    {
        if (null == $this->topicModel) {
            $this->topicModel = $this->container->get('ccdn_forum_forum.model.topic');
        }

        return $this->topicModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\PostModel
     */
    protected function getPostModel()
    {
        if (null == $this->postModel) {
            $this->postModel = $this->container->get('ccdn_forum_forum.model.post');
        }

        return $this->postModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\RegistryModel
     */
    protected function getRegistryModel()
    {
        if (null == $this->registryModel) {
            $this->registryModel = $this->container->get('ccdn_forum_forum.model.registry');
        }

        return $this->registryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\Model\SubscriptionModel
     */
    protected function getSubscriptionModel()
    {
        if (null == $this->subscriptionModel) {
            $this->subscriptionModel = $this->container->get('ccdn_forum_forum.model.subscription');
        }

        return $this->subscriptionModel;
    }
}