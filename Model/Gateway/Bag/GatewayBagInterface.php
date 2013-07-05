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

namespace CCDNForum\ForumBundle\Model\Gateway\Bag;

use Symfony\Component\DependencyInjection\Container;

/**
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
