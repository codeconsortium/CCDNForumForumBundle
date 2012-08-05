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

namespace CCDNForum\ForumBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CCDNForumForumExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'ccdn_forum_forum';
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('ccdn_forum_forum.template.engine', $config['template']['engine']);

        $container->setParameter('ccdn_forum_forum.user.profile_route', $config['user']['profile_route']);

        $this->getSEOSection($container, $config);
        $this->getCategorySection($container, $config);
        $this->getBoardSection($container, $config);
        $this->getTopicSection($container, $config);
        $this->getPostSection($container, $config);

        $this->getItemBoardSection($container, $config);
        $this->getItemPostSection($container, $config);
        $this->getItemSignatureSection($container, $config);

        $this->getDraftSection($container, $config);
        $this->getSubscriptionSection($container, $config);
        $this->getTranscriptSection($container, $config);
    }

    /**
     *
     * @access protected
     * @param $container, $config
     */
    protected function getSEOSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.seo.title_length', $config['seo']['title_length']);
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getCategorySection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.category.last_post_datetime_format', $config['category']['last_post_datetime_format']);
//		$container->setParameter('ccdn_forum_forum.category.enable_bb_parser', $config['category']['enable_bb_parser']);
        $container->setParameter('ccdn_forum_forum.category.index.layout_template', $config['category']['index']['layout_template']);
        $container->setParameter('ccdn_forum_forum.category.show.layout_template', $config['category']['show']['layout_template']);
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getBoardSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.board.show.layout_template', $config['board']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.board.show.topics_per_page', $config['board']['show']['topics_per_page']);
        $container->setParameter('ccdn_forum_forum.board.show.topic_title_truncate', $config['board']['show']['topic_title_truncate']);
        $container->setParameter('ccdn_forum_forum.board.show.first_post_datetime_format', $config['board']['show']['first_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.board.show.last_post_datetime_format', $config['board']['show']['last_post_datetime_format']);
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getTopicSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.topic.show.layout_template', $config['topic']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.show.posts_per_page', $config['topic']['show']['posts_per_page']);
        $container->setParameter('ccdn_forum_forum.topic.show.topic_closed_datetime_format', $config['topic']['show']['topic_closed_datetime_format']);
        $container->setParameter('ccdn_forum_forum.topic.show.topic_deleted_datetime_format', $config['topic']['show']['topic_deleted_datetime_format']);

        $container->setParameter('ccdn_forum_forum.topic.create.layout_template', $config['topic']['create']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.create.form_theme', $config['topic']['create']['form_theme']);
        $container->setParameter('ccdn_forum_forum.topic.create.enable_bb_editor', $config['topic']['create']['enable_bb_editor']);

        $container->setParameter('ccdn_forum_forum.topic.reply.layout_template', $config['topic']['reply']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.reply.form_theme', $config['topic']['reply']['form_theme']);
        $container->setParameter('ccdn_forum_forum.topic.reply.enable_bb_editor', $config['topic']['reply']['enable_bb_editor']);

    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getPostSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.post.show.layout_template', $config['post']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.show.topic_closed_datetime_format', $config['post']['show']['topic_closed_datetime_format']);
        $container->setParameter('ccdn_forum_forum.post.show.topic_deleted_datetime_format', $config['post']['show']['topic_deleted_datetime_format']);

        $container->setParameter('ccdn_forum_forum.post.flag.layout_template', $config['post']['flag']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.flag.form_theme', $config['post']['flag']['form_theme']);

        $container->setParameter('ccdn_forum_forum.post.edit_topic.layout_template', $config['post']['edit_topic']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.edit_topic.form_theme', $config['post']['edit_topic']['form_theme']);
        $container->setParameter('ccdn_forum_forum.post.edit_topic.enable_bb_editor', $config['post']['edit_topic']['enable_bb_editor']);

        $container->setParameter('ccdn_forum_forum.post.edit_post.layout_template', $config['post']['edit_post']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.edit_post.form_theme', $config['post']['edit_post']['form_theme']);
        $container->setParameter('ccdn_forum_forum.post.edit_post.enable_bb_editor', $config['post']['edit_post']['enable_bb_editor']);

        $container->setParameter('ccdn_forum_forum.post.delete_post.layout_template', $config['post']['delete_post']['layout_template']);
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getItemBoardSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.item_board.enable_bb_parser', $config['item_board']['enable_bb_parser']);

    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getItemPostSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.item_post.post_created_datetime_format', $config['item_post']['post_created_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.post_edited_datetime_format', $config['item_post']['post_edited_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.post_locked_datetime_format', $config['item_post']['post_locked_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.post_deleted_datetime_format', $config['item_post']['post_deleted_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.enable_bb_parser', $config['item_post']['enable_bb_parser']);

    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getItemSignatureSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.item_signature.enable_bb_parser', $config['item_signature']['enable_bb_parser']);

    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getDraftSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.draft.list.layout_template', $config['draft']['list']['layout_template']);
        $container->setParameter('ccdn_forum_forum.draft.list.drafts_per_page', $config['draft']['list']['drafts_per_page']);
        $container->setParameter('ccdn_forum_forum.draft.list.topic_title_truncate', $config['draft']['list']['topic_title_truncate']);
        $container->setParameter('ccdn_forum_forum.draft.list.creation_datetime_format', $config['draft']['list']['creation_datetime_format']);
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getSubscriptionSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.subscription.list.layout_template', $config['subscription']['list']['layout_template']);
        $container->setParameter('ccdn_forum_forum.subscription.list.topics_per_page', $config['subscription']['list']['topics_per_page']);
        $container->setParameter('ccdn_forum_forum.subscription.list.topic_title_truncate', $config['subscription']['list']['topic_title_truncate']);
        $container->setParameter('ccdn_forum_forum.subscription.list.first_post_datetime_format', $config['subscription']['list']['first_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.subscription.list.last_post_datetime_format', $config['subscription']['list']['last_post_datetime_format']);
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getTranscriptSection($container, $config)
    {
        $container->setParameter('ccdn_forum_forum.transcript.post_creation_datetime_format', $config['transcript']['post_creation_datetime_format']);
        $container->setParameter('ccdn_forum_forum.transcript.post_deleted_datetime_format', $config['transcript']['post_deleted_datetime_format']);
    }

}
