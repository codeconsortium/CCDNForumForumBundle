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

namespace CCDNForum\ForumBundle\Form\Handler\Moderator\Topic;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicMoveEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\Forum;
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
class TopicChangeBoardFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicChangeBoardFormType $formTopicChangeBoardType
     */
    protected $formTopicChangeBoardType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $oldBoard
     */
    protected $oldBoard;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected $forum;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface               $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                       $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicChangeBoardFormType $formTopicChangeBoardType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\TopicModel                        $topicModel
     * @param \CCDNForum\ForumBundle\Model\FrontModel\BoardModel                        $boardModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory,
     $formTopicChangeBoardType, ModelInterface $topicModel, ModelInterface $boardModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formTopicChangeBoardType = $formTopicChangeBoardType;
        $this->topicModel = $topicModel;
        $this->boardModel = $boardModel;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Forum                                             $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\Topic\TopicChangeBoardFormHandler
     */
    public function setForum(Forum $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                                             $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\Topic\TopicChangeBoardFormHandler
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            // Store the old board before proceeding, as we will need it to update its stats
            // as it will be a topic down in its count at least, posts perhaps even more so.
            $this->oldBoard = $this->topic->getBoard();

            // Boards are pre-filtered for proper rights managements, moderators may move Topics,
            // but some boards may only be accessible by admins, so moderators should not see them.
            $filteredBoards = $this->boardModel->findAllBoardsForForumById($this->forum->getId());

            $options = array('boards' => $filteredBoards);

            $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_INITIALISE, new ModeratorTopicEvent($this->request, $this->topic));

            $this->form = $this->factory->create($this->formTopicChangeBoardType, $this->topic, $options);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected function onSuccess(Topic $topic)
    {
        $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_SUCCESS, new ModeratorTopicEvent($this->request, $topic));

        $this->topicModel->updateTopic($topic);

        $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_CHANGE_BOARD_COMPLETE, new ModeratorTopicMoveEvent($this->request, $this->oldBoard, $topic->getBoard(), $topic));
    }
}
