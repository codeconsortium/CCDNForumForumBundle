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
use CCDNForum\ForumBundle\Tests\Functional\src\Entity\User;
use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Subscription;

class TestBase extends WebTestCase
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

	/*
     *
     * Close doctrine connections to avoid having a 'too many connections'
     * message when running many tests
     */
	public function tearDown(){
		$this->container->get('doctrine')->getConnection()->close();
	
		parent::tearDown();
	}

    protected function purge()
    {
        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->purge();
	}

	protected function addNewUser($username, $email, $password, $persist = true, $andFlush = true)
	{
		$user = new User();
		$user->setUsername($username);
		$user->setEmail($email);
		$user->setPlainPassword($password);
		
		if ($persist) {
			$this->em->persist($user);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($user);
			}
		}
		
		return $user;
	}

	protected function addFixturesForUsers()
	{
		$userNames = array('admin', 'tom', 'dick', 'harry');
		$users = array();
		foreach ($userNames as $username) {
			$users[$username] = $this->addNewUser($username, $username . '@foobar.com', 'password', true, false);
		}

		$this->em->flush();
	
		return $users;
	}

	protected function addNewForum($forumName, $persist = true, $andFlush = true)
	{
		$forum = $this->getForumModel()->createForum();
		$forum->setName($forumName);
		
		if ($persist) {
			$this->em->persist($forum);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($forum);
			}
		}
		
		return $forum;
	}

	protected function addFixturesForForums()
	{
		$forumNames = array('test_forum_1', 'test_forum_2', 'test_forum_3');
		$forums = array();
		foreach ($forumNames as $forumName) {
			$forums[] = $this->addNewForum($forumName, true, false);
		}
		
		$this->em->flush();
		
		return $forums;
	}

	protected function addNewCategory($categoryName, $order, Forum $forum = null, $persist = true, $andFlush = true)
	{
		$category = $this->getCategoryModel()->createCategory();
		$category->setName($categoryName);
		$category->setListOrderPriority($order);
		$category->setForum($forum);
		
		if ($persist) {
			$this->em->persist($category);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($category);
			}
		}
		
		return $category;
	}

	protected function addFixturesForCategories($forums)
	{
		$categoryNames = array('test_category_1', 'test_category_2', 'test_category_3');
		$categories = array();
		foreach ($forums as $forum) {
			foreach ($categoryNames as $index => $categoryName) {
				$categories[] = $this->addNewCategory($categoryName, $index, $forum, true, true);
			}
		}
		
		$this->em->flush();
		
		return $categories;
	}

	protected function addNewBoard($boardName, $boardDescription, $order, Category $category = null, $persist = true, $andFlush = true)
	{
		$board = $this->getBoardModel()->createBoard();
		$board->setName($boardName);
		$board->setDescription($boardDescription);
		$board->setListOrderPriority($order);
		$board->setCategory($category);
		
		if ($persist) {
			$this->em->persist($board);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($board);
			}
		}
		
		return $board;
	}

	protected function addFixturesForBoards($categories)
	{
		$boardNames = array('test_board_1', 'test_board_2', 'test_board_3');
		$boards = array();
		foreach ($categories as $category) {
			foreach ($boardNames as $index => $boardName) {
				$boards[] = $this->addNewBoard($boardName, $boardName, $index, $category, true, true);
			}
		}
		
		$this->em->flush();

		return $boards;
	}

	protected function addNewTopic($title, Board $board = null, $persist = true, $andFlush = true)
	{
		$topic = $this->getTopicModel()->createTopic();
		$topic->setTitle($title);
		$topic->setBoard($board);
		
		if ($persist) {
			$this->em->persist($topic);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($topic);
			}
		}
		
		return $topic;
	}

	protected function addFixturesForTopics($boards)
	{
		$topicTitles = array('test_topic_1', 'test_topic_2', 'test_topic_3');
		$topics = array();
		foreach ($boards as $board) {
			foreach ($topicTitles as $topicTitle) {
				$topics[] = $this->addNewTopic($topicTitle, $board, true, false);
			}
		}
		
		$this->em->flush();
		
		return $topics;
	}

	protected function addNewPost($body, $topic, $user, \Datetime $createdDate = null, $persist = true, $andFlush = true)
	{
		$post = $this->getPostModel()->createPost();
		$post->setTopic($topic);
		$post->setBody($body);
        $post->setCreatedDate($createdDate ?: new \DateTime());
        $post->setCreatedBy($user);
        $post->setDeleted(false);
		
		if ($topic) {
			$topic->setLastPost($post);
			
			if (! $topic->getFirstPost()) {
				$topic->setFirstPost($post);
			}
		}
		
		if ($persist) {
			$this->em->persist($post);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($post);
			}
		}
		
		return $post;
	}

	protected function addFixturesForPosts($topics, $user)
	{
		$postBodies = array('test_post_1', 'test_post_2', 'test_post_3');
		$posts = array();
		foreach ($topics as $topicIndex => $topic) {
			foreach ($postBodies as $postIndex => $postBody) {
				$datetime = new \DateTime('now + ' . (int)(($topicIndex + 1) . ($postIndex + 1)) . ' minutes');
				$posts[] = $this->addNewPost($postBody, $topics[$topicIndex], $user, $datetime, true, false);
				
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

	protected function addNewSubscription($forum, $topic, $user, $isRead = false, $persist = true, $andFlush = true)
	{
		$subscription = $this->getSubscriptionModel()->createSubscription();
		$subscription->setTopic($topic);
        $subscription->setOwnedBy($user);
		$subscription->setForum($forum);
        $subscription->setRead($isRead);
        $subscription->setSubscribed(true);
		
		if ($persist) {
			$this->em->persist($subscription);
			if ($andFlush) {
				$this->em->flush();
				$this->em->refresh($subscription);
			}
		}
		
		return $subscription;
	}

	protected function addFixturesForSubscriptions($forum, $topics, $user, $isRead = false)
	{
		$subscriptions = array();
		foreach ($topics as $topic) {
			$subscriptions[] = $this->addNewSubscription($forum, $topic, $user, $isRead, true, false);
		}
		
		$this->em->flush();
		
		return $subscriptions;
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
            $this->forumModel = $this->container->get('ccdn_forum_forum.model.forum');
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
            $this->categoryModel = $this->container->get('ccdn_forum_forum.model.category');
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
            $this->boardModel = $this->container->get('ccdn_forum_forum.model.board');
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
            $this->topicModel = $this->container->get('ccdn_forum_forum.model.topic');
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
            $this->postModel = $this->container->get('ccdn_forum_forum.model.post');
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
            $this->registryModel = $this->container->get('ccdn_forum_forum.model.registry');
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
            $this->subscriptionModel = $this->container->get('ccdn_forum_forum.model.subscription');
        }

        return $this->subscriptionModel;
    }
}
