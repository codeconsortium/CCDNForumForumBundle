<?php

/*
 * This file is part of the CCDN ForumBundle
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
class FlagFormHandler
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
     * @param Array() $options
     * @return $this
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
            $this->form->bindRequest($this->request);

            $formData = $this->form->getData();

            $formData->setPost($this->defaults['post']);
            $formData->setFlaggedDate(new \DateTime());
            $formData->setFlaggedBy($this->defaults['user']);
            $formData->setStatus(0);

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
            $flagType = $this->container->get('ccdn_forum_forum.flag.form.type');
            $flagType->setDefaultValues(array('flag_default_choices' => $this->container->get('ccdn_forum_forum.flag.form.default_choices')));
            $this->form = $this->factory->create($flagType);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param $entity
     * @return FlagManager
     */
    protected function onSuccess($entity)
    {
        return $this->manager->insert($entity)->flush();
    }

}
