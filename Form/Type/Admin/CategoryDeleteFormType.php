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

namespace CCDNForum\ForumBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use CCDNForum\ForumBundle\Entity\Forum;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class CategoryDeleteFormType extends AbstractType
{
    /**
     *
     * @access protected
     * @var string $categoryClass
     */
    protected $categoryClass;

    /**
     *
     * @access public
     * @var string $categoryClass
     */
    public function __construct($categoryClass)
    {
        $this->categoryClass = $categoryClass;
    }

    /**
     *
     * @access public
     * @param FormBuilderInterface $builder, array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('confirm_delete', 'choice',
				array(
					'choices' => array(
						'delete_forum' => 'Yes, I want to delete this forum.',
					),
					'multiple' => true,
					'expanded' => true,
					'mapped' => false
				)
			)
			->add('confirm_subordinates', 'choice',
				array(
					'choices' => array(
						'delete_subordinates' => 'Also delete categories, boards and topics.'
					),
					'multiple' => true,
					'expanded' => true,
					'mapped' => false
				)
			)
        ;
    }

    /**
     *
     * @access public
     * @param  array $options
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'          => $this->categoryClass,
            'csrf_protection'     => true,
            'csrf_field_name'     => '_token',
            // a unique key to help generate the secret token
            'intention'           => 'forum_category_delete_item',
            'validation_groups'   => array('forum_category_delete'),
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
        return 'Forum_CategoryDelete';
    }
}
