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
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class CCDNForumForumExtension extends Extension
{
    /**
     *
     * @access public
     * @return string
     */
    public function getAlias()
    {
        return 'ccdn_forum_forum';
    }

    /**
     *
     * @access public
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        // Class file namespaces.
        $this
            ->getEntitySection($config, $container)
            ->getGatewaySection($config, $container)
            ->getRepositorySection($config, $container)
            ->getManagerSection($config, $container)
            ->getModelSection($config, $container)
            ->getFormSection($config, $container)
            ->getComponentSection($config, $container)
        ;

        // Configuration stuff.
        $container->setParameter('ccdn_forum_forum.template.engine', $config['template']['engine']);

        $this
            ->getFixtureReferenceSection($config, $container)
            ->getSEOSection($config, $container)
            ->getCategorySection($config, $container)
            ->getBoardSection($config, $container)
            ->getTopicSection($config, $container)
            ->getPostSection($config, $container)
            ->getItemPostSection($config, $container)
            ->getSubscriptionSection($config, $container)
        ;

        // Load Service definitions.
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $loader->load('services/components.yml');

        $loader->load('services/model-utils.yml');
        $loader->load('services/model-gateway.yml');
        $loader->load('services/model-repository.yml');
        $loader->load('services/model-manager.yml');
        $loader->load('services/model.yml');

        $loader->load('services/forms-forum.yml');
        $loader->load('services/forms-category.yml');
        $loader->load('services/forms-board.yml');
        $loader->load('services/forms-topic.yml');
        $loader->load('services/forms-post.yml');

        $loader->load('services/twig_extensions.yml');
	}

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getEntitySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.entity.forum.class', $config['entity']['forum']['class']);
        $container->setParameter('ccdn_forum_forum.entity.category.class', $config['entity']['category']['class']);
        $container->setParameter('ccdn_forum_forum.entity.board.class', $config['entity']['board']['class']);
        $container->setParameter('ccdn_forum_forum.entity.topic.class', $config['entity']['topic']['class']);
        $container->setParameter('ccdn_forum_forum.entity.post.class', $config['entity']['post']['class']);
        $container->setParameter('ccdn_forum_forum.entity.subscription.class', $config['entity']['subscription']['class']);
        $container->setParameter('ccdn_forum_forum.entity.registry.class', $config['entity']['registry']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getGatewaySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.gateway.forum.class', $config['gateway']['forum']['class']);
        $container->setParameter('ccdn_forum_forum.gateway.category.class', $config['gateway']['category']['class']);
        $container->setParameter('ccdn_forum_forum.gateway.board.class', $config['gateway']['board']['class']);
        $container->setParameter('ccdn_forum_forum.gateway.topic.class', $config['gateway']['topic']['class']);
        $container->setParameter('ccdn_forum_forum.gateway.post.class', $config['gateway']['post']['class']);
        $container->setParameter('ccdn_forum_forum.gateway.subscription.class', $config['gateway']['subscription']['class']);
        $container->setParameter('ccdn_forum_forum.gateway.registry.class', $config['gateway']['registry']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getRepositorySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.repository.forum.class', $config['repository']['forum']['class']);
        $container->setParameter('ccdn_forum_forum.repository.category.class', $config['repository']['category']['class']);
        $container->setParameter('ccdn_forum_forum.repository.board.class', $config['repository']['board']['class']);
        $container->setParameter('ccdn_forum_forum.repository.topic.class', $config['repository']['topic']['class']);
        $container->setParameter('ccdn_forum_forum.repository.post.class', $config['repository']['post']['class']);
        $container->setParameter('ccdn_forum_forum.repository.subscription.class', $config['repository']['subscription']['class']);
        $container->setParameter('ccdn_forum_forum.repository.registry.class', $config['repository']['registry']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getManagerSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.manager.forum.class', $config['manager']['forum']['class']);
        $container->setParameter('ccdn_forum_forum.manager.category.class', $config['manager']['category']['class']);
        $container->setParameter('ccdn_forum_forum.manager.board.class', $config['manager']['board']['class']);
        $container->setParameter('ccdn_forum_forum.manager.topic.class', $config['manager']['topic']['class']);
        $container->setParameter('ccdn_forum_forum.manager.post.class', $config['manager']['post']['class']);
        $container->setParameter('ccdn_forum_forum.manager.subscription.class', $config['manager']['subscription']['class']);
        $container->setParameter('ccdn_forum_forum.manager.registry.class', $config['manager']['registry']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getModelSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.model_bag.class', $config['model']['bag']['class']);
        $container->setParameter('ccdn_forum_forum.model.utility_bag.class', $config['model']['utility_bag']['class']);
        $container->setParameter('ccdn_forum_forum.model.utility.pagination_config.class', $config['model']['utility']['pagination_config']['class']);

        $container->setParameter('ccdn_forum_forum.model.forum.class', $config['model']['forum']['class']);
        $container->setParameter('ccdn_forum_forum.model.category.class', $config['model']['category']['class']);
        $container->setParameter('ccdn_forum_forum.model.board.class', $config['model']['board']['class']);
        $container->setParameter('ccdn_forum_forum.model.topic.class', $config['model']['topic']['class']);
        $container->setParameter('ccdn_forum_forum.model.post.class', $config['model']['post']['class']);
        $container->setParameter('ccdn_forum_forum.model.subscription.class', $config['model']['subscription']['class']);
        $container->setParameter('ccdn_forum_forum.model.registry.class', $config['model']['registry']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getFormSection(array $config, ContainerBuilder $container)
    {
        // Types
        $container->setParameter('ccdn_forum_forum.form.type.forum_create.class', $config['form']['type']['forum_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.forum_update.class', $config['form']['type']['forum_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.forum_delete.class', $config['form']['type']['forum_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.type.category_create.class', $config['form']['type']['category_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.category_update.class', $config['form']['type']['category_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.category_delete.class', $config['form']['type']['category_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.type.board_create.class', $config['form']['type']['board_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.board_update.class', $config['form']['type']['board_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.board_delete.class', $config['form']['type']['board_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.type.topic_create.class', $config['form']['type']['topic_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.topic_update.class', $config['form']['type']['topic_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.topic_delete.class', $config['form']['type']['topic_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.type.change_topics_board.class', $config['form']['type']['change_topics_board']['class']);

        $container->setParameter('ccdn_forum_forum.form.type.post_create.class', $config['form']['type']['post_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.post_update.class', $config['form']['type']['post_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.post_delete.class', $config['form']['type']['post_delete']['class']);
        $container->setParameter('ccdn_forum_forum.form.type.post_unlock.class', $config['form']['type']['post_unlock']['class']);

        // Handlers
        $container->setParameter('ccdn_forum_forum.form.handler.forum_create.class', $config['form']['handler']['forum_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.forum_update.class', $config['form']['handler']['forum_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.forum_delete.class', $config['form']['handler']['forum_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.handler.category_create.class', $config['form']['handler']['category_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.category_update.class', $config['form']['handler']['category_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.category_delete.class', $config['form']['handler']['category_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.handler.board_create.class', $config['form']['handler']['board_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.board_update.class', $config['form']['handler']['board_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.board_delete.class', $config['form']['handler']['board_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.handler.topic_create.class', $config['form']['handler']['topic_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.topic_update.class', $config['form']['handler']['topic_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.topic_delete.class', $config['form']['handler']['topic_delete']['class']);

        $container->setParameter('ccdn_forum_forum.form.handler.change_topics_board.class', $config['form']['handler']['change_topics_board']['class']);

        $container->setParameter('ccdn_forum_forum.form.handler.post_create.class', $config['form']['handler']['post_create']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.post_update.class', $config['form']['handler']['post_update']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.post_delete.class', $config['form']['handler']['post_delete']['class']);
        $container->setParameter('ccdn_forum_forum.form.handler.post_unlock.class', $config['form']['handler']['post_unlock']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getComponentSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.component.dashboard.integrator.class', $config['component']['dashboard']['integrator']['class']);
        $container->setParameter('ccdn_forum_forum.component.crumb_factory.class', $config['component']['crumb_factory']['class']);
        $container->setParameter('ccdn_forum_forum.component.crumb_builder.class', $config['component']['crumb_builder']['class']);
        $container->setParameter('ccdn_forum_forum.component.security.authorizer.class', $config['component']['security']['authorizer']['class']);
        $container->setParameter('ccdn_forum_forum.component.role_helper.class', $config['component']['role_helper']['class']);
        $container->setParameter('ccdn_forum_forum.component.flood_control.class', $config['component']['flood_control']['class']);
        $container->setParameter('ccdn_forum_forum.component.twig_extension.board_list.class', $config['component']['twig_extension']['board_list']['class']);
        $container->setParameter('ccdn_forum_forum.component.twig_extension.authorizer.class', $config['component']['twig_extension']['authorizer']['class']);
        $container->setParameter('ccdn_forum_forum.component.event_listener.flash.class', $config['component']['event_listener']['flash']['class']);
        $container->setParameter('ccdn_forum_forum.component.event_listener.subscriber.class', $config['component']['event_listener']['subscriber']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getFixtureReferenceSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.fixtures.user_admin', $config['fixtures']['user_admin']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getSEOSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.seo.title_length', $config['seo']['title_length']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getCategorySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.category.last_post_datetime_format', $config['category']['last_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.category.index.layout_template', $config['category']['index']['layout_template']);
        $container->setParameter('ccdn_forum_forum.category.show.layout_template', $config['category']['show']['layout_template']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getBoardSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.board.show.layout_template', $config['board']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.board.show.topics_per_page', $config['board']['show']['topics_per_page']);
        $container->setParameter('ccdn_forum_forum.board.show.topic_title_truncate', $config['board']['show']['topic_title_truncate']);
        $container->setParameter('ccdn_forum_forum.board.show.first_post_datetime_format', $config['board']['show']['first_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.board.show.last_post_datetime_format', $config['board']['show']['last_post_datetime_format']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getTopicSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.topic.flood_control.post_limit', $config['topic']['flood_control']['post_limit']);
        $container->setParameter('ccdn_forum_forum.topic.flood_control.block_for_minutes', $config['topic']['flood_control']['block_for_minutes']);

        $container->setParameter('ccdn_forum_forum.topic.show.layout_template', $config['topic']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.show.posts_per_page', $config['topic']['show']['posts_per_page']);
        $container->setParameter('ccdn_forum_forum.topic.show.closed_datetime_format', $config['topic']['show']['closed_datetime_format']);
        $container->setParameter('ccdn_forum_forum.topic.show.deleted_datetime_format', $config['topic']['show']['deleted_datetime_format']);

        $container->setParameter('ccdn_forum_forum.topic.create.layout_template', $config['topic']['create']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.create.form_theme', $config['topic']['create']['form_theme']);

        $container->setParameter('ccdn_forum_forum.topic.reply.layout_template', $config['topic']['reply']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.reply.form_theme', $config['topic']['reply']['form_theme']);

        $container->setParameter('ccdn_forum_forum.topic.change_board.layout_template', $config['topic']['change_board']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.change_board.form_theme', $config['topic']['change_board']['form_theme']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getPostSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.post.show.layout_template', $config['post']['show']['layout_template']);
        //$container->setParameter('ccdn_forum_forum.post.show.closed_datetime_format', $config['post']['show']['closed_datetime_format']);
        //$container->setParameter('ccdn_forum_forum.post.show.deleted_datetime_format', $config['post']['show']['deleted_datetime_format']);

        $container->setParameter('ccdn_forum_forum.post.edit_topic.layout_template', $config['post']['edit_topic']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.edit_topic.form_theme', $config['post']['edit_topic']['form_theme']);

        $container->setParameter('ccdn_forum_forum.post.edit_post.layout_template', $config['post']['edit_post']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.edit_post.form_theme', $config['post']['edit_post']['form_theme']);

        $container->setParameter('ccdn_forum_forum.post.delete_post.layout_template', $config['post']['delete_post']['layout_template']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getItemPostSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.item_post.created_datetime_format', $config['item_post']['created_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.edited_datetime_format', $config['item_post']['edited_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.post_locked_datetime_format', $config['item_post']['locked_datetime_format']);
        $container->setParameter('ccdn_forum_forum.item_post.deleted_datetime_format', $config['item_post']['deleted_datetime_format']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                              $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder            $container
     * @return \CCDNForum\ForumBundle\DependencyInjection\CCDNForumForumExtension
     */
    private function getSubscriptionSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.subscription.list.layout_template', $config['subscription']['list']['layout_template']);
        $container->setParameter('ccdn_forum_forum.subscription.list.topics_per_page', $config['subscription']['list']['topics_per_page']);
        $container->setParameter('ccdn_forum_forum.subscription.list.topic_title_truncate', $config['subscription']['list']['topic_title_truncate']);
        $container->setParameter('ccdn_forum_forum.subscription.list.first_post_datetime_format', $config['subscription']['list']['first_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.subscription.list.last_post_datetime_format', $config['subscription']['list']['last_post_datetime_format']);

        return $this;
    }
}
