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

namespace CCDNForum\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use CCDNForum\ForumBundle\Entity\Post;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostCreateFormType extends AbstractType
{
	/**
	 *
	 * @access protected
	 * @var string $postClass
	 */
	protected $postClass;
	
	/**
	 *
	 * @access public
	 * @var string $postClass
	 */
	public function __construct($postClass)
	{
		$this->postClass = $postClass;
	}
	
    /**
     *
     * @access public
     * @param FormBuilderInterface $builder, array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('body', 'bb_editor',
				array(
					'label'              => 'ccdn_forum_forum.form.label.post.body',
					'translation_domain' => 'CCDNForumForumBundle'
		        )
			)
		;
    }

    /**
     *
     * @access public
     * @param array $options
	 * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'          => $this->postClass,
            'csrf_protection'     => true,
            'csrf_field_name'     => '_token',
            // a unique key to help generate the secret token
            'intention'           => 'post_item',
            'validation_groups'   => array('post_create'),
            'cascade_validation'  => true,
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