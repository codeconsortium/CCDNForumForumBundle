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

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class ManagerBag
{
	/**
	 *
	 * @access protected
	 */
	protected $categoryManager;
	
	/**
	 *
	 * @access protected
	 */
	protected $boardManager;
	
	/**
	 *
	 * @access protected
	 */
	protected $topicManager;
	
	/**
	 *
	 * @access protected
	 */
	protected $postManager;
	
	/**
	 *
	 * @access protected
	 */
	protected $draftManager;
	
	/**
	 *
	 * @access protected
	 */
	protected $registryManager;
	
	/**
	 *
	 * @access protected
	 */
	protected $subscriptionManager;
	
	/**
	 *
	 * @access protected
	 */
    protected $container;

	/**
	 *
	 * @access public
	 * @param $doctrine
	 */
    public function __construct($container)
    {
        $this->container = $container;
    }
	
	public function getCategoryManager()
	{
		if (null == $this->categoryManager) {
			$this->categoryManager = $this->container->get('ccdn_forum_forum.manager.category');
		}
		
		return $this->categoryManager;
	}
	
	public function getBoardManager()
	{
		if (null == $this->boardManager) {
			$this->boardManager = $this->container->get('ccdn_forum_forum.manager.board');
		}
		
		return $this->boardManager;
	}
	
	public function getTopicManager()
	{
		if (null == $this->topicManager) {
			$this->topicManager = $this->container->get('ccdn_forum_forum.manager.topic');
		}
		
		return $this->topicManager;		
	}
	
	public function getPostManager()
	{
		if (null == $this->postManager) {
			$this->postManager = $this->container->get('ccdn_forum_forum.manager.post');
		}
		
		return $this->postManager;
	}
	
	public function getDraftManager()
	{
		if (null == $this->draftManager) {
			$this->draftManager = $this->container->get('ccdn_forum_forum.manager.draft');
		}
		
		return $this->draftManager;		
	}
		
	public function getRegistryManager()		
	{
		if (null == $this->registryManager) {
			$this->registryManager = $this->container->get('ccdn_forum_forum.manager.registry');
		}
		
		return $this->registryManager;
	}
	
	public function getSubscriptionManager()
	{
		if (null == $this->subscriptionManager) {
			$this->subscriptionManager = $this->container->get('ccdn_forum_forum.manager.subscription');
		}
		
		return $this->subscriptionManager;
	}
}