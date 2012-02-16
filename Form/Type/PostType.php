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
	

	public function __construct()
	{
		$this->options = array();
	}
	
	
	/**
	 *
	 * @access public
	 * @param Array() $options
	 */
	public function setOptions(array $options)
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
		if (array_key_exists('quote', $this->options))
		{
			$quote = $this->options['quote'];
			
			$author = $quote->getCreatedBy();
			$body = $quote->getBody();
			
			$quote = '[QUOTE=' . $author . ']' . $body . '[/QUOTE]';

			$builder->add('body', 'textarea', array(
				'data' => $quote,
			));
		} else {
			$builder->add('body', 'textarea');
		}

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
