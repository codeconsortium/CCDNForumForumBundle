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
     * @access protected
     * @var string $env
     */
    protected $env;

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

        $this->env = $container->getParameter('kernel.environment');

        // Class file namespaces.
        $this->getEntitySection($config, $container);
        $this->getGatewaySection($config, $container);
        $this->getRepositorySection($config, $container);
        $this->getManagerSection($config, $container);
        $this->getModelSection($config, $container);
        $this->getFormSection($config, $container);
        $this->getComponentSection($config, $container);

        // Configuration stuff.
        $container->setParameter('ccdn_forum_forum.template.engine', $config['template']['engine']);
        $container->setParameter('ccdn_forum_forum.template.pager_theme', $config['template']['pager_theme']);

        $this->getFixtureReferenceSection($config, $container);
        $this->getSEOSection($config, $container);
        $this->getForumSection($config, $container);
        $this->getCategorySection($config, $container);
        $this->getBoardSection($config, $container);
        $this->getTopicSection($config, $container);
        $this->getPostSection($config, $container);
        $this->getItemPostSection($config, $container);
        $this->getSubscriptionSection($config, $container);

        // Load Service definitions.
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('services/components.yml');
        $loader->load('services/model-gateway.yml');
        $loader->load('services/model-repository.yml');
        $loader->load('services/model-manager.yml');
        $loader->load('services/model.yml');
        $loader->load('services/forms-forum.yml');
        $loader->load('services/forms-category.yml');
        $loader->load('services/forms-board.yml');
        $loader->load('services/forms-topic.yml');
        $loader->load('services/forms-post.yml');
        $loader->load('services/twig-extensions.yml');
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
        $container->setParameter('ccdn_forum_forum.component.integrator.dashboard.class', $config['component']['integrator']['dashboard']['class']);
        $container->setParameter('ccdn_forum_forum.component.crumb_factory.class', $config['component']['crumb_factory']['class']);
        $container->setParameter('ccdn_forum_forum.component.crumb_builder.class', $config['component']['crumb_builder']['class']);
        $container->setParameter('ccdn_forum_forum.component.security.authorizer.class', $config['component']['security']['authorizer']['class']);
        $container->setParameter('ccdn_forum_forum.component.flood_control.class', $config['component']['flood_control']['class']);
        $container->setParameter('ccdn_forum_forum.component.helper.role.class', $config['component']['helper']['role']['class']);
        $container->setParameter('ccdn_forum_forum.component.helper.pagination_config.class', $config['component']['helper']['pagination_config']['class']);
        $container->setParameter('ccdn_forum_forum.component.helper.post_lock.class', $config['component']['helper']['post_lock']['class']);
        $container->setParameter('ccdn_forum_forum.component.twig_extension.board_list.class', $config['component']['twig_extension']['board_list']['class']);
        $container->setParameter('ccdn_forum_forum.component.twig_extension.authorizer.class', $config['component']['twig_extension']['authorizer']['class']);
        $container->setParameter('ccdn_forum_forum.component.event_listener.flash.class', $config['component']['event_listener']['flash']['class']);
        $container->setParameter('ccdn_forum_forum.component.event_listener.subscriber.class', $config['component']['event_listener']['subscriber']['class']);
        $container->setParameter('ccdn_forum_forum.component.event_listener.stats.class', $config['component']['event_listener']['stats']['class']);

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
    private function getForumSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_forum_forum.forum.admin.create.layout_template', $config['forum']['admin']['create']['layout_template']);
        $container->setParameter('ccdn_forum_forum.forum.admin.create.form_theme', $config['forum']['admin']['create']['form_theme']);

        $container->setParameter('ccdn_forum_forum.forum.admin.edit.layout_template', $config['forum']['admin']['edit']['layout_template']);
        $container->setParameter('ccdn_forum_forum.forum.admin.edit.form_theme', $config['forum']['admin']['edit']['form_theme']);

        $container->setParameter('ccdn_forum_forum.forum.admin.delete.layout_template', $config['forum']['admin']['delete']['layout_template']);
        $container->setParameter('ccdn_forum_forum.forum.admin.delete.form_theme', $config['forum']['admin']['delete']['form_theme']);

        $container->setParameter('ccdn_forum_forum.forum.admin.list.layout_template', $config['forum']['admin']['list']['layout_template']);

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
        $container->setParameter('ccdn_forum_forum.category.admin.create.layout_template', $config['category']['admin']['create']['layout_template']);
        $container->setParameter('ccdn_forum_forum.category.admin.create.form_theme', $config['category']['admin']['create']['form_theme']);

        $container->setParameter('ccdn_forum_forum.category.admin.edit.layout_template', $config['category']['admin']['edit']['layout_template']);
        $container->setParameter('ccdn_forum_forum.category.admin.edit.form_theme', $config['category']['admin']['edit']['form_theme']);

        $container->setParameter('ccdn_forum_forum.category.admin.delete.layout_template', $config['category']['admin']['delete']['layout_template']);
        $container->setParameter('ccdn_forum_forum.category.admin.delete.form_theme', $config['category']['admin']['delete']['form_theme']);

        $container->setParameter('ccdn_forum_forum.category.admin.list.layout_template', $config['category']['admin']['list']['layout_template']);

        $container->setParameter('ccdn_forum_forum.category.user.last_post_datetime_format', $config['category']['user']['last_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.category.user.index.layout_template', $config['category']['user']['index']['layout_template']);
        $container->setParameter('ccdn_forum_forum.category.user.show.layout_template', $config['category']['user']['show']['layout_template']);

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
        $container->setParameter('ccdn_forum_forum.board.admin.create.layout_template', $config['board']['admin']['create']['layout_template']);
        $container->setParameter('ccdn_forum_forum.board.admin.create.form_theme', $config['board']['admin']['create']['form_theme']);

        $container->setParameter('ccdn_forum_forum.board.admin.edit.layout_template', $config['board']['admin']['edit']['layout_template']);
        $container->setParameter('ccdn_forum_forum.board.admin.edit.form_theme', $config['board']['admin']['edit']['form_theme']);

        $container->setParameter('ccdn_forum_forum.board.admin.delete.layout_template', $config['board']['admin']['delete']['layout_template']);
        $container->setParameter('ccdn_forum_forum.board.admin.delete.form_theme', $config['board']['admin']['delete']['form_theme']);

        $container->setParameter('ccdn_forum_forum.board.admin.list.layout_template', $config['board']['admin']['list']['layout_template']);

        $container->setParameter('ccdn_forum_forum.board.user.show.layout_template', $config['board']['user']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.board.user.show.topics_per_page', $config['board']['user']['show']['topics_per_page']);
        $container->setParameter('ccdn_forum_forum.board.user.show.topic_title_truncate', $config['board']['user']['show']['topic_title_truncate']);
        $container->setParameter('ccdn_forum_forum.board.user.show.first_post_datetime_format', $config['board']['user']['show']['first_post_datetime_format']);
        $container->setParameter('ccdn_forum_forum.board.user.show.last_post_datetime_format', $config['board']['user']['show']['last_post_datetime_format']);

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
        $container->setParameter('ccdn_forum_forum.topic.moderator.change_board.layout_template', $config['topic']['moderator']['change_board']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.moderator.change_board.form_theme', $config['topic']['moderator']['change_board']['form_theme']);

        $container->setParameter('ccdn_forum_forum.topic.moderator.delete.layout_template', $config['topic']['moderator']['delete']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.moderator.delete.form_theme', $config['topic']['moderator']['delete']['form_theme']);

        if ($this->env == 'dev' || $this->env == 'test') {
            $postLimit = 0;
            $blockForMinutes = 0;
        } else {
            $postLimit = $config['topic']['user']['flood_control']['post_limit'];
            $blockForMinutes = $config['topic']['user']['flood_control']['block_for_minutes'];
        }

        $container->setParameter('ccdn_forum_forum.topic.user.flood_control.post_limit', $postLimit);
        $container->setParameter('ccdn_forum_forum.topic.user.flood_control.block_for_minutes', $blockForMinutes);

        $container->setParameter('ccdn_forum_forum.topic.user.show.layout_template', $config['topic']['user']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.user.show.posts_per_page', $config['topic']['user']['show']['posts_per_page']);
        $container->setParameter('ccdn_forum_forum.topic.user.show.closed_datetime_format', $config['topic']['user']['show']['closed_datetime_format']);
        $container->setParameter('ccdn_forum_forum.topic.user.show.deleted_datetime_format', $config['topic']['user']['show']['deleted_datetime_format']);

        $container->setParameter('ccdn_forum_forum.topic.user.create.layout_template', $config['topic']['user']['create']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.user.create.form_theme', $config['topic']['user']['create']['form_theme']);

        $container->setParameter('ccdn_forum_forum.topic.user.reply.layout_template', $config['topic']['user']['reply']['layout_template']);
        $container->setParameter('ccdn_forum_forum.topic.user.reply.form_theme', $config['topic']['user']['reply']['form_theme']);

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
        $container->setParameter('ccdn_forum_forum.post.moderator.unlock.layout_template', $config['post']['moderator']['unlock']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.moderator.unlock.form_theme', $config['post']['moderator']['unlock']['form_theme']);

        $container->setParameter('ccdn_forum_forum.post.user.show.layout_template', $config['post']['user']['show']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.user.edit.layout_template', $config['post']['user']['edit']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.user.edit.form_theme', $config['post']['user']['edit']['form_theme']);
        $container->setParameter('ccdn_forum_forum.post.user.delete.layout_template', $config['post']['user']['delete']['layout_template']);
        $container->setParameter('ccdn_forum_forum.post.user.delete.form_theme', $config['post']['user']['delete']['form_theme']);

        $container->setParameter('ccdn_forum_forum.post.user.lock.enable', $config['post']['user']['lock']['enable']);
        $container->setParameter('ccdn_forum_forum.post.user.lock.after_days', $config['post']['user']['lock']['after_days']);

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
