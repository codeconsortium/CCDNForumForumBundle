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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BaseController extends ContainerAware
{
	/**
	 *
	 * @var \Symfony\Bundle\FrameworkBundle\Translation\Translator $translator
	 */
	private $translator;

	/**
	 *
	 * @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router
	 */
	private $router;

	/**
	 *
	 * @var \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine $templating
	 */	
	private $templating;
	
	/**
	 *
	 * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
	 */
	private $securityContext;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\CategoryManager $categoryManager
	 */
	private $categoryManager;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\BoardManager $boardManager
	 */
	private $boardManager;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\TopicManager $topicManager
	 */
	private $topicManager;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\PostManager $postManager
	 */
	private $postManager;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\DraftManager $draftManager
	 */
	private $draftManager;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\RegistryManager $registryManager
	 */
	private $registryManager;
	
	/**
	 *
	 * @var \CCDNForum\ForumBundle\Manager\SubscriptionManager $subscriptionManager
	 */
	private $subscriptionManager;
	
	/**
	 *
	 * @access protected
	 * @return \Symfony\Bundle\FrameworkBundle\Translation\Translator
	 */
	protected function getTranslator()
	{
		if (null == $this->translator) {
			$this->translator = $this->container->get('translator');
		}
		
		return $this->translator;
	}
	
	/**
	 *
	 * @access protected
	 * @param string $message
	 * @param Array $params
	 * @param string $bundle
	 * @return string
	 */
	protected function trans($message, $params = array(), $bundle = 'CCDNForumForumBundle')
	{
		return $this->getTranslator()->trans($message, $params, $bundle);
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
	
	/**
	 *
	 * @access protected
	 * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
	 */
	protected function getRouter()
	{
		if (null == $this->router) {
			$this->router = $this->container->get('router');
		}
		
		return $this->router;
	}
	
	/**
	 *
	 * @access protected
	 * @param string $route
	 * @param Array $params
	 * @return string
	 */
	protected function path($route, $params = array())
	{
		return $this->getRouter()->generate($route, $params);
	}
	
	/**
	 *
	 * @access protected
	 * @return \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine
	 */
	protected function getTemplating()
	{
		if (null == $this->templating) {
			$this->templating = $this->container->get('templating');
		}
		
		return $this->templating;
	}
	
	/**
	 *
	 * @access protected
	 * @param string $template
	 * @param Array $params
	 * @param string $engine
	 * @return string
	 */
	protected function renderResponse($template, $params = array(), $engine = null)
	{
		return $this->getTemplating()->renderResponse($template . ($engine ?: $this->getEngine()), $params);
	}
	
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
	 * @return \Symfony\Component\Security\Core\SecurityContext
	 */
	protected function getSecurityContext()
	{
		if (null == $this->securityContext) {
			$this->securityContext = $this->container->get('security.context');
		}
		
		return $this->securityContext;
	}

	/** 
	 * 
	 * @access protected
	 * @return UserInterface
	 */	
	protected function getUser()
	{
		return $this->getSecurityContext()->getToken()->getUser();		
	}
	
	/** 
	 * 
	 * @access protected
	 * @param string $role
	 * @return bool
	 */
	protected function isGranted($role)
	{
		if (! $this->getSecurityContext()->isGranted($role)) {
			return false;
		}
		
		return true;
	}
		
	/** 
	 * 
	 * @access protected
	 * @throws Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	protected function isAuthorised($role)
	{
		if (! $this->isGranted($role)) {
			throw new AccessDeniedException('You do not have permission to use this resource.');
		}
		
		return true;
	}
	
	/**
	 *
	 * @access protected
	 * @param \Object $item
	 * @param string $message
	 * @return bool
	 * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	protected function isFound($item, $message = null)
	{
		if (null == $item) {
			throw new NotFoundHttpException($message ?: 'Page you are looking for could not be found!');
		}
		
		return true;
	}

	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\CategoryManager
	 */
	protected function getCategoryManager()
	{
		if (null == $this->categoryManager) {
			$this->categoryManager = $this->container->get('ccdn_forum_forum.manager.category');
		}
		
		return $this->categoryManager;
	}
	
	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\BoardManager
	 */
	protected function getBoardManager()
	{
		if (null == $this->boardManager) {
			$this->boardManager = $this->container->get('ccdn_forum_forum.manager.board');
		}
		
		return $this->boardManager;
	}
	
	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\TopicManager
	 */
	protected function getTopicManager()
	{
		if (null == $this->topicManager) {
			$this->topicManager = $this->container->get('ccdn_forum_forum.manager.topic');
		}
		
		return $this->topicManager;		
	}
	
	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\PostManager
	 */
	protected function getPostManager()
	{
		if (null == $this->postManager) {
			$this->postManager = $this->container->get('ccdn_forum_forum.manager.post');
		}
		
		return $this->postManager;
	}
	
	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\DraftManager
	 */
	protected function getDraftManager()
	{
		if (null == $this->draftManager) {
			$this->draftManager = $this->container->get('ccdn_forum_forum.manager.draft');
		}
		
		return $this->draftManager;		
	}
	
	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\RegistryManager
	 */	
	protected function getRegistryManager()		
	{
		if (null == $this->registryManager) {
			$this->registryManager = $this->container->get('ccdn_forum_forum.manager.registry');
		}
		
		return $this->registryManager;
	}
	
	/**
	 *
	 * @access protected
	 * @return \CCDNForum\ForumBundle\Manager\SubscriptionManager
	 */
	protected function getSubscriptionManager()
	{
		if (null == $this->subscriptionManager) {
			$this->subscriptionManager = $this->container->get('ccdn_forum_forum.manager.subscription');
		}
		
		return $this->subscriptionManager;
	}
	
	/**
	 *
	 * @access protected
	 */
	protected function getCrumbs()
	{
		return $this->container->get('ccdn_component_crumb.trail');
	}
}