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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent;
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
        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        // Get post by id.
        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);

        $this->isAuthorised($this->getAuthorizer()->canShowPost($post, $forum));

        // get the topic subscriptions.
        if ($this->isGranted('ROLE_USER')) {
            $subscription = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($post->getTopic()->getId(), $this->getUser()->getId());
        } else {
            $subscription = null;
        }

        $subscriberCount = $this->getSubscriptionModel()->countSubscriptionsForTopicById($post->getTopic()->getId());

        // Setup crumb trail.
        $crumbs = $this->getCrumbs()->addUserPostShow($forum, $post);

        return $this->renderResponse('CCDNForumForumBundle:User:Post/show.html.',
            array(
                'crumbs' => $crumbs,
                'forum' => $forum,
                'topic' => $post->getTopic(),
                'post' => $post,
                'subscription' => $subscription,
                'subscription_count' => $subscriberCount,
            )
        );
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
        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $this->isAuthorised('ROLE_USER');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);

        $this->isAuthorised($this->getAuthorizer()->canEditPost($post, $forum));

        $formHandler = $this->getFormHandlerToEditPost($post);

        // Setup crumb trail.
        $crumbs = $this->getCrumbs()->addUserPostShow($forum, $post);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Post/edit_post.html.',
            array(
                'crumbs' => $crumbs,
                'forum' => $forum,
                'post' => $post,
                'preview' => $formHandler->getForm()->getData(),
                'form' => $formHandler->getForm()->createView(),
            )
        );

        $this->dispatch(ForumEvents::USER_POST_EDIT_RESPONSE, new UserPostResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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
        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $this->isAuthorised('ROLE_USER');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);

        $this->isAuthorised($this->getAuthorizer()->canEditPost($post, $forum));

        $formHandler = $this->getFormHandlerToEditPost($post);

        if ($formHandler->process()) {
            // get posts for determining the page of the edited post
            $post = $formHandler->getForm()->getData();
            $topic = $post->getTopic();

            //$page = $this->getModelManager()->getPageForPostOnTopic($topic, $post);

            $this->dispatch(ForumEvents::USER_POST_EDIT_COMPLETE, new UserPostEvent($this->getRequest(), $post));

            $response = $this->redirectResponse(
                $this->path('ccdn_forum_user_topic_show',
                    array(
                        'forumName' => $forumName,
                        'topicId' => $topic->getId(),
                        //'page' => $page,
                    )
                ) //. '#' . $post->getId()
            );
        } else {
            // Setup crumb trail.
            $crumbs = $this->getCrumbs()->addUserPostShow($forum, $post);

            $response = $this->renderResponse('CCDNForumForumBundle:User:Post/edit_post.html.',
                array(
                    'crumbs' => $crumbs,
                    'forum' => $forum,
                    'post' => $post,
                    'preview' => $formHandler->getForm()->getData(),
                    'form' => $formHandler->getForm()->createView(),
                )
            );
        }

        $this->dispatch(ForumEvents::USER_POST_EDIT_RESPONSE, new UserPostResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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
        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $this->isAuthorised('ROLE_USER');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);

        $this->isAuthorised($this->getAuthorizer()->canDeletePost($post, $forum));

        $formHandler = $this->getFormHandlerToDeletePost($post);

        // setup crumb trail.
        $crumbs = $this->getCrumbs()->addUserPostDelete($forum, $post);

        $response = $this->renderResponse('CCDNForumForumBundle:User:Post/delete_post.html.',
            array(
                'crumbs' => $crumbs,
                'forum' => $forum,
                'post' => $post,
                'form' => $formHandler->getForm()->createView(),
            )
        );

        $this->dispatch(ForumEvents::USER_POST_SOFT_DELETE_RESPONSE, new UserPostResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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
        $forum = $this->getForumModel()->findOneForumByName($forumName);
        $this->isFound($forum);

        $this->isAuthorised('ROLE_USER');

        $post = $this->getPostModel()->findOnePostByIdWithTopicAndBoard($postId, true);
        $this->isFound($post);

        $this->isAuthorised($this->getAuthorizer()->canDeletePost($post, $forum));

        $formHandler = $this->getFormHandlerToDeletePost($post);

        if ($formHandler->process()) {
            // get posts for determining the page of the edited post
            $post = $formHandler->getForm()->getData();
            $topic = $post->getTopic();

            //$page = $this->getModelManager()->getPageForPostOnTopic($topic, $post);

            $this->dispatch(ForumEvents::USER_POST_SOFT_DELETE_COMPLETE, new UserPostEvent($this->getRequest(), $post));

            $response = $this->redirectResponse(
                $this->path('ccdn_forum_user_topic_show',
                    array(
                        'forumName' => $forumName,
                        'topicId' => $topic->getId(),
                        //'page' => $page,
                    )
                ) //. '#' . $post->getId()
            );
        } else {
            // Setup crumb trail.
            $crumbs = $this->getCrumbs()->addUserPostShow($forum, $post);

            $response = $this->renderResponse('CCDNForumForumBundle:User:Post/delete_post.html.',
                array(
                    'crumbs' => $crumbs,
                    'forum' => $forum,
                    'post' => $post,
                    'form' => $formHandler->getForm()->createView(),
                )
            );
        }

        $this->dispatch(ForumEvents::USER_POST_SOFT_DELETE_RESPONSE, new UserPostResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

        return $response;
    }
}
