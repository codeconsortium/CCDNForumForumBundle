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

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints\NotBlank;

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
class ForumDeleteFormType extends AbstractType
{
    /**
     *
     * @access protected
     * @var string $forumClass
     */
    protected $forumClass;

    /**
     *
     * @access public
     * @param string $forumClass
     */
    public function __construct($forumClass)
    {
        $this->forumClass = $forumClass;
    }

    /**
     *
     * @access public
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trueValidator = function (FormEvent $event) {
            $form = $event->getForm();

            $confirm = $form->get('confirm_delete')->getData();

            if (empty($confirm) || $confirm == false) {
                $form['confirm_delete']->addError(new FormError("You must confirm this action."));
            }
        };

        $builder
            ->add('confirm_delete', 'checkbox',
                array(
                    'mapped'             => false,
                    'required'           => true,
                    'label'              => 'forum.confirm-delete-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                    'constraints'        => array(
                        new True(),
                        new NotBlank()
                    ),
                )
            )
            ->add('confirm_subordinates', 'checkbox',
                array(
                    'mapped'             => false,
                    'required'           => true,
                    'label'              => 'forum.confirm-delete-subordinates-label',
                    'translation_domain' => 'CCDNForumForumBundle',
                    'constraints'        => array(
                        new True(),
                        new NotBlank()
                    ),
                )
            )
            ->addEventListener(FormEvents::POST_BIND, $trueValidator)
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
            'intention'           => 'forum_forum_delete_item',
            'validation_groups'   => array('forum_forum_delete'),
            'cascade_validation'  => true,
        ));
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Forum_ForumDelete';
    }
}
