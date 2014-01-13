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

namespace CCDNForum\ForumBundle\Form\Type\Admin\Forum;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
class ForumCreateFormType extends AbstractType
{
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
     * @param string $forumClass
     * @param Object $roleHelper
     */
    public function __construct($forumClass, $roleHelper)
    {
        $this->forumClass = $forumClass;
        $this->roleHelper = $roleHelper;
    }

    /**
     *
     * @access public
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text',
                array(
                    'label'              => 'forum.name-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('readAuthorisedRoles', 'choice',
                array(
                    'required'           => false,
                    'expanded'           => true,
                    'multiple'           => true,
                    'choices'            => $options['available_roles'],
                    'label'              => 'forum.roles.board-view-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
        ;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'          => $this->forumClass,
            'csrf_protection'     => true,
            'csrf_field_name'     => '_token',
            // a unique key to help generate the secret token
            'intention'           => 'forum_forum_create_item',
            'validation_groups'   => array('forum_forum_create'),
            'cascade_validation'  => true,
            'available_roles'     => $this->roleHelper->getRoleHierarchy(),
        ));
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Forum_ForumCreate';
    }
}
