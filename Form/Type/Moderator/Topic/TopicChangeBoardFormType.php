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

namespace CCDNForum\ForumBundle\Form\Type\Moderator\Topic;

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
class TopicChangeBoardFormType extends AbstractType
{
    /**
     *
     * @access protected
     * @var string $topicClass
     */
    protected $topicClass;

    /**
     *
     * @access protected
     * @var string $boardClass
     */
    protected $boardClass;

    /**
     *
     * @access public
     * @param string $topicClass
     * @param string $boardClass
     */
    public function __construct($topicClass, $boardClass)
    {
        $this->topicClass = $topicClass;
        $this->boardClass = $boardClass;
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
            ->add('board', 'entity',
                array(
                    'property'           => 'name',
                    'class'              => $this->boardClass,
                    'choices'            => $options['boards'],
                    'label'              => 'topic.board-label',
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
            'data_class'         => $this->topicClass,
            'csrf_protection'    => true,
            'csrf_field_name'    => '_token',
            // a unique key to help generate the secret token
            'intention'          => 'forum_topic_change_board_item',
            'validation_groups'  => array('forum_topic_change_board'),
            'boards'             => array(),
        ));
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
