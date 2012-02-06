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
		$container->setParameter('ccdn_forum_forum.user.profile_route', $config['user']['profile_route']);
		
		$this->getCategorySection($container, $config);
		$this->getBoardSection($container, $config);
		$this->getTopicSection($container, $config);
		$this->getPostSection($container, $config);
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
		
	}
	

	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getBoardSection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.board.topics_per_page', $config['board']['topics_per_page']);
	}
	

	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getTopicSection($container, $config)
	{
		$container->setParameter('ccdn_forum_forum.topic.posts_per_page', $config['topic']['posts_per_page']);		
	}
	

	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getPostSection($container, $config)
	{
		
	}
	
}
