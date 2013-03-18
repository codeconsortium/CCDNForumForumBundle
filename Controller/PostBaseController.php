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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostBaseController extends BaseController
{
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToEditPost($post)
	{
		return $this->isAuthorised($this->getPostManager()->isAuthorisedToEditPost($post));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToDeletePost($post)
	{
		return $this->isAuthorised($this->getPostManager()->isAuthorisedToDeletePost($post));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToRestorePost($post)
	{
		return $this->isAuthorised($this->getPostManager()->isAuthorisedToRestorePost($post));
	}
}