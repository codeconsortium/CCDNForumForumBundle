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

use CCDNComponent\CommonBundle\Entity\Manager\EntityManagerInterface;

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
	protected $options;
	
	
	
	/**
	 *
	 * @access protected
	 */
	protected $form;



	/**
	 *
	 * @access protected
	 */
	protected $previewMode;
	
	
	
	/**
	 *
	 * @access protected
	 */
	protected $previewData;
	
	
	
	/**
	 *
	 * @access protected
	 */
	protected $strategy; 
	const INSERT = 0;
	const UPDATE = 1;
	
	
	
	/**
	 *
	 * @access public
	 */
	public function setInsert()
	{
		$this->strategy = self::INSERT;
	}
	
	

	/**
	 *
	 * @access public
	 */
	public function setUpdate()	
	{
		$this->strategy = self::UPDATE;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param FormFactory $factory, ContainerInterface $container, EntityManagerInterface $manager
	 */
	public function __construct(FormFactory $factory, ContainerInterface $container, EntityManagerInterface $manager)
	{
		$this->options = array();
		$this->factory = $factory;
		$this->container = $container;
		$this->manager = $manager;

		$this->request = $container->get('request');
		
		$this->previewMode = false;
	}

	
	
	/**
	 *
	 * @access public
	 * @param Array() $options
	 * @return $this
	 */
	public function setOptions($options = null )
	{
		$this->options = $options;
		
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
		
		if ($this->request->getMethod() == 'POST')
		{
			
			$this->form->bindRequest($this->request);
		
			$formData = $this->form->getData();

			//
			// INSERT topic
			//
			if ($this->strategy == self::INSERT)
			{
				$formData->setCreatedDate(new \DateTime());
				$formData->setCreatedBy($this->options['user']);

				$formData->getTopic()->setViewCount(0);
				$formData->getTopic()->setReplyCount(0);

				$formData->getTopic()->setBoard($this->options['board']);				
			}

			//
			// UPDATE topic
			//
			if ($this->strategy == self::UPDATE)
			{
				$formData->setEditedDate(new \DateTime());
				$formData->setEditedBy($this->options['user']);
			}
			
			//
			// Validate
			//			
			if ($this->form->isValid())
			{	
				$this->onSuccess($this->form->getData());
				
				return true;				
			}
		}

		return false;
	}
	
	
	
	/**
	 *
	 *
	 * @access public
	 */
	public function previewMode()
	{
		$this->previewMode = true;	
	}
	
	
	
	/**
	 *
	 *
	 * @access public
	 */
	public function getPreview()
	{
		// we need to use the getForm to ensure a preview is created
		// incase the form createView method is invoked after this 
		// method. Otherwise we won't have any preview data.
		$this->getForm();
		
		return $this->previewData;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @return Form
	 */
	public function getForm()
	{
		
		if ( ! $this->form)
		{
			$postType = $this->container->get('ccdn_forum_forum.post.form.type');
			$postType->setOptions($this->options);
			$topicType = $this->container->get('ccdn_forum_forum.topic.form.type');
			
			// set for insert method
			if ($this->strategy == self::INSERT)
			{
				$this->form = $this->factory->create($postType);			
				$this->form->add($this->factory->create($topicType));
			}
			
			// set if in update method
			if ($this->strategy == self::UPDATE)
			{
				$this->form = $this->factory->create($postType, $this->options['post']);
				$this->form->add($this->factory->create($topicType, $this->options['post']->getTopic()));
			}			
			
			//
			// Preview mode
			//
			if ($this->previewMode)
			{				
				$this->form->bindRequest($this->request);
				$this->previewData = $this->form->getData();
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

		if ($this->strategy == self::INSERT)
		{
			return $this->manager->insert($entity)->flushNow();		
		}

		if ($this->strategy == self::UPDATE)
		{
			return $this->manager->update($entity)->flushNow();
		}
    }



	/**
	 *
	 * @access protected
	 */
	public function getCounters()
	{
		return $this->manager->getCounters();
	}

}