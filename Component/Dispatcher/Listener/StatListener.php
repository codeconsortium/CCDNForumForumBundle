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

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent;

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
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
     * @access public
     * @param \CCDNForum\ForumBundle\Model\Model\boardModel $boardModel
     * @param \CCDNForum\ForumBundle\Model\Model\topicModel $topicModel
     */
    public function __construct($boardModel, $topicModel)
    {
        $this->boardModel = $boardModel;
        $this->topicModel = $topicModel;
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
			$this->topicModel->updateStats($topic);
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
					$this->boardModel->updateStats($board);
				}
			}
		}
	}

//	protected function updateUserStats(Topic $topic)
//	{
//		
//	}
}
