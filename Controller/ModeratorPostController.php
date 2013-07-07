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

        $post = $this->getPostModel()->findOneByIdWithTopicAndBoard($postId);

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

        $post = $this->getPostModel()->findOneByIdWithTopicAndBoard($postId);

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
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function restoreAction($postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $post = $this->getPostModel()->findOneByIdWithTopicAndBoard($postId);

        $this->isFound($post);
        $this->isAuthorisedToViewPost($post);
        $this->isAuthorisedToRestorePost($post);

        $this->getPostModel()->restore($post)->flush();

        // set flash message
        $this->setFlash('notice', $this->trans('ccdn_forum_admin.flash.post.restore.success', array('%post_id%' => $postId)));

        // forward user
        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $post->getTopic()->getId()) ));
    }
}
