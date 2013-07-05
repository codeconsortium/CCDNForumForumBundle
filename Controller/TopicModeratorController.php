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

use CCDNForum\ForumBundle\Entity\Topic;

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
class TopicModeratorController extends TopicBaseController
{
    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function stickyAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToStickyTopic($topic);

        $this->getTopicModel()->sticky($topic, $this->getUser())->flush();

        $this->setFlash('success', $this->trans('flash.topic.sticky.success', array('%topic_title%' => $topic->getTitle())));

        return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function unstickyAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToUnStickyTopic($topic);

        $this->getTopicModel()->unsticky($topic)->flush();

        $this->setFlash('notice', $this->trans('flash.topic.unsticky.success', array('%topic_title%' => $topic->getTitle())));

        return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * Once a topic is locked, no posts can be added, deleted or edited!
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function closeAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToCloseTopic($topic);

        $this->getTopicModel()->close($topic, $this->getUser())->flush();

        $this->setFlash('warning', $this->trans('flash.topic.close.success', array('%topic_title%' => $topic->getTitle())));

        return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function reopenAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToReOpenTopic($topic);

        $this->getTopicModel()->reopen($topic)->flush();

        $this->setFlash('warning', $this->trans('flash.topic.reopen.success', array('%topic_title%' => $topic->getTitle())));

        return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
    }

    /**
     *
     * @access public
     * @param  int            $topicId
     * @return RenderResponse
     */
    public function deleteAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToDeleteTopic($topic);

        $board = $topic->getBoard();
        $category = $board->getCategory();

        $crumbDelete = $this->trans('crumbs.topic.delete');

        // setup crumb trail.
        $crumbs = $this->getCrumbs()
            ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_forum_category_index'))
            ->add($category->getName(),	$this->path('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())))
            ->add($board->getName(), $this->path('ccdn_forum_forum_board_show', array('boardId' => $board->getId())))
            ->add($topic->getTitle(), $this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())))
            ->add($crumbDelete, $this->path('ccdn_forum_forum_topic_reply', array('topicId' => $topic->getId())));

        return $this->renderResponse('CCDNForumForumBundle:Topic:delete_topic.html.', array(
            'topic' => $topic,
            'crumbs' => $crumbs,
        ));
    }

    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function deleteConfirmedAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToDeleteTopic($topic);

        $this->getTopicModel()->softDelete($topic, $this->getUser())->flush();

        // set flash message
        $this->setFlash('warning', $this->trans('flash.topic.delete.success', array('%topic_title%' => $topic->getTitle())));

        // forward user
        return $this->redirectResponse($this->path('ccdn_forum_forum_board_show', array('boardId' => $topic->getBoard()->getId()) ));
    }

    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function restoreAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToRestoreTopic($topic);

        $this->getTopicModel()->restore($topic)->flush();

        // set flash message
        $this->setFlash('notice', $this->trans('flash.topic.restore.success', array('%topic_title%' => $topic->getTitle())));

        // forward user
        return $this->redirectResponse($this->path('ccdn_forum_forum_board_show', array('boardId' => $topic->getBoard()->getId()) ));
    }

    /**
     *
     * @access public
     * @param  int                             $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function moveAction($topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');

        $topic = $this->getTopicModel()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);
        $this->isAuthorisedToMoveTopic($topic);

        $formHandler = $this->getFormHandlerToChangeBoardOnTopic($topic);

        if ($formHandler->process($this->getRequest())) {
            $this->setFlash('warning', $this->trans('flash.topic.success.move', array('%topic_title%' => $topic->getTitle())));

            return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId()) ));
        } else {
            $board = $topic->getBoard();
            $category = $board->getCategory();

            // setup crumb trail.
            $crumbs = $this->getCrumbs()
                ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_forum_category_index'))
                ->add($category->getName(), $this->path('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())))
                ->add($board->getName(), $this->path('ccdn_forum_forum_board_show', array('boardId' => $board->getId())))
                ->add($topic->getTitle(), $this->path('ccdn_forum_forum_topic_show', array('topicId' => $topic->getId())))
                ->add($this->trans('crumbs.topic.move'), $this->path('ccdn_forum_forum_topic_change_board', array('topicId' => $topic->getId())));

            return $this->renderResponse('CCDNForumForumBundle:Topic:change_board.html.', array(
                'crumbs' => $crumbs,
                'topic' => $topic,
                'form' => $formHandler->getForm()->createView(),
            ));
        }
    }
}
