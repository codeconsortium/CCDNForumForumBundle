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

namespace CCDNForum\ForumBundle\Controller;

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
class AdminForumBaseController extends BaseController
{
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\ForumCreateFormHandler
	 */
	public function getFormHandlerToCreateForum()
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.forum_create');

	    return $formHandler;
	}
}