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

namespace CCDNForum\ForumBundle\Manager\Bag;

use CCDNForum\ForumBundle\Manager\Bag\ManagerBagInterface;

use Symfony\Component\DependencyInjection\Container;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class ManagerBag implements ManagerBagInterface
{
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\CategoryManager $categoryManager
	 */
	protected $categoryManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\BoardManager $boardManager
	 */
	protected $boardManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\TopicManager $topicManager
	 */
	protected $topicManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\PostManager $postManager
	 */
	protected $postManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\DraftManager $draftManager
	 */
	protected $draftManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\RegistryManager $registryManager
	 */
	protected $registryManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\SubscriptionManager $subscriptionManager
	 */
	protected $subscriptionManager;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\PolicyManager $policyManager
	 */
	protected $policyManager;
	
	/**
	 *
	 * @access protected
	 * @var \Symfony\Component\DependencyInjection\Container $container
	 */
    protected $container;
	
	/**
	 *
	 * @access protected
	 * @var int $topicsPerPageOnSubscriptions
	 */
	protected $topicsPerPageOnSubscriptions;
	
	/**
	 *
	 * @access protected
	 * @var int $topicsPerPageOnBoards
	 */
	protected $topicsPerPageOnBoards;

	/**
	 *
	 * @access protected
	 * @var int $postsPerPageOnTopics
	 */
	protected $postsPerPageOnTopics;
	
	/**
	 *
	 * @access protected
	 * @var int $draftsPerPageOnDrafts
	 */
	protected $draftsPerPageOnDrafts;

	/**
	 *
	 * @access public
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
    public function __construct(Container $container)
    {
        $this->container = $container;

		$this->topicsPerPageOnSubscriptions = $this->container->getParameter('ccdn_forum_forum.subscription.list.topics_per_page');		
		$this->topicsPerPageOnBoards = $this->container->getParameter('ccdn_forum_forum.board.show.topics_per_page');
		$this->postsPerPageOnTopics  = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');
		$this->draftsPerPageOnDrafts = $this->container->getParameter('ccdn_forum_forum.draft.list.drafts_per_page');
    }
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\CategoryManager
	 */
	public function getCategoryManager()
	{
		if (null == $this->categoryManager) {
			$this->categoryManager = $this->container->get('ccdn_forum_forum.manager.category');
		}
		
		return $this->categoryManager;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\BoardManager
	 */
	public function getBoardManager()
	{
		if (null == $this->boardManager) {
			$this->boardManager = $this->container->get('ccdn_forum_forum.manager.board');
		}
		
		return $this->boardManager;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\TopicManager
	 */
	public function getTopicManager()
	{
		if (null == $this->topicManager) {
			$this->topicManager = $this->container->get('ccdn_forum_forum.manager.topic');
		}
		
		return $this->topicManager;		
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\PostManager
	 */
	public function getPostManager()
	{
		if (null == $this->postManager) {
			$this->postManager = $this->container->get('ccdn_forum_forum.manager.post');
		}
		
		return $this->postManager;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\DraftManager
	 */
	public function getDraftManager()
	{
		if (null == $this->draftManager) {
			$this->draftManager = $this->container->get('ccdn_forum_forum.manager.draft');
		}
		
		return $this->draftManager;		
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\RegistryManager
	 */
	public function getRegistryManager()		
	{
		if (null == $this->registryManager) {
			$this->registryManager = $this->container->get('ccdn_forum_forum.manager.registry');
		}
		
		return $this->registryManager;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\SubscriptionManager
	 */
	public function getSubscriptionManager()
	{
		if (null == $this->subscriptionManager) {
			$this->subscriptionManager = $this->container->get('ccdn_forum_forum.manager.subscription');
		}
		
		return $this->subscriptionManager;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\PolicyManager
	 */
	public function getPolicyManager()
	{
		if (null == $this->policyManager) {
			$this->policyManager = $this->container->get('ccdn_forum_forum.manager.policy');
		}
		
		return $this->policyManager;
	}
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getTopicsPerPageOnSubscriptions()
	{
		return $this->topicsPerPageOnSubscriptions;
	}
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getTopicsPerPageOnBoards()
	{
		return $this->topicsPerPageOnBoards;
	}
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getPostsPerPageOnTopics()
	{
		return $this->postsPerPageOnTopics;
	}
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getDraftsPerPageOnDrafts()
	{
		return $this->draftsPerPageOnDrafts;
	}
}