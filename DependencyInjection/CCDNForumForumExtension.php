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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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
    public function load(array $configs, ContainerBuilder $container)
    {
		$processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

		$container->setParameter('ccdn_forum_forum.template.engine', $config['template']['engine']);
		$container->setParameter('ccdn_forum_forum.template.theme', $config['template']['theme']);
		
		$container->setParameter('ccdn_forum_forum.user.profile_route', $config['user']['profile_route']);
		
		$this->getCategorySection($container, $config);
		$this->getBoardSection($container, $config);
		$this->getTopicSection($container, $config);
		$this->getPostSection($container, $config);
		$this->getDraftSection($container, $config);
    }
	
	
	
    /**
     * {@inheritDoc}
     */
	public function getAlias()
	{
		return 'ccdn_forum_forum';
	}
	


	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getCategorySection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.category.layout_templates.index', $config['category']['layout_templates']['index']);
		$container->setParameter('ccdn_forum_forum.category.layout_templates.show', $config['category']['layout_templates']['show']);
	}
	
	

	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getBoardSection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.board.topics_per_page', $config['board']['topics_per_page']);
		$container->setParameter('ccdn_forum_forum.board.layout_templates.show', $config['board']['layout_templates']['show']);
	}
	


	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getTopicSection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.topic.posts_per_page', $config['topic']['posts_per_page']);		
		$container->setParameter('ccdn_forum_forum.topic.layout_templates.create', $config['topic']['layout_templates']['create']);
		$container->setParameter('ccdn_forum_forum.topic.layout_templates.reply', $config['topic']['layout_templates']['reply']);
		$container->setParameter('ccdn_forum_forum.topic.layout_templates.show', $config['topic']['layout_templates']['show']);
	}
	
	

	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getPostSection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.post.layout_templates.show', $config['post']['layout_templates']['show']);
		$container->setParameter('ccdn_forum_forum.post.layout_templates.flag', $config['post']['layout_templates']['flag']);
		$container->setParameter('ccdn_forum_forum.post.layout_templates.edit_post', $config['post']['layout_templates']['edit_post']);
		$container->setParameter('ccdn_forum_forum.post.layout_templates.edit_topic', $config['post']['layout_templates']['edit_topic']);
		$container->setParameter('ccdn_forum_forum.post.layout_templates.delete_post', $config['post']['layout_templates']['delete_post']);
	}
	
	
	
	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getDraftSection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.draft.drafts_per_page', $config['draft']['drafts_per_page']);
		$container->setParameter('ccdn_forum_forum.draft.layout_templates.list', $config['draft']['layout_templates']['list']);
	}
		
}
