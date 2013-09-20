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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicResponseEvent;

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

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canStickyTopic($topic, $forum));

        $this->getTopicModel()->sticky($topic, $this->getUser());

		$this->dispatch(ForumEvents::MODERATOR_TOPIC_UNSTICKY_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show',
			array(
				'forumName' => $forumName,
				'topicId' => $topic->getId()
			)
		));
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

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canUnstickyTopic($topic, $forum));

        $this->getTopicModel()->unsticky($topic);

		$this->dispatch(ForumEvents::MODERATOR_TOPIC_UNSTICKY_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show',
			array(
				'forumName' => $forumName,
				'topicId' => $topic->getId()
			)
		));
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

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);

        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canCloseTopic($topic, $forum));

        $this->getTopicModel()->close($topic, $this->getUser())->flush();

		$this->dispatch(ForumEvents::MODERATOR_TOPIC_CLOSE_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show',
			array(
				'forumName' => $forumName,
				'topicId' => $topic->getId()
			)
		));
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
		
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canReopenTopic($topic, $forum));

        $this->getTopicModel()->reopen($topic)->flush();

		$this->dispatch(ForumEvents::MODERATOR_TOPIC_REOPEN_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show',
			array(
				'forumName' => $forumName,
				'topicId' => $topic->getId()
			)
		));
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

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canDeleteTopic($topic, $forum));

        $formHandler = $this->getFormHandlerToDeleteTopic($forum, $topic);

        // setup crumb trail.
		$crumbs = $this->getCrumbs()->addModeratorTopicDelete($forum, $topic);

        $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/delete.html.',
			array(
	            'crumbs' => $crumbs,
				'forum' => $forum,
	            'topic' => $topic,
	            'form' => $formHandler->getForm()->createView(),
	        )
		);
		
		$this->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
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

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canDeleteTopic($topic, $forum));

        $formHandler = $this->getFormHandlerToDeleteTopic($forum, $topic);

        if ($formHandler->process()) {
            // Page of the last post.
            //$page = $this->getTopicModel()->getPageForPostOnTopic($topic, $topic->getLastPost());

			$this->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

            $response = $this->redirectResponse(
				$this->path('ccdn_forum_user_topic_show',
					array(
						'forumName' => $forum->getName(),
						'topicId' => $topicId,
						//'page' => $page
					)
				) // . '#' . $topic->getLastPost()->getId()
			);
        } else {
	        // setup crumb trail.
			$crumbs = $this->getCrumbs()->addModeratorTopicDelete($forum, $topic);

	        $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/delete.html.',
				array(
		            'crumbs' => $crumbs,
					'forum' => $forum,
		            'topic' => $topic,
		            'form' => $formHandler->getForm()->createView(),
		        )
			);
        }
		
		$this->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
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

		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId, true);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canRestoreTopic($topic, $forum));

        $this->getTopicModel()->restore($topic)->flush();

        // set flash message
        //$this->setFlash('notice', $this->trans('flash.topic.restore.success', array('%topic_title%' => $topic->getTitle())));

        // forward user
        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show',
			array(
				'forumName' => $forumName,
				'topicId' => $topic->getId()
			)
		));
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
		
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canTopicChangeBoard($topic, $forum));

        $formHandler = $this->getFormHandlerToChangeBoardOnTopic($forum, $topic);

		$crumbs = $this->getCrumbs()->addModeratorTopicChangeBoard($forum, $topic);

        $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/change_board.html.',
			array(
                'crumbs' => $crumbs,
				'forum' => $forum,
                'topic' => $topic,
                'form' => $formHandler->getForm()->createView(),
            )
		);

		$this->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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
		
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);

		$this->isAuthorised($this->getAuthorizer()->canTopicChangeBoard($topic, $forum));

        $formHandler = $this->getFormHandlerToChangeBoardOnTopic($forum, $topic);

        if ($formHandler->process($this->getRequest())) {
			$this->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_COMPLETE, new ModeratorTopicEvent($this->getRequest(), $topic));

            $response = $this->redirectResponse($this->path('ccdn_forum_user_topic_show',
				array(
					'forumName' => $forumName,
					'topicId' => $topic->getId()
				)
			));
        } else {
			$crumbs = $this->getCrumbs()->addModeratorTopicChangeBoard($forum, $topic);

            $response = $this->renderResponse('CCDNForumForumBundle:Moderator:Topic/change_board.html.',
				array(
	                'crumbs' => $crumbs,
					'forum' => $forum,
	                'topic' => $topic,
	                'form' => $formHandler->getForm()->createView(),
	            )
			);
        }

		$this->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_RESPONSE, new ModeratorTopicResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

		return $response;
    }
}
