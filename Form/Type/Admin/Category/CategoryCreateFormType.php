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

namespace CCDNForum\ForumBundle\Form\Type\Admin\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
class CategoryCreateFormType extends AbstractType
{
    /**
     *
     * @access protected
     * @var string $categoryClass
     */
    protected $categoryClass;

    /**
     *
     * @access protected
     * @var string $forumClass
     */
    protected $forumClass;

    /**
     *
     * @access protected
     * @var Object $roleHelper
     */
    protected $roleHelper;

    /**
     *
     * @access public
     * @var string $categoryClass
     * @var string $forumClass
     * @var Object $roleHelper
     */
    public function __construct($categoryClass, $forumClass, $roleHelper)
    {
        $this->categoryClass = $categoryClass;
		$this->forumClass = $forumClass;
		$this->roleHelper = $roleHelper;
    }

    /**
     *
     * @access public
     * @param FormBuilderInterface $builder, array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forum', 'entity',
                array(
					'property'           => 'name',
					'class'              => $this->forumClass,
					'data'               => $options['default_forum'],
					'required'           => false,
                    'label'              => 'form.label.forum',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('name', 'text',
                array(
                    'label'              => 'form.label.forum.name',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('readAuthorisedRoles', 'choice',
                array(
                    'required'           => false,
                    'expanded'           => true,
                    'multiple'           => true,
                    'choices'            => $options['available_roles'],
                    'label'              => 'form.label.board.view_roles',
                    'translation_domain' => 'CCDNForumAdminBundle',
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
            'intention'           => 'forum_category_create_item',
            'validation_groups'   => array('forum_category_create'),
            'cascade_validation'  => true,
			'available_roles'     => $this->roleHelper->getRoleHierarchy(),
			'default_forum'       => null
        );
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Forum_CategoryCreate';
    }
}
