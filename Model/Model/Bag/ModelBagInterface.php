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

namespace CCDNForum\ForumBundle\Model\Model\Bag;

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
interface ModelBagInterface
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
     * @return \CCDNForum\ForumBundle\Model\Model\ForumModel
     */
    public function getForumModel();
	
    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\CategoryModel
     */
    public function getCategoryModel();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\BoardModel
     */
    public function getBoardModel();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\TopicModel
     */
    public function getTopicModel();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\PostModel
     */
    public function getPostModel();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\DraftModel
     */
    public function getDraftModel();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\RegistryModel
     */
    public function getRegistryModel();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\SubscriptionModel
     */
    public function getSubscriptionModel();
}
