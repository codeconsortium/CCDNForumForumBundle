<?php

/*
 * This file is part of the CCDNForum AdminBundle
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher;

//use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent;

//use CCDNForum\ForumBundle\Model\BaseModelInterface;
use CCDNForum\ForumBundle\Entity\Forum;
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
class TopicChangeBoardFormHandler
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Form\FormFactory $factory
     */
    protected $factory;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\TopicChangeBoardType $formTopicChangeBoardType
     */
    protected $formTopicChangeBoardType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\BaseModelInterface $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\BaseModelInterface $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\TopicChangeBoardType $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $oldBoard
     */
    protected $oldBoard;

    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     */
    protected $dispatcher;

    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected $forum;

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                        $factory
     * @param \CCDNForum\ForumBundle\Form\Type\TopicChangeBoardType                      $formTopicChangeBoardType
     * @param \CCDNForum\ForumBundle\Model\BaseModelInterface                            $topicModel
     * @param \CCDNForum\ForumBundle\Model\BaseModelInterface                            $boardModel
     */
    public function __construct(ContainerAwareTraceableEventDispatcher $dispatcher, FormFactory $factory, $formTopicChangeBoardType, $topicModel, $boardModel)
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
     * @param  \CCDNForum\ForumBundle\Entity\Forum                             $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicChangeBoardFormHandler
     */
    public function setForum(Forum $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                             $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicChangeBoardFormHandler
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function process()
    {
        $this->getForm();

        if ($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            // Validate
            if ($this->form->isValid()) {
                $formData = $this->form->getData();

                if ($this->getSubmitAction() == 'post') {
                    $this->onSuccess($formData);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getSubmitAction()
    {
        if ($this->request->request->has('submit')) {
            $action = key($this->request->request->get('submit'));
        } else {
            $action = 'post';
        }

        return $action;
    }

    /**
     *
     * @access public
     * @return Form
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

            $this->form = $this->factory->create($this->formTopicChangeBoardType, $this->topic, $options);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param $entity
     * @return TopicModel
     */
    protected function onSuccess(Topic $topic)
    {
        $this->topicModel->updateTopic($topic);

        // Update stats of the topics old board.
        if ($this->oldBoard) {
        //    $this->boardModel->updateStats($this->oldBoard)->flush();
        }

        // Setup stats on the topics new board.
        if ($topic->getBoard()) {
        //    $this->boardModel->updateStats($topic->getBoard())->flush();
        }

        return $this->topicModel;
    }
}
