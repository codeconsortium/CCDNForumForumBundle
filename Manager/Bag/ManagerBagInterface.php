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

use Symfony\Component\DependencyInjection\Container;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface ManagerBagInterface
{
	/**
	 *
	 * @access public
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
    public function __construct(Container $container);
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\CategoryManager
	 */
	public function getCategoryManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\BoardManager
	 */
	public function getBoardManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\TopicManager
	 */
	public function getTopicManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\PostManager
	 */
	public function getPostManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\DraftManager
	 */
	public function getDraftManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\RegistryManager
	 */
	public function getRegistryManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Manager\SubscriptionManager
	 */
	public function getSubscriptionManager();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getTopicsPerPageOnSubscriptions();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getTopicsPerPageOnBoards();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getPostsPerPageOnTopics();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getDraftsPerPageOnDrafts();
}