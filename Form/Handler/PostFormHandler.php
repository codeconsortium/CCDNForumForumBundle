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
	 * @param FormFactory $factory, ContainerInterface $container, EntityManagerInterface $manager
	 */
	public function __construct(FormFactory $factory, ContainerInterface $container)
	{
		$this->defaults = array();
		$this->factory = $factory;
		$this->container = $container;

		// topic manager is the default unless the mode is changed.
		$this->mode = self::NORMAL;
		$this->manager = $this->container->get('ccdn_forum_forum.post.manager');
		
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
		switch($mode)
		{
			case self::NORMAL:
				$this->mode = self::NORMAL;
				$this->manager = $this->container->get('ccdn_forum_forum.topic.manager');
			break;
			case self::PREVIEW:
				$this->mode = self::PREVIEW;
				$this->manager = $this->container->get('ccdn_forum_forum.topic.manager');
			break;
			case self::DRAFT:
				$this->mode = self::DRAFT;
				$this->manager = $this->container->get('ccdn_forum_forum.draft.manager');
			break;
		}
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
			
		if ($this->request->getMethod() == 'POST')
		{
			$formData = $this->form->getData();

			//
			// INSERT topic
			//
			if ($this->strategy == self::INSERT)
			{
				$formData->setCreatedDate(new \DateTime());
				$formData->setCreatedBy($this->defaults['user']);
				$formData->setTopic($this->defaults['topic']);
			}

			//
			// UPDATE topic
			//
			if ($this->strategy == self::UPDATE)
			{
				$formData = $this->form->getData();		
				$formData->setEditedDate(new \DateTime());
				$formData->setEditedBy($this->defaults['user']);
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
	 * @access public
	 * @return Form
	 */
	public function getForm()
	{
		
		if ( ! $this->form)
		{
			$postType = $this->container->get('ccdn_forum_forum.post.form.type');
			$postType->setDefaultValues($this->defaults);

			//
			// INSERT topic
			//
			if ($this->strategy == self::INSERT)
			{
				// post for draft
				if (array_key_exists('post', $this->defaults))
				{
					$this->form = $this->factory->create($postType, $this->defaults['post']);				
				} else {
					$this->form = $this->factory->create($postType);			
				}
				
				//$this->form = $this->factory->create($postType);
			}
			
			//
			// UPDATE topic
			//
			if ($this->strategy == self::UPDATE)
			{
				$this->form = $this->factory->create($postType, $this->defaults['post']);
			}
			
			if ($this->request->getMethod() == 'POST')
			{
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
		// INSERT topic
		//
		if ($this->strategy == self::INSERT)
		{
			return $this->manager->insert($entity)->flushNow();
		}
		
		//
		// UPDATE topic
		//
		if ($this->strategy == self::UPDATE)
		{
			return $this->manager->update($entity)->flushNow();
		}
    }



	/**
	 *
	 *
	 * @access public
	 */
	public function getCounters()
	{
		return $this->manager->getCounters();
	}
	
}