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

namespace CCDNForum\ForumBundle\Gateway\Bag;

use CCDNForum\ForumBundle\Gateway\Bag\GatewayBagInterface;

use Symfony\Component\DependencyInjection\Container;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class GatewayBag implements GatewayBagInterface
{
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\CategoryGateway $categoryGateway
	 */
	protected $categoryGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\BoardGateway $boardGateway
	 */
	protected $boardGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\TopicGateway $topicGateway
	 */
	protected $topicGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\PostGateway $postGateway
	 */
	protected $postGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\DraftGateway $draftGateway
	 */
	protected $draftGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\RegistryGateway $registryGateway
	 */
	protected $registryGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Gateway\SubscriptionGateway $subscriptionGateway
	 */
	protected $subscriptionGateway;
	
	/**
	 *
	 * @access protected
	 * @var \Symfony\Component\DependencyInjection\Container $container
	 */
    protected $container;

	/**
	 *
	 * @access public
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\CategoryGateway
	 */
	public function getCategoryGateway()
	{
		if (null == $this->categoryGateway) {
			$this->categoryGateway = $this->container->get('ccdn_forum_forum.gateway.category');
		}
		
		return $this->categoryGateway;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\BoardGateway
	 */
	public function getBoardGateway()
	{
		if (null == $this->boardGateway) {
			$this->boardGateway = $this->container->get('ccdn_forum_forum.gateway.board');
		}
		
		return $this->boardGateway;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\TopicGateway
	 */
	public function getTopicGateway()
	{
		if (null == $this->topicGateway) {
			$this->topicGateway = $this->container->get('ccdn_forum_forum.gateway.topic');
		}
		
		return $this->topicGateway;		
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\PostGateway
	 */
	public function getPostGateway()
	{
		if (null == $this->postGateway) {
			$this->postGateway = $this->container->get('ccdn_forum_forum.gateway.post');
		}
		
		return $this->postGateway;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\DraftGateway
	 */
	public function getDraftGateway()
	{
		if (null == $this->draftGateway) {
			$this->draftGateway = $this->container->get('ccdn_forum_forum.gateway.draft');
		}
		
		return $this->draftGateway;		
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\RegistryGateway
	 */
	public function getRegistryGateway()		
	{
		if (null == $this->registryGateway) {
			$this->registryGateway = $this->container->get('ccdn_forum_forum.gateway.registry');
		}
		
		return $this->registryGateway;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\SubscriptionGateway
	 */
	public function getSubscriptionGateway()
	{
		if (null == $this->subscriptionGateway) {
			$this->subscriptionGateway = $this->container->get('ccdn_forum_forum.gateway.subscription');
		}
		
		return $this->subscriptionGateway;
	}
}