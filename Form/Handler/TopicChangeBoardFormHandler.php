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

namespace CCDNForum\ForumBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
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
     * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $topicManager
     */
    protected $topicManager;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $boardManager
     */
    protected $boardManager;

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
     * @access public
     * @param \Symfony\Component\Form\FormFactory                   $factory
     * @param \CCDNForum\ForumBundle\Form\Type\TopicChangeBoardType $formTopicChangeBoardType
     * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface   $topicManager
     * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface   $boardManager
     */
    public function __construct(FormFactory $factory, $formTopicChangeBoardType, BaseManagerInterface $topicManager, BaseManagerInterface $boardManager)
    {
        $this->factory = $factory;
        $this->formTopicChangeBoardType = $formTopicChangeBoardType;
        $this->topicManager = $topicManager;
        $this->boardManager = $boardManager;
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
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function process(Request $request)
    {
        $this->getForm();

        if ($request->getMethod() == 'POST') {
            $this->form->bind($request);

            // Validate
            if ($this->form->isValid()) {
                $formData = $this->form->getData();

                if ($this->getSubmitAction($request) == 'post') {
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
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function getSubmitAction(Request $request)
    {
        if ($request->request->has('submit')) {
            $action = key($request->request->get('submit'));
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
            $filteredBoards = $this->boardManager->findAllForFormDropDown();
            $options = array('boards' => $filteredBoards);

            $this->form = $this->factory->create($this->formTopicChangeBoardType, $this->topic, $options);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param $entity
     * @return TopicManager
     */
    protected function onSuccess(Topic $topic)
    {
        $this->topicManager->updateTopic($topic)->flush();

        // Update stats of the topics old board.
        if ($this->oldBoard) {
            $this->boardManager->updateStats($this->oldBoard)->flush();
        }

        // Setup stats on the topics new board.
        if ($topic->getBoard()) {
            $this->boardManager->updateStats($topic->getBoard())->flush();
        }

        return $this->topicManager;
    }
}
