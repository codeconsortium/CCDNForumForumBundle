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

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class }
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
class Configuration implements ConfigurationInterface
{
    /**
     *
     * @access public
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ccdn_forum_forum');

        $rootNode
            ->children()
                ->arrayNode('template')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('engine')->defaultValue('twig')->end()
                    ->end()
                ->end()
            ->end();

        // Class file namespaces.
        $this
            ->addEntitySection($rootNode)
            ->addRepositorySection($rootNode)
            ->addGatewaySection($rootNode)
            ->addManagerSection($rootNode)
            ->addFormSection($rootNode)
            ->addComponentSection($rootNode)
        ;

        // Configuration stuff.
        $this
            ->addFixtureReferenceSection($rootNode)
            ->addSEOSection($rootNode)
            ->addCategorySection($rootNode)
            ->addBoardSection($rootNode)
            ->addTopicSection($rootNode)
            ->addPostSection($rootNode)
            ->addItemPostSection($rootNode)
            ->addDraftSection($rootNode)
            ->addSubscriptionSection($rootNode)
            ->addTranscriptSection($rootNode)
        ;

        return $treeBuilder;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addEntitySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('category')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Category')->end()
                            ->end()
                        ->end()
                        ->arrayNode('board')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Board')->end()
                            ->end()
                        ->end()
                        ->arrayNode('topic')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Topic')->end()
                            ->end()
                        ->end()
                        ->arrayNode('post')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Post')->end()
                            ->end()
                        ->end()
                        ->arrayNode('draft')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Draft')->end()
                            ->end()
                        ->end()
                        ->arrayNode('subscription')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Subscription')->end()
                            ->end()
                        ->end()
                        ->arrayNode('registry')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Entity\Registry')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addRepositorySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('repository')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('category')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\CategoryRepository')->end()
                            ->end()
                        ->end()
                        ->arrayNode('board')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\BoardRepository')->end()
                            ->end()
                        ->end()
                        ->arrayNode('topic')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\TopicRepository')->end()
                            ->end()
                        ->end()
                        ->arrayNode('post')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\PostRepository')->end()
                            ->end()
                        ->end()
                        ->arrayNode('draft')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\DraftRepository')->end()
                            ->end()
                        ->end()
                        ->arrayNode('subscription')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\SubscriptionRepository')->end()
                            ->end()
                        ->end()
                        ->arrayNode('registry')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Repository\RegistryRepository')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addGatewaySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('gateway')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('bag')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\Bag\GatewayBag')->end()
                            ->end()
                        ->end()
                        ->arrayNode('category')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\CategoryGateway')->end()
                            ->end()
                        ->end()
                        ->arrayNode('board')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\BoardGateway')->end()
                            ->end()
                        ->end()
                        ->arrayNode('topic')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\TopicGateway')->end()
                            ->end()
                        ->end()
                        ->arrayNode('post')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\PostGateway')->end()
                            ->end()
                        ->end()
                        ->arrayNode('draft')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\DraftGateway')->end()
                            ->end()
                        ->end()
                        ->arrayNode('subscription')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\SubscriptionGateway')->end()
                            ->end()
                        ->end()
                        ->arrayNode('registry')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Gateway\RegistryGateway')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addManagerSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('manager')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('bag')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\Bag\ManagerBag')->end()
                            ->end()
                        ->end()
                        ->arrayNode('category')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\CategoryManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('board')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\BoardManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('topic')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\TopicManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('post')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\PostManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('draft')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\DraftManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('subscription')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\SubscriptionManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('registry')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\RegistryManager')->end()
                            ->end()
                        ->end()
                        ->arrayNode('policy')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Manager\PolicyManager')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addFormSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('type')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('topic_create')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Type\TopicCreateFormType')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('topic_update')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Type\TopicUpdateFormType')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('post_create')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Type\PostCreateFormType')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('post_update')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Type\PostUpdateFormType')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('change_topics_board')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Type\TopicChangeBoardFormType')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('handler')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('topic_create')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('topic_update')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Handler\TopicUpdateFormHandler')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('post_create')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Handler\PostCreateFormHandler')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('post_update')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Handler\PostUpdateFormHandler')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('change_topics_board')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Form\Handler\TopicChangeBoardFormHandler')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addComponentSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('component')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('dashboard')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('integrator')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Component\Dashboard\DashboardIntegrator')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('twig_extension')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('board_list')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Component\TwigExtension\BoardListExtension')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('flood_control')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNForum\ForumBundle\Component\FloodControl')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access protected
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    protected function addFixtureReferenceSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('fixtures')
                    ->addDefaultsIfNotSet()
                    ->children()
                    ->scalarNode('user_admin')->defaultValue('user-admin')->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access protected
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    protected function addSEOSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('seo')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('title_length')->defaultValue('67')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addCategorySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('category')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('last_post_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                        ->arrayNode('index')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('show')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addBoardSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('board')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('show')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('topics_per_page')->defaultValue('50')->end()
                                ->scalarNode('topic_title_truncate')->defaultValue('50')->end()
                                ->scalarNode('first_post_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                                ->scalarNode('last_post_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addTopicSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('topic')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('flood_control')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('post_limit')->defaultValue(4)->end()
                                ->scalarNode('block_for_minutes')->defaultValue(1)->end()
                            ->end()
                        ->end()
                        ->arrayNode('show')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('posts_per_page')->defaultValue('20')->end()
                                ->scalarNode('topic_closed_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                                ->scalarNode('topic_deleted_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                            ->end()
                        ->end()
                        ->arrayNode('create')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('form_theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('reply')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('form_theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('change_board')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('form_theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addPostSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('post')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('show')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('topic_closed_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                                ->scalarNode('topic_deleted_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                            ->end()
                        ->end()
                        ->arrayNode('edit_topic')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('form_theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('edit_post')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('form_theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('delete_post')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addItemPostSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('item_post')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('post_created_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                        ->scalarNode('post_edited_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                        ->scalarNode('post_locked_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                        ->scalarNode('post_deleted_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addDraftSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('draft')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('list')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('drafts_per_page')->defaultValue('40')->end()
                                ->scalarNode('topic_title_truncate')->defaultValue('80')->end()
                                ->scalarNode('creation_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addSubscriptionSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('subscription')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('list')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('topics_per_page')->defaultValue('50')->end()
                                ->scalarNode('topic_title_truncate')->defaultValue('50')->end()
                                ->scalarNode('first_post_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                                ->scalarNode('last_post_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  ArrayNodeDefinition                                      $node
     * @return \CCDNForum\ForumBundle\DependencyInjection\Configuration
     */
    private function addTranscriptSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('transcript')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('post_creation_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                        ->scalarNode('post_deleted_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }
}
