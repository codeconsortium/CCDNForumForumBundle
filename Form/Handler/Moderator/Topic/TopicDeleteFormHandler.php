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
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
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
class TopicDeleteFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicDeleteFormType $formTopicType
     */
    protected $formTopicType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected $topic;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface          $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                  $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicDeleteFormType $formTopicType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\TopicModel                   $topicModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $formTopicType, ModelInterface $topicModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formTopicType = $formTopicType;
        $this->topicModel = $topicModel;
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
     * @return \Symfony\Component\Form\Form
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
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected function onSuccess(Topic $topic)
    {
        $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_SUCCESS, new ModeratorTopicEvent($this->request, $this->topic));

        $this->topicModel->softDelete($topic, $this->user);

        $this->dispatcher->dispatch(ForumEvents::MODERATOR_TOPIC_SOFT_DELETE_COMPLETE, new ModeratorTopicEvent($this->request, $topic));
    }
}
