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
use Symfony\Component\DependencyInjection\ContainerInterface;

use CCDNForum\ForumBundle\Manager\ManagerInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicChangeBoardFormHandler
{

    /** @access protected */
    protected $factory;

    /** @access protected */
    protected $container;

    /** @access protected */
    protected $request;

    /** @access protected */
    protected $manager;

    /** @access protected */
    protected $defaults = array();

    /** @access protected */
    protected $form;

    /** @access protected */
    protected $oldBoard;

    /**
     *
     * @access public
     * @param FormFactory $factory, ContainerInterface $container, ManagerInterface $manager
     */
    public function __construct(FormFactory $factory, ContainerInterface $container, ManagerInterface $manager)
    {
        $this->defaults = array();
        $this->factory = $factory;
        $this->container = $container;
        $this->manager = $manager;

        $this->request = $container->get('request');
    }

    /**
     *
     * @access public
     * @param array $options
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
            $this->form->bind($this->request);

            $formData = $this->form->getData();

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
        if (!$this->form) {
            $this->oldBoard = $this->defaults['topic']->getBoard();

            $topicChangeBoardType = $this->container->get('ccdn_forum_forum.form.type.change_topics_board');
			
            $topicChangeBoardType->setDefaultValues(array(
				'board' => $this->defaults['topic']->getBoard()
			));
            
			$this->form = $this->factory->create($topicChangeBoardType, $this->defaults['topic']);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param $entity
     * @return TopicManager
     */
    protected function onSuccess($topic)
    {
        $this->manager->update($topic)->flush();

        $boardManager = $this->container->get('ccdn_forum_forum.manager.board');

        //
        // Update stats of the topics old board.
        //
        if ($this->oldBoard) {
            $boardManager->updateStats($this->oldBoard)->flush();
        }

        //
        // Setup stats on the topics new board.
        //
        if ($topic->getBoard()) {
            $boardManager->updateStats($topic->getBoard())->flush();
        }
    }
}
