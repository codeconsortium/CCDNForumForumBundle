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

namespace CCDNForum\ForumBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BaseController extends ContainerAware
{
    /**
     *
     * @access protected
     * @return string
     */
    protected function getEngine()
    {
        return $this->container->getParameter('ccdn_forum_forum.template.engine');
    }

    /**
     *
     * @access protected
	 * @param string $action, string $value
     * @return string
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
    }
	
	private $categoryManager;
	protected function getCategoryManager()
	{
		if (null == $this->categoryManager) {
			$this->categoryManager = $this->container->get('ccdn_forum_forum.manager.category');
		}
		
		return $this->categoryManager;
	}
	
	private $boardManager;
	protected function getBoardManager()
	{
		if (null == $this->boardManager) {
			$this->boardManager = $this->container->get('ccdn_forum_forum.manager.board');
		}
		
		return $this->boardManager;
	}
	
	private $topicManager;
	protected function getTopicManager()
	{
		if (null == $this->topicManager) {
			$this->topicManager = $this->container->get('ccdn_forum_forum.manager.topic');
		}
		
		return $this->topicManager;		
	}
	
	private $postManager;
	protected function getPostManager()
	{
		if (null == $this->postManager) {
			$this->postManager = $this->container->get('ccdn_forum_forum.manager.post');
		}
		
		return $this->postManager;
	}
	
	private $draftManager;
	protected function getDraftManager()
	{
		if (null == $this->draftManager) {
			$this->draftManager = $this->container->get('ccdn_forum_forum.manager.draft');
		}
		
		return $this->draftManager;		
	}
			
	private $registryManager;
	protected function getRegistryManager()		
	{
		if (null == $this->registryManager) {
			$this->registryManager = $this->container->get('ccdn_forum_forum.manager.registry');
		}
		
		return $this->registryManager;
	}
	
	private $subscriptionManager;
	protected function getSubscriptionManager()
	{
		if (null == $this->subscriptionManager) {
			$this->subscriptionManager = $this->container->get('ccdn_forum_forum.manager.subscription');
		}
		
		return $this->subscriptionManager;
	}
	
    protected function filterViewableBoards($boards)
    {
        foreach ($boards as $boardKey => $board) {
            if (! $board->isAuthorisedToRead($this->container->get('security.context'))) {
                unset($boards[$boardKey]);
            }
        }

        return $boards;
    }

    protected function filterViewableCategories($categories)
    {
        foreach ($categories as $categoryKey => $category) {
            $boards = $category->getBoards();

            foreach($boards as $board) {
                if (! $board->isAuthorisedToRead($this->container->get('security.context'))) {
                    $categories[$categoryKey]->removeBoard($board);
                }
            }
        }

        return $categories;
    }
}