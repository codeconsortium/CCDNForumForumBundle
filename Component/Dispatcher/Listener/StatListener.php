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

namespace CCDNForum\ForumBundle\Component\Dispatcher\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;

use CCDNForum\ForumBundle\Entity\Board;
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
class StatListener implements EventSubscriberInterface
{
    /**
     *
     * @access private
     * @var \CCDNForum\ForumBundle\Model\Model\BoardModel $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\TopicModel $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\PostModel $postModel
     */
    protected $postModel;

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\Model\boardModel $boardModel
     * @param \CCDNForum\ForumBundle\Model\Model\topicModel $topicModel
     * @param \CCDNForum\ForumBundle\Model\Model\PostModel  $postModel
     */
    public function __construct($boardModel, $topicModel, $postModel)
    {
        $this->boardModel = $boardModel;
        $this->topicModel = $topicModel;
        $this->postModel = $postModel;
    }

    /**
     *
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ForumEvents::USER_TOPIC_CREATE_COMPLETE           => 'onTopicCreateComplete',
            ForumEvents::USER_TOPIC_REPLY_COMPLETE            => 'onTopicReplyComplete',
            ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_COMPLETE => 'onTopicSoftDeleteComplete',
            ForumEvents::MODERATOR_TOPIC_RESTORE_COMPLETE     => 'onTopicRestoreComplete',
        );
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent $event
     */
    public function onTopicCreateComplete(UserTopicEvent $event)
    {
        $this->updateBoardStats($event->getTopic());
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent $event
     */
    public function onTopicReplyComplete(UserTopicEvent $event)
    {
        $this->updateTopicStats($event->getTopic());
        $this->updateBoardStats($event->getTopic());
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent $event
     */
    public function onTopicSoftDeleteComplete(ModeratorTopicEvent $event)
    {
        $this->updateBoardStats($event->getTopic());
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent $event
     */
    public function onTopicRestoreComplete(ModeratorTopicEvent $event)
    {
        $this->updateBoardStats($event->getTopic());
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected function updateTopicStats(Topic $topic)
    {
        if ($topic->getId()) {
            // Get stats.
            $topicPostCount = $this->postModel->countPostsForTopicById($topic->getId());
            $topicFirstPost = $this->postModel->getFirstPostForTopicById($topic->getId());
            $topicLastPost = $this->postModel->getLastPostForTopicById($topic->getId());

            // Set the board / topic last post.
            $topic->setCachedReplyCount($topicPostCount > 0 ? --$topicPostCount : 0);
            $topic->setFirstPost($topicFirstPost ?: null);
            $topic->setLastPost($topicLastPost ?: null);

            $this->topicModel->updateTopic($topic);
        }
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected function updateBoardStats(Topic $topic)
    {
        if ($topic) {
            if ($topic->getId()) {
                $board = $topic->getBoard();

                if ($board) {
                    if ($board->getId()) {
                        $stats = $this->topicModel->getTopicAndPostCountForBoardById($board->getId());
    //
    //			        // set the board topic / post count
    //			        $board->setCachedTopicCount($stats['topicCount']);
    //			        $board->setCachedPostCount($stats['postCount']);

                        $lastTopic = $this->topicModel->findLastTopicForBoardByIdWithLastPost($board->getId());

                        // set last_post for board
                        if ($lastTopic) {
                            $board->setLastPost($lastTopic->getLastPost() ?: null);
                        } else {
                            $board->setLastPost(null);
                        }

                        $this->boardModel->updateBoard($board);
                    }
                }
            }
        }
    }

//    /**
//     *
//     * @access public
//     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function updateTopicStats(Topic $topic)
//    {
//        $postModel = $this->model->getModelBag()->getPostModel();
//
//        // Get stats.
//        $topicPostCount = $postModel->countPostsForTopicById($topic->getId());
//        $topicFirstPost = $postModel->getFirstPostForTopicById($topic->getId());
//        $topicLastPost = $postModel->getLastPostForTopicById($topic->getId());
//
//        // Set the board / topic last post.
//        $topic->setCachedReplyCount($topicPostCount > 0 ? --$topicPostCount : 0);
//        $topic->setFirstPost($topicFirstPost ?: null);
//        $topic->setLastPost($topicLastPost ?: null);
//
//        $this->persist($topic)->flush();
//
//        return $this;
//    }

//    /**
//     *
//     * @access public
//     * @param  \CCDNForum\ForumBundle\Entity\Board                 $board
//     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
//     */
//    public function updateBoardStats(Board $board)
//    {
//        $boardModel = $this->model->getModelBag()->getBoardModel();
//
//        $stats = $boardModel->getTopicAndPostCountForBoardById($board->getId());
//
//        // set the board topic / post count
//        $board->setCachedTopicCount($stats['topicCount']);
//        $board->setCachedPostCount($stats['postCount']);
//
//        $topicModel = $this->model->getModelBag()->getTopicModel();
//
//        $lastTopic = $topicModel->findLastTopicForBoardByIdWithLastPost($board->getId());
//
//        // set last_post for board
//        if ($lastTopic) {
//            $board->setLastPost($lastTopic->getLastPost() ?: null);
//        } else {
//            $board->setLastPost(null);
//        }
//
//        $this->persist($board)->flush();
//
//        return $this;
//    }
//	protected function updateUserStats(Topic $topic)
//	{
//
//	}
}
