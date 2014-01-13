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

namespace CCDNForum\ForumBundle\Form\Type\Admin\Board;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

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
class BoardCreateFormType extends AbstractType
{
    /**
     *
     * @access protected
     * @var string $boardClass
     */
    protected $boardClass;

    /**
     *
     * @access protected
     * @var string $categoryClass
     */
    protected $categoryClass;

    /**
     *
     * @access protected
     * @var Object $roleHelper
     */
    protected $roleHelper;

    /**
     *
     * @access public
     * @param string $boardClass
     * @param string $categoryClass
     * @param Object $roleHelper
     */
    public function __construct($boardClass, $categoryClass, $roleHelper)
    {
        $this->boardClass = $boardClass;
        $this->categoryClass = $categoryClass;
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
            ->add('category', 'entity',
                array(
                    'property'           => 'name',
                    'class'              => $this->categoryClass,
                    'group_by'           => 'category.forum.name',
                    'query_builder'      =>
                        function (EntityRepository $er) {
                            return $er
                                ->createQueryBuilder('c')
                                ->leftJoin('c.forum', 'f')
                                //->groupBy('c.forum')
                            ;
                        },
                    'data'               => $options['default_category'],
                    'required'           => false,
                    'label'              => 'category.label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('name', 'text',
                array(
                    'label'              => 'board.name-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('description', 'textarea',
                array(
                    'label'              => 'board.description-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('readAuthorisedRoles', 'choice',
                array(
                    'required'           => false,
                    'expanded'           => true,
                    'multiple'           => true,
                    'choices'            => $options['available_roles'],
                    'label'              => 'board.roles.topic-view-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('topicCreateAuthorisedRoles', 'choice',
                array(
                    'required'           => false,
                    'expanded'           => true,
                    'multiple'           => true,
                    'choices'            => $options['available_roles'],
                    'label'              => 'board.roles.topic-create-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                )
            )
            ->add('topicReplyAuthorisedRoles', 'choice',
                array(
                    'required'           => false,
                    'expanded'           => true,
                    'multiple'           => true,
                    'choices'            => $options['available_roles'],
                    'label'              => 'board.roles.topic-reply-label',
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
            'data_class'          => $this->boardClass,
            'csrf_protection'     => true,
            'csrf_field_name'     => '_token',
            // a unique key to help generate the secret token
            'intention'           => 'forum_board_create_item',
            'validation_groups'   => array('forum_board_create'),
            'cascade_validation'  => true,
            'available_roles'     => $this->roleHelper->getRoleHierarchy(),
            'default_category'    => null
        ));
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Forum_BoardCreate';
    }
}
