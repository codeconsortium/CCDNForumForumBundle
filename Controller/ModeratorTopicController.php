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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicResponseEvent;

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
class ModeratorTopicController extends ModeratorTopicBaseController
{
    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function stickyAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canStickyTopic($topic, $forum));
        $this->getTopicModel()->sticky($topic, $this->getUser());
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_UNSTICKY_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId()
        )));
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function unstickyAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canUnstickyTopic($topic, $forum));
        $this->getTopicModel()->unsticky($topic);
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_UNSTICKY_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId()
        )));
    }

    /**
     *
     * Once a topic is locked, no posts can be added, deleted or edited!
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function closeAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId));
        $this->isAuthorised($this->getAuthorizer()->canCloseTopic($topic, $forum));
        $this->getTopicModel()->close($topic, $this->getUser())->flush();
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_CLOSE_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId()
        )));
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function reopenAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId));
        $this->isAuthorised($this->getAuthorizer()->canReopenTopic($topic, $forum));
        $this->getTopicModel()->reopen($topic)->flush();
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_REOPEN_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId()
        )));
    }

    /**
     *
     * @access public
     * @param  string         $forumName
     * @param  int            $topicId
     * @return RenderResponse
     */
    public function deleteAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canDeleteTopic($topic, $forum));
        $formHandler = $this->getFormHandlerToDeleteTopic($topic);
        $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/delete.html.', array(
            'crumbs' => $this->getCrumbs()->addModeratorTopicDelete($forum, $topic),
            'forum' => $forum,
            'forumName' => $forumName,
            'topic' => $topic,
            'form' => $formHandler->getForm()->createView(),
        ));
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string         $forumName
     * @param  int            $topicId
     * @return RenderResponse
     */
    public function deleteProcessAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canDeleteTopic($topic, $forum));
        $formHandler = $this->getFormHandlerToDeleteTopic($topic);

        if ($formHandler->process()) {
            $response = $this->redirectResponseForTopicOnPageFromPost($forumName, $topic, $topic->getLastPost());
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/delete.html.', array(
                'crumbs' => $this->getCrumbs()->addModeratorTopicDelete($forum, $topic),
                'forum' => $forum,
                'forumName' => $forumName,
                'topic' => $topic,
                'form' => $formHandler->getForm()->createView(),
            ));
        }
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function restoreAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true));
        $this->isAuthorised($this->getAuthorizer()->canRestoreTopic($topic, $forum));
        $this->getTopicModel()->restore($topic)->flush();
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_RESTORE_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId()
        )));
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function changeBoardAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId));
        $this->isAuthorised($this->getAuthorizer()->canTopicChangeBoard($topic, $forum));
        $formHandler = $this->getFormHandlerToChangeBoardOnTopic($forum, $topic);
        $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/change_board.html.', array(
            'crumbs' => $this->getCrumbs()->addModeratorTopicChangeBoard($forum, $topic),
            'forum' => $forum,
            'forumName' => $forumName,
            'topic' => $topic,
            'form' => $formHandler->getForm()->createView(),
        ));
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $topicId
     * @return RedirectResponse|RenderResponse
     */
    public function changeBoardProcessAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_MODERATOR');
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId));
        $this->isAuthorised($this->getAuthorizer()->canTopicChangeBoard($topic, $forum));
        $formHandler = $this->getFormHandlerToChangeBoardOnTopic($forum, $topic);

        if ($formHandler->process()) {
            $response = $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('forumName' => $forumName, 'topicId' => $topic->getId() )));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/change_board.html.', array(
                'crumbs' => $this->getCrumbs()->addModeratorTopicChangeBoard($forum, $topic),
                'forum' => $forum,
                'forumName' => $forumName,
                'topic' => $topic,
                'form' => $formHandler->getForm()->createView(),
            ));
        }
        $this->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }
}
