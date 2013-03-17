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

use Symfony\Component\DependencyInjection\Container;
	
/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface GatewayBagInterface
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
	 * @return \CCDNForum\ForumBundle\Gateway\CategoryGateway
	 */
	public function getCategoryGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\BoardGateway
	 */
	public function getBoardGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\TopicGateway
	 */
	public function getTopicGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\PostGateway
	 */
	public function getPostGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\DraftGateway
	 */
	public function getDraftGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\RegistryGateway
	 */
	public function getRegistryGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Gateway\SubscriptionGateway
	 */
	public function getSubscriptionGateway();
}