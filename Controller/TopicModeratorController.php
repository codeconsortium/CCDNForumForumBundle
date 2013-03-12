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
class TopicModeratorController extends BaseController
{
    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function stickyAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have access to this section.');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $this->container->get('ccdn_forum_forum.manager.topic')->sticky($topic, $user)->flush();

        $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.sticky.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function unstickyAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have access to this section.');
        }

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $this->container->get('ccdn_forum_forum.manager.topic')->unsticky($topic)->flush();

        $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.unsticky.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * Once a topic is locked, no posts can be added, deleted or edited!
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function closeAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have access to this section.');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $this->container->get('ccdn_forum_forum.manager.topic')->close($topic, $user)->flush();

        $this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.close.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function reopenAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have access to this section.');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $this->container->get('ccdn_forum_forum.manager.topic')->reopen($topic)->flush();

        $this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.reopen.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RenderResponse
     */
    public function deleteAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such post exists!');
        }

        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbDelete = $this->container->get('translator')->trans('crumbs.topic.delete', array(), 'CCDNForumForumBundle');

        // setup crumb trail.
        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($category->getName(),	$this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
            ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
            ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
            ->add($crumbDelete, $this->container->get('router')->generate('ccdn_forum_forum_topic_reply', array('topicId' => $topic->getId())), "trash");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:delete_topic.html.' . $this->getEngine(), array(
            'topic' => $topic,
            'crumbs' => $crumbs,
        ));
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function deleteConfirmedAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $this->container->get('ccdn_forum_forum.manager.topic')->softDelete($topic, $user)->flush();

        // set flash message
        $this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.delete.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

        // forward user
        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $topic->getBoard()->getId()) ));
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function restoreAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $this->container->get('ccdn_forum_forum.manager.topic')->restore($topic)->flush();

        // set flash message
        $this->container->get('session')->setFlash('notice', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.restore.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

        // forward user
        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $topic->getBoard()->getId()) ));
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function moveAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedException('You do not have access to this section.');
        }

        $topic = $this->container->get('ccdn_forum_forum.repository.topic')->find($topicId);

        if (! $topic) {
            throw new NotFoundHttpException('No such topic exists!');
        }

        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.change_topics_board')->setDefaultValues(array('topic' => $topic));

        if ($formHandler->process()) {
            $this->container->get('session')->setFlash('warning', $this->container->get('translator')->trans('ccdn_forum_admin.flash.topic.move.success', array('%topic_title%' => $topic->getTitle()), 'CCDNForumAdminBundle'));

            return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
        } else {
            $board = $topic->getBoard();
            $category = $board->getCategory();

            // setup crumb trail.
            $crumbs = $this->container->get('ccdn_component_crumb.trail')
                ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
                ->add($category->getName(), $this->container->get('router')->generate('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())), "category")
                ->add($board->getName(), $this->container->get('router')->generate('ccdn_forum_forum_board_show', array('boardId' => $board->getId())), "board")
                ->add($topic->getTitle(), $this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())), "communication")
                ->add($this->container->get('translator')->trans('ccdn_forum_admin.crumbs.topic.change_board', array(), 'CCDNForumAdminBundle'),
					$this->container->get('router')->generate('ccdn_forum_forum_topic_change_board', array('topicId' => $topic->getId())), "edit");

            return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Topic:change_board.html.' . $this->getEngine(), array(
                'crumbs' => $crumbs,
                'topic' => $topic,
                'form' => $formHandler->getForm()->createView(),
            ));
        }
    }
}