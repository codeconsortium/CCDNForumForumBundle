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

namespace CCDNForum\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class TopicType extends AbstractType
{
	
	
	/**
	 *
	 * @access protected
	 */
	protected $defaults = array();
	
	
	
	/**
	 *
	 * @access public
	 * @param array() $defaults
	 */
	public function setDefaultValues(array $defaults = null)
	{
		$this->defaults = array_merge($this->defaults, $defaults);
		
		return $this;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param FormBuilder $builder, Array() $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		if (array_key_exists('choose_board', $this->defaults))
		{
			if ($this->defaults['choose_board'])
			{			
				if (array_key_exists('board', $this->defaults))
				{
					$preferredChoices = array($this->defaults['board']->getName() => $this->defaults['board']->getId());
				} else {
					$preferredChoices = array();
				} 
				
				$builder
					->add('board', 'entity', array(
						'class' => 'CCDNForumForumBundle:Board',
						'query_builder' => function($repository)  { return $repository->createQueryBuilder('b')->orderBy('b.id', 'ASC'); },
						'property' => 'name',
						'preferred_choices' => $preferredChoices,					
					));
			}
		}	
		
		$builder->add('title');
	}
	
	

	/**
	 *
	 * for creating and replying to topics
	 *
	 * @access public
	 * @param Array() $options
	 */
	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class' => 'CCDNForum\ForumBundle\Entity\Topic',
            'empty_data' => new \CCDNForum\ForumBundle\Entity\Topic(),
			'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'topic_item',
			'validation_groups' => 'topic',
		);
	}
	
	
	
	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getName()
	{
		return 'Topic';
	}

}