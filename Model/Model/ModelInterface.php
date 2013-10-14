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

namespace CCDNForum\ForumBundle\Model\Model;

use CCDNForum\ForumBundle\Model\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Repository\RepositoryInterface;

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
interface ModelInterface
{
	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Model\Repository\RepositoryInterface $repository
	 * @param \CCDNForum\ForumBundle\Model\Manager\ManagerInterface       $manager
	 */
    public function __construct(RepositoryInterface $repository, ManagerInterface $manager);

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Model\Repository\RepositoryInterface
	 */
    public function getRepository();

	/**
	 * 
	 * @access public
	 * @return \CCDNForum\ForumBundle\Model\Manager\ManagerInterface
	 */
    public function getManager();
}
