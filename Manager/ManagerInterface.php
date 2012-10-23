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

namespace CCDNForum\ForumBundle\Manager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface ManagerInterface
{

	/**
	 *
	 * @access public
	 * @param $doctrine
	 */
    public function __construct($doctrine, $container);

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return self
	 */
    public function persist($entity);

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return self
	 */
    public function remove($entity);

	/**
	 *
	 * @access public
	 * @return self
	 */
    public function flush();

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return self
	 */
    public function refresh($entity);

}
