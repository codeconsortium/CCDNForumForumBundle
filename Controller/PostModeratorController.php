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

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Draft;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostModeratorController extends PostBaseController
{
	/**
	 * Lock to prevent editing of post.
	 *
	 * @access public
	 * @param int $postId
	 * @return RedirectResponse
	 */
	public function lockAction($postId)
	{
	    $this->isAuthorised('ROLE_MODERATOR');

	    $user = $this->getUser();

	    $post = $this->getPostManager()->findOneByIdWithTopicAndBoard($postId);

	    $this->isFound($post);

	    $this->getPostManager()->lock($post, $user)->flush();

	    $this->setFlash('notice', $this->trans('ccdn_forum_admin.flash.post.lock.success', array('%post_id%' => $postId)));

	    return new RedirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $post->getTopic()->getId()) ));
	}

	/**
	 *
	 * @access public
	 * @param int $postId
	 * @return RedirectResponse
	 */
	public function unlockAction($postId)
	{
	    $this->isAuthorised('ROLE_MODERATOR');

	    $post = $this->getPostManager()->findOneByIdWithTopicAndBoard($postId);

	    $this->isFound($post);

	    $this->getPostManager()->unlock($post)->flush();

	    $this->setFlash('notice', $this->trans('ccdn_forum_admin.flash.post.unlock.success', array('%post_id%' => $postId)));

	    return new RedirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $post->getTopic()->getId()) ));
	}

	/**
	 *
	 * @access public
	 * @param int $postId
	 * @return RedirectResponse
	 */
	public function restoreAction($postId)
	{
	    $this->isAuthorised('ROLE_MODERATOR');

	    $post = $this->getPostManager()->findOneByIdWithTopicAndBoard($postId);

		$this->isFound($post);
		
	    $this->getPostManager()->restore($post)->flush();

	    // set flash message
	    $this->setFlash('notice', $this->trans('ccdn_forum_admin.flash.post.restore.success', array('%post_id%' => $postId)));

	    // forward user
	    return new RedirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $post->getTopic()->getId()) ));
	}
}