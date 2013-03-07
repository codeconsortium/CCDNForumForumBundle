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
class TopicController extends BaseController
{

    /**
     *
     * @access public
     * @param int $topicId, int $page
     * @return RedirectResponse|RenderResponse
     */
    public function showAction($topicId, $page)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->findByIdWithBoardAndCategory($topicId);

        $postsPager = $this->container->get('ccdn_forum_forum.repository.post')->findPostsForTopicByIdPaginated($topicId);

        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');
        $postsPager->setMaxPerPage($postsPerPage);
        $postsPager->setCurrentPage($page, false, true);

        if ( ! $topic || ! $postsPager->getCurrentPageResults()) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        // if this topics first post is deleted, and no
        // other posts exist then throw an NotFoundHttpException!
        if ($topic->getIsDeleted()
        && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
        {
            throw new NotFoundHttpException('No such topic exists!');
        }

        // update the view counter because you viewed the topic
        $this->container->get('ccdn_forum_forum.manager.topic')->incrementViewCounter($topic);

        // get the topic subscriptions
        if ($this->container->get('security.context')->isGranted('ROLE_USER')) {
            $subscription = $this->container->get('ccdn_forum_forum.repository.subscription')->findTopicSubscriptionByTopicAndUserId($topicId, $user->getId());
        } else {
            $subscription = null;
        }

        //
        // Get post counts for users
        //
//        if (count($postsPager->getCurrentPageResults()) > 0) {
//            $registryUserIds = array();
//
//            foreach ($postsPager->getCurrentPageResults() as $key => $post) {
//                if ($post->getCreatedBy()) {
//                    $id = $post->getCreatedBy()->getId();
//
//                    if (! array_key_exists($id, $registryUserIds)) {
//                        $registryUserIds[] = $id;
//                    }
//                }
//            }
//        }
//
//        $registries = $this->container->get('ccdn_forum_forum.manager.registry')->getRegistriesForUsersAsArray($registryUserIds);

        $subscriberCount = $this->container->get('ccdn_forum_forum.repository.subscription')->getSubscriberCountForTopicById($topicId);

        // setup crumb trail.
        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(), $this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:show.html.' . $this->getEngine(), array(
            'user'	=> $user,
            'crumbs' => $crumbs,
            'pager' => $postsPager,
            'board' => $board,
            'topic' => $topic,
            //'registries' => $registries,
            'subscription' => $subscription,
            'subscription_count' => $subscriberCount,
        ));
    }

    /**
     *
     * @access public
     * @param int $boardId, int $draftId
     * @return RedirectResponse|RenderResponse
     */
    public function createAction($boardId, $draftId)
    {
        //
        //	Invalidate this action / redirect if user should not have access to it
        //
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $board = $this->container->get('ccdn_forum_forum.repository.board')->find($boardId);

        if (! $board) {
            throw new NotFoundHttpException('No such board exists!');
        }

        if (! $board->isAuthorisedToCreateTopic($this->container->get('security.context'))) {
            throw new AccessDeniedException('You do not have permission to use this resource.');
        }

        //
        // Set the form handler options
        //
        $options = array('board' => $board, 'user' => $user);

        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.topic_create')->setDefaultValues($options);

		// Flood Control.
		if ( ! $this->container->get('ccdn_forum_forum.component.flood_control')->isFlooded()) {
	        if (isset($_POST['submit_post'])) {
	            if ($formHandler->process()) {
	                $this->container->get('ccdn_forum_forum.component.flood_control')->incrementCounter();

					$this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('ccdn_forum_forum.flash.topic.create.success', array('%topic_title%' => $formHandler->getForm()->getData()->getTopic()->getTitle()), 'CCDNForumForumBundle'));

	                return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $formHandler->getForm()->getData()->getTopic()->getId() )));
	            }
	        }
		} else {
			$this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_forum.flash.topic.flood_control', array(), 'CCDNForumForumBundle'));
		}

        // setup crumb trail.
        $category = $board->getCategory();

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(), $this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.topic.create', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_topic_create', array('boardId' => $board->getId())), "edit");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:create.html.' . $this->getEngine(), array(
            'user' => $user,
            'crumbs' => $crumbs,
            'board' => $board,
            'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
    }

    /**
     *
     * @access public
     * @param int $topicId, int $quoteId, int $draftId
     * @return RedirectResponse|RenderResponse
     */
    public function replyAction($topicId, $quoteId, $draftId)
    {
        //
        // 	Invalidate this action / redirect if user should not have access to it
        //
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->findOneByIdJoinedToPosts($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        if (! $topic->getBoard()->isAuthorisedToTopicReply($this->container->get('security.context'))) {
            throw new AccessDeniedException('You do not have permission to use this resource.');
        }

        if ($topic->getIsClosed() && ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('This topic has been closed!');
        }

        //
        // Set the form handler options
        //
        if ( ! empty($quoteId)) {
            $quote = $this->container->get('ccdn_forum_forum.repository.post')->find($quoteId);
        }

        $options = array('topic' => $topic,	'user' => $user, 'quote' => (empty($quote) ? null : $quote));

        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.post_create')->setDefaultValues($options);

		// Flood Control.
		if ( ! $this->container->get('ccdn_forum_forum.component.flood_control')->isFlooded()) {
	        if (isset($_POST['submit_post'])) {
	            if ($formHandler->process()) {
					$this->container->get('ccdn_forum_forum.component.flood_control')->incrementCounter();
					
	                // page of the last post
	                $postsPerTopicPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');

	                $pageCounter = $this->container->get('ccdn_forum_forum.repository.post')->getPostCountForTopicById($topicId);

	                $page =  $pageCounter ? ceil($pageCounter / $postsPerTopicPage) : 1;

	                $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('ccdn_forum_forum.flash.topic.reply.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumForumBundle'));

	                return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show_paginated_anchored',
	                    array('topicId' => $topicId, 'page' => $page, 'postId' => $topic->getLastPost()->getId()) ));
	            }
	        }
		} else {
			$this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_forum.flash.topic.flood_control', array(), 'CCDNForumForumBundle'));
		}
		
        // setup crumb trail.
        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(), $this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.topic.reply', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_topic_reply', array('topicId' => $topic->getId())), "edit");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:reply.html.' . $this->getEngine(), array(
            'user' => $user,
            'crumbs' => $crumbs,
            'topic' => $topic,
            //'preview' => $formHandler->getForm()->getData(),
            'form' => $formHandler->getForm()->createView(),
        ));
    }

    /**
     *
     * @access protected
     * @return string
     */
    protected function getEngine()
    {
        return $this->container->getParameter('ccdn_forum_forum.template.engine');
    }
}
