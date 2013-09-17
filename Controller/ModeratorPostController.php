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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\Event;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent;
//use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostResponseEvent;

use CCDNForum\ForumBundle\Entity\Post;

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
class ModeratorPostController extends UserPostBaseController
{
    /**
     * Lock to prevent editing of post.
     *
     * @access public
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function lockAction($postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $user = $this->getUser();

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId);

        $this->isFound($post);
        $this->isAuthorisedToViewPost($post);
        $this->isAuthorisedToEditPost($post);

        $this->getPostModel()->lock($post, $user)->flush();

        $this->setFlash('notice', $this->trans('flash.post.success.lock', array('%post_id%' => $postId)));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $post->getTopic()->getId()) ));
    }

    /**
     *
     * @access public
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function unlockAction($postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId);

        $this->isFound($post);
        $this->isAuthorisedToViewPost($post);
        $this->isAuthorisedToEditPost($post);

        $this->getPostModel()->unlock($post)->flush();

        $this->setFlash('notice', $this->trans('flash.post.unlock.success', array('%post_id%' => $postId)));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $post->getTopic()->getId()) ));
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function restoreAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);

		$this->isAuthorised($this->getAuthorizer()->canRestorePost($post, $forum));

        $this->getPostModel()->restore($post)->flush();

		$this->dispatch(ForumEvents::MODERATOR_POST_RESTORE_COMPLETE, new ModeratorPostEvent($this->getRequest(), $post));

        // forward user
        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $post->getTopic()->getId()) ));
    }
}
