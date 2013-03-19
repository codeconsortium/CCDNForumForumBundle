<?php

/*
 * This file is part of the CCDNForum AdminBundle
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

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicChangeBoardType extends AbstractType
{
    /**
     *
     * @access public
     * @param FormBuilderInterface $builder, array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('board', 'entity', array(
            'property' => 'name',
            'class' => 'CCDNForumForumBundle:Board',
            'query_builder' => function($qb) { return $qb->createQueryBuilder('b')->orderBy('b.id', 'ASC'); },
            'label' => 'ccdn_forum_forum.form.label.board',
			'translation_domain' =>  'CCDNForumForumBundle',
        ));
    }

    /**
     *
     * @access public
     * @param array $options
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CCDNForum\ForumBundle\Entity\Topic',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'topic_change_board',
        );
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'TopicChangeBoard';
    }
}