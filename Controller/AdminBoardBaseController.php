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

use CCDNForum\ForumBundle\Entity\Board;

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
class AdminBoardBaseController extends BaseController
{
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\BoardCreateFormHandler
	 */
	public function getFormHandlerToCreateBoard()
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.board_create');

	    return $formHandler;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\BoardUpdateFormHandler
	 */
	public function getFormHandlerToUpdateBoard(Board $board)
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.board_update');

		$formHandler->setBoard($board);
		
	    return $formHandler;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNForum\ForumBundle\Form\Handler\BoardDeleteFormHandler
	 */
	public function getFormHandlerToDeleteBoard(Board $board)
	{
	    $formHandler = $this->container->get('ccdn_forum_forum.form.handler.board_delete');

		$formHandler->setBoard($board);
		
	    return $formHandler;
	}
}
