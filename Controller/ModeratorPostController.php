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

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostResponseEvent;

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
class ModeratorPostController extends ModeratorPostBaseController
{
    /**
     * Lock to prevent editing of post.
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function lockAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canLockPost($post, $forum));
        $this->getPostModel()->lock($post);
        $this->dispatch(ForumEvents::MODERATOR_POST_LOCK_COMPLETE, new ModeratorPostEvent($this->getRequest(), $post));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $post->getTopic()->getId()
        )));
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function unlockAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canUnlockPost($post, $forum));
        $formHandler = $this->getFormHandlerToUnlockPost($post);
        $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Post/unlock.html.', array(
            'crumbs' => $this->getCrumbs()->addModeratorPostUnlock($forum, $post),
            'forum' => $forum,
            'forumName' => $forumName,
            'topic' => $post->getTopic(),
            'post' => $post,
            'form' => $formHandler->getForm()->createView(),
        ));
        $this->dispatch(ForumEvents::MODERATOR_POST_UNLOCK_RESPONSE, new ModeratorPostResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $postId
     * @return RedirectResponse
     */
    public function unlockProcessAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canUnlockPost($post, $forum));
        $formHandler = $this->getFormHandlerToUnlockPost($post);

        if ($formHandler->process()) {
            $response = $this->redirectResponseForTopicOnPageFromPost($forumName, $post->getTopic(), $post);
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Post/unlock.html.', array(
                'crumbs' => $this->getCrumbs()->addModeratorPostUnlock($forum, $post->getTopic()),
                'forum' => $forum,
                'forumName' => $forumName,
                'topic' => $post->getTopic(),
                'post' => $post,
                'form' => $formHandler->getForm()->createView(),
            ));
        }
        $this->dispatch(ForumEvents::MODERATOR_POST_UNLOCK_RESPONSE, new ModeratorPostResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canRestorePost($post, $forum));
        $this->getPostModel()->restore($post)->flush();
        $this->dispatch(ForumEvents::MODERATOR_POST_RESTORE_COMPLETE, new ModeratorPostEvent($this->getRequest(), $post));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $post->getTopic()->getId()
        )));
    }
}
