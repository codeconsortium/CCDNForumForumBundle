<?php

/*
 * This file is part of the CCDN ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/> 
 * 
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class }
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class Configuration implements ConfigurationInterface
{
	
	
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('forum');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
		$rootNode
			->children()
				->arrayNode('user')
					->children()
						->scalarNode('profile_route')->defaultValue('cc_profile_show_by_id')->end()
					->end()
				->end()
				->arrayNode('template')
					->children()
						->scalarNode('engine')->defaultValue('twig')->end()
						->scalarNode('theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
					->end()
				->end()
			->end();

		$this->addCategorySection($rootNode);
		$this->addBoardSection($rootNode);
		$this->addTopicSection($rootNode);
		$this->addPostSection($rootNode);
		
        return $treeBuilder;
    }

	

	/**
	 *
	 * @access private
	 * @param ArrayNodeDefinition $node
	 */
	private function addCategorySection(ArrayNodeDefinition $node)
	{
		$node
			->children()
				->arrayNode('category')
					->children()
						->arrayNode('layout_templates')
							->children()
								->scalarNode('index')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('show')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								
							->end()
						->end()
					->end()
				->end()
			->end();
	}
	

	/**
	 *
	 * @access private
	 * @param ArrayNodeDefinition $node
	 */
	private function addBoardSection(ArrayNodeDefinition $node)
	{
		$node
			->children()
				->arrayNode('board')
					->children()
						->scalarNode('topics_per_page')->defaultValue('40')->end()
						->arrayNode('layout_templates')
							->children()
								->scalarNode('show')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
							->end()
						->end()
					->end()
				->end()
			->end();
	}


	/**
	 *
	 * @access private
	 * @param ArrayNodeDefinition $node
	 */
	private function addTopicSection(ArrayNodeDefinition $node)
	{
		$node
			->children()
				->arrayNode('topic')
					->children()
						->scalarNode('posts_per_page')->defaultValue('20')->end()
						->arrayNode('layout_templates')
							->children()
								->scalarNode('create')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('reply')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('show')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
							->end()
						->end()
					->end()
				->end()
			->end();
	}
	

	/**
	 *
	 * @access private
	 * @param ArrayNodeDefinition $node
	 */	
	private function addPostSection(ArrayNodeDefinition $node)
	{
		$node
			->children()
				->arrayNode('post')
					->children()
						->arrayNode('layout_templates')
							->children()
								->scalarNode('show')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('flag')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('edit_topic')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('edit_post')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
								->scalarNode('delete_post')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_left.html.twig')->end()
							->end()
						->end()
					->end()
				->end()
			->end();
	}

	
}
