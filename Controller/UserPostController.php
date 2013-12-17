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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostResponseEvent;

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
class UserPostController extends UserPostBaseController
{
    /**
     *
     * @access public
     * @param  string         $forumName
     * @param  int            $postId
     * @return RenderResponse
     */
    public function showAction($forumName, $postId)
    {
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canShowPost($post, $forum));

        if ($this->isGranted('ROLE_USER')) { // get the topic subscriptions.
            $subscription = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($post->getTopic()->getId(), $this->getUser()->getId());
        } else {
            $subscription = null;
        }
        $subscriberCount = $this->getSubscriptionModel()->countSubscriptionsForTopicById($post->getTopic()->getId());

        return $this->renderResponse('CCDNForumForumBundle:User:Post/show.html.', array(
            'crumbs' => $this->getCrumbs()->addUserPostShow($forum, $post),
            'forum' => $forum,
            'forumName' => $forumName,
            'topic' => $post->getTopic(), 'post' => $post,
            'subscription' => $subscription,
            'subscription_count' => $subscriberCount,
        ));
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $postId
     * @return RedirectResponse|RenderResponse
     */
    public function editAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_USER');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canEditPost($post, $forum));
        $formHandler = $this->getFormHandlerToEditPost($post);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Post/edit.html.', array(
            'crumbs' => $this->getCrumbs()->addUserPostShow($forum, $post),
            'forum' => $forum,
            'forumName' => $forumName,
            'post' => $post,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
        $this->dispatch(ForumEvents::USER_POST_EDIT_RESPONSE, new UserPostResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $postId
     * @return RedirectResponse|RenderResponse
     */
    public function editProcessAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_USER');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canEditPost($post, $forum));
        $formHandler = $this->getFormHandlerToEditPost($post);

        if ($formHandler->process()) {
            $response = $this->redirectResponseForTopicOnPageFromPost($forumName, $formHandler->getForm()->getData()->getTopic(), $formHandler->getForm()->getData());
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:User:Post/edit.html.', array(
                'crumbs' => $this->getCrumbs()->addUserPostShow($forum, $post), 'forum' => $forum, 'post' => $post,
                'forumName' => $forumName,
                'preview' => $formHandler->getForm()->getData(), 'form' => $formHandler->getForm()->createView(),
            ));
        }
        $this->dispatch(ForumEvents::USER_POST_EDIT_RESPONSE, new UserPostResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $postId
     * @return RedirectResponse|RenderResponse
     */
    public function deleteAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_USER');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canDeletePost($post, $forum));
        $formHandler = $this->getFormHandlerToDeletePost($post);
        $response = $this->renderResponse('CCDNForumForumBundle:User:Post/delete.html.', array(
            'crumbs' => $this->getCrumbs()->addUserPostDelete($forum, $post),
            'forum' => $forum,
            'forumName' => $forumName,
            'post' => $post,
            'form' => $formHandler->getForm()->createView(),
        ));
        $this->dispatch(ForumEvents::USER_POST_SOFT_DELETE_RESPONSE, new UserPostResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $postId
     * @return RedirectResponse|RenderResponse
     */
    public function deleteProcessAction($forumName, $postId)
    {
        $this->isAuthorised('ROLE_USER');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true));
        $this->isAuthorised($this->getAuthorizer()->canDeletePost($post, $forum));
        $formHandler = $this->getFormHandlerToDeletePost($post);

        if ($formHandler->process()) {
            $response = $this->redirectResponseForTopicOnPageFromPost($forumName, $post->getTopic(), $post);
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:User:Post/delete.html.', array(
                'crumbs' => $this->getCrumbs()->addUserPostShow($forum, $post),
                'forum' => $forum, 'post' => $post,
                'forumName' => $forumName,
                'form' => $formHandler->getForm()->createView(),
            ));
        }
        $this->dispatch(ForumEvents::USER_POST_SOFT_DELETE_RESPONSE, new UserPostResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }
}
