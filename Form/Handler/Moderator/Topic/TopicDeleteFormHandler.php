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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;

//use CCDNForum\ForumBundle\Model\BaseModelInterface;
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
class TopicDeleteFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicType $formTopicType
     */
    protected $formTopicType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\BaseModelInterface $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicType $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected $topic;

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
     * @var \Symfony\Component\Security\Core\User\UserInterface
     */
    protected $user;

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                        $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicType                 $formTopicType
     * @param \CCDNForum\ForumBundle\Model\BaseModelInterface                            $topicModel
     */
    public function __construct(ContainerAwareTraceableEventDispatcher $dispatcher, FormFactory $factory, $formTopicType, $topicModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formTopicType = $formTopicType;
        $this->topicModel = $topicModel;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\Security\Core\User\UserInterface                  $user
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\TopicDeleteFormHandler
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                                        $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\Topic\TopicDeleteFormHandler
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
            if (! is_object($this->topic) || ! ($this->topic instanceof Topic)) {
                throw new \Exception('Topic must be specified to delete in TopicDeleteFormHandler');
            }

            $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_INITIALISE, new ModeratorTopicEvent($this->request, $this->topic));

            $this->form = $this->factory->create($this->formTopicType, $this->topic);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Topic     $topic
     * @return \CCDNForum\ForumBundle\Model\TopicModel
     */
    protected function onSuccess(Topic $topic)
    {
        $this->topicModel->softDelete($topic, $this->user)->flush();

        $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_SUCCESS, new ModeratorTopicEvent($this->request, $this->topic));

        return $this->topicModel;
    }
}
