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
class PostFormHandler
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
        $this->mode = self::NORMAL;
        $this->manager = $this->container->get('ccdn_forum_forum.manager.post');

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
     *
     * @access public
     */
    public function setMode($mode)
    {
        switch ($mode) {
            case self::NORMAL:
                $this->mode = self::NORMAL;
                $this->manager = $this->container->get('ccdn_forum_forum.manager.post');
            break;
            case self::PREVIEW:
                $this->mode = self::PREVIEW;
                $this->manager = $this->container->get('ccdn_forum_forum.manager.post');
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
                $formData->setTopic($this->defaults['topic']);
                $formData->setIsLocked(false);
                $formData->setIsDeleted(false);
            }

            //
            // UPDATE topic
            //
            if ($this->strategy == self::UPDATE) {
                $formData = $this->form->getData();

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

            //
            // INSERT topic
            //
            if ($this->strategy == self::INSERT) {
                // post for draft
                if (array_key_exists('post', $this->defaults)) {
                    $this->form = $this->factory->create($postType, $this->defaults['post']);
                } else {
                    $this->form = $this->factory->create($postType);
                }

                //$this->form = $this->factory->create($postType);
            }

            //
            // UPDATE topic
            //
            if ($this->strategy == self::UPDATE) {
                $this->form = $this->factory->create($postType, $this->defaults['post']);
            }

            if ($this->request->getMethod() == 'POST') {
                $this->form->bindRequest($this->request);
            }
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param $entity
     * @return PostManager
     */
    protected function onSuccess($entity)
    {
        //
        // INSERT post
        //
        if ($this->strategy == self::INSERT) {
            return $this->manager->create($entity)->flush();
        }

        //
        // UPDATE post
        //
        if ($this->strategy == self::UPDATE) {
            return $this->manager->update($entity)->flush();
        }
    }

}
