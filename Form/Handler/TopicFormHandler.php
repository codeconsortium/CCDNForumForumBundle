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

namespace CCDNForum\ForumBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

use CCDNForum\ForumBundle\Manager\ManagerInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicFormHandler
{

    /**
     *
     * @access protected
     */
    protected $factory;

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     * @access protected
     */
    protected $request;

    /**
     *
     * @access protected
     */
    protected $manager;

    /**
     *
     * @access protected
     */
    protected $defaults = array();

    /**
     *
     * @access protected
     */
    protected $form;

    /**
     *
     * @access protected
     */
    protected $strategy;
    const INSERT = 0;
    const UPDATE = 1;

    /**
     *
     * @access protected
     */
    protected $mode;
    const NORMAL = 0;
    const PREVIEW = 1;
    const DRAFT = 2;

    /**
     *
     * @access public
     * @param FormFactory $factory, ContainerInterface $container, ManagerInterface $manager
     */
    public function __construct(FormFactory $factory, ContainerInterface $container)
    {
        $this->defaults = array();
        $this->factory = $factory;
        $this->container = $container;

        // topic manager is the default unless the mode is changed.
//        $this->mode = self::NORMAL;
        $this->manager = $this->container->get('ccdn_forum_forum.manager.topic');

        // set insert as default strategy
        $this->strategy = self::INSERT;

        $this->request = $container->get('request');
    }

    /**
     *
     * @access public
     */
    public function useInsertStrategy()
    {
        $this->strategy = self::INSERT;
    }

    /**
     *
     * @access public
     */
    public function useUpdateStrategy()
    {
        $this->strategy = self::UPDATE;
    }

    /**
     *
     * @access public
	 * @param int $mode
     */
    public function setMode($mode)
    {
        switch ($mode) {
            case self::NORMAL:
                $this->mode = self::NORMAL;
                $this->manager = $this->container->get('ccdn_forum_forum.manager.topic');
            break;
            case self::PREVIEW:
                $this->mode = self::PREVIEW;
                $this->manager = $this->container->get('ccdn_forum_forum.manager.topic');
            break;
            case self::DRAFT:
                $this->mode = self::DRAFT;
                $this->manager = $this->container->get('ccdn_forum_forum.manager.draft');
            break;
        }
    }

    /**
     *
     * @access public
     * @param array $defaults
     * @return self
     */
    public function setDefaultValues(array $defaults = null)
    {
        $this->defaults = array_merge($this->defaults, $defaults);

        return $this;
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
            $formData = $this->form->getData();

            //
            // INSERT topic
            //
            if ($this->strategy == self::INSERT) {
                $formData->setCreatedDate(new \DateTime());
                $formData->setCreatedBy($this->defaults['user']);
                $formData->setIsLocked(false);
                $formData->setIsDeleted(false);

                $formData->getTopic()->setCachedViewCount(0);
                $formData->getTopic()->setCachedReplyCount(0);
                $formData->getTopic()->setIsClosed(false);
                $formData->getTopic()->setIsDeleted(false);
                $formData->getTopic()->setIsSticky(false);

                $board = $formData->getTopic()->getBoard();
            }

            //
            // UPDATE topic
            //
            if ($this->strategy == self::UPDATE) {
                // get the current time, and compare to when the post was made.
                $now = new \DateTime();
                $interval = $now->diff($formData->getCreatedDate());

                // if post is less than 15 minutes old, don't add that it was edited.
                if ($interval->format('%i') > 15) {
                    $formData->setEditedDate(new \DateTime());
                    $formData->setEditedBy($this->defaults['user']);
                }
            }

            //
            // Validate
            //
            if ($this->form->isValid()) {
                $this->onSuccess($this->form->getData());

                return true;
            }
        }

        return false;
    }

    /**
     *
     * @access public
     * @return Form
     */
    public function getForm()
    {

        if (! $this->form) {
            $postType = $this->container->get('ccdn_forum_forum.form.type.post');
            $postType->setDefaultValues($this->defaults);
            $topicType = $this->container->get('ccdn_forum_forum.form.type.topic');

            // set for insert method
            if ($this->strategy == self::INSERT) {
//                if (array_key_exists('board', $this->defaults)) {
//                    $topicType->setDefaultValues(array('choose_board' => true, 'board' => $this->defaults['board']));
//                }

                $post = new Post();

                $topic = new Topic();

                $topic->setPost($post);
                $topic->setBoard($this->defaults['board']);

                $this->form = $this->factory->create($topicType);

//                // post for draft
//                if (array_key_exists('post', $this->defaults)) {
//                    $this->form = $this->factory->create($postType, $this->defaults['post']);
//                } else {
//                    $this->form = $this->factory->create($postType);
//                }
//
//                // topic for draft
//                if (array_key_exists('topic', $this->defaults)) {
//                    $this->form->add($this->factory->create($topicType, $this->defaults['topic']));
//                } else {
//                    $this->form->add($this->factory->create($topicType));
//                }
            }

            // set if in update method
            if ($this->strategy == self::UPDATE) {
//                if (array_key_exists('board', $this->defaults)) {
//                    $topicType->setDefaultValues(array('choose_board' => true, 'board' => $this->defaults['board']));
//                }

                $topic = $this->defaults['post']->getTopic();
                $topic->setBoard($this->defaults['board']);

                $this->form = $this->factory->create($postType, $this->defaults['post']);
                $this->form->add($this->factory->create($topicType, $topic));
            }

            if ($this->request->getMethod() == 'POST') {
                $this->form->bind($this->request);
            }
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param $entity
     * @return TopicManager
     */
    protected function onSuccess($entity)
    {

        if ($this->strategy == self::INSERT) {
            return $this->manager->create($entity)->flush();
        }

        if ($this->strategy == self::UPDATE) {
            return $this->manager->update($entity)->flush();
        }
    }

}
