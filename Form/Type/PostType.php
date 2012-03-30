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
use Symfony\Component\Form\FormBuilder;

use CCDNForum\ForumBundle\Entity\Post;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class PostType extends AbstractType
{

	/**
	 *
	 * @access private
	 */
	private $options;



	/**
	 *
	 * @access private
	 */
	protected $container;
	
	
	
	/**
	 *
	 * @access private
	 */
	protected $doctrine;
	
	
	
	/**
	 *
	 *
	 */
	public function __construct($service_container)
	{
		$this->options = array();
		
		$this->container = $service_container;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param Array() $options
	 */
	public function setOptions($options = array())
	{
		$this->options = $options;
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param FormBuilder $builder, Array() $options
	 */
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('body', 'textarea', array(
			'data' => $this->getQuote(),
		));
		
		$userId = $this->options['user']->getId();
		$attachments = $this->container->get('ccdn_component_attachment.attachment.repository')->findForUserByIdAsQB($userId);
		
		$builder->add('attachment', 'entity', array(
		    'class' => 'CCDNComponentAttachmentBundle:Attachment',
//		    'query_builder' => $this->container->get('ccdn_component_attachment.attachment.repository')->findForUserByIdAsQB($userId),
			'choices' => $attachments,
		    'property' => 'attachment_original',
			'required' => false,
			)
		);
	}
	
	
	
	/**
	 *
	 * @access public
	 * @return String
	 */
	public function getQuote()
	{
		if (array_key_exists('quote', $this->options))
		{
			if (is_object($this->options['quote']) && $this->options['quote'] instanceof Post)
			{
				$quote = $this->options['quote'];
			
				$author = $quote->getCreatedBy();
				$body = $quote->getBody();
			
				$quote = '[QUOTE=' . $author . ']' . $body . '[/QUOTE]';

				return $quote;
			}
		}

		return "";
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
			'data_class' => 'CCDNForum\ForumBundle\Entity\Post',
            'empty_data' => new \CCDNForum\ForumBundle\Entity\Post(),
			'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'post_item',
		);
	}



	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getName()
	{
		return 'Post';
	}
	
}
