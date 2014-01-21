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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\Event;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

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
class BaseController extends ContainerAware
{
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
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface  $dispatcher;
     */
    protected $dispatcher;

    /**
     *
     * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    private $securityContext;

    /**
     *
     * @var \CCDNForum\ForumBundle\Component\Security\Authorizer $authorizer;
     */
    private $authorizer;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     */
    private $forumModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel $categoryModel
     */
    private $categoryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     */
    private $boardModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     */
    private $topicModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
     */
    private $postModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel $registryModel
     */
    private $registryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel $subscriptionModel
     */
    private $subscriptionModel;

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
     * @param  string $route
     * @param  Array  $params
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
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        if (null == $this->request) {
            $this->request = $this->container->get('request');
        }

        return $this->request;
    }

    /**
     *
     * @access protected
     * @param  string $prefix
     * @return Array
     */
    protected function getCheckedItemIds($prefix = 'check_', $enforceNumericType = true)
    {
        $request = $this->getRequest();

        $sanitarisedIds = array();

        if ($request->request->has($prefix)) {
            $itemIds = $request->request->get($prefix);

            foreach ($itemIds as $id => $val) {
                if ($enforceNumericType == true) {
                    if (! is_numeric($id)) {
                        continue;
                    }
                }

                $sanitarisedIds[] = $id;
            }
        }

        return $sanitarisedIds;
    }

    /**
     *
     * @access protected
     * @param  string $template
     * @param  Array  $params
     * @param  string $engine
     * @return string
     */
    protected function renderResponse($template, $params = array(), $engine = null)
    {
        return $this->getTemplating()->renderResponse($template . ($engine ?: $this->getEngine()), $params);
    }

    /**
     *
     * @access protected
     * @param  string                                             $url
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponse($url)
    {
        return new RedirectResponse($url);
    }

    /**
     *
     * @access protected
     * @param  string                                             $forumName
     * @param  \CCDNForum\ForumBundle\Entity\Topic                $topic
     * @param  \CCDNForum\ForumBundle\Entity\Post                 $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseForTopicOnPageFromPost($forumName, Topic $topic, Post $post)
    {
        //$page = $this->getTopicModel()->getPageForPostOnTopic($topic, $topic->getLastPost()); // Page of the last post.
        $response = $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId(),
            /*'page' => $page*/
        )) /* . '#' . $topic->getLastPost()->getId()*/);

        return $response;
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
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function getUser()
    {
        return $this->getSecurityContext()->getToken()->getUser();
    }

    /**
     *
     * @access protected
     * @param  string $role
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
     * @param  string                                                           $role|boolean $role
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    protected function isAuthorised($role)
    {
        if (is_bool($role)) {
            if ($role == false) {
                throw new AccessDeniedException('You do not have permission to use this resource.');
            }

            return true;
        }

        if (! $this->isGranted($role)) {
            throw new AccessDeniedException('You do not have permission to use this resource.');
        }

        return true;
    }

    /**
     *
     * @access protected
     * @param  \Object                                                      $item
     * @param  string                                                       $message
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

    protected function getQuery($query, $default)
    {
        return $this->getRequest()->query->get($query, $default);
    }

    protected function dispatch($name, Event $event)
    {
        if (! $this->dispatcher) {
            $this->dispatcher = $this->container->get('event_dispatcher');
        }

        $this->dispatcher->dispatch($name, $event);
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Component\Security\Authorizer
     */
    protected function getAuthorizer()
    {
        if (null == $this->authorizer) {
            $this->authorizer = $this->container->get('ccdn_forum_forum.component.security.authorizer');
        }

        return $this->authorizer;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\ForumModel
     */
    protected function getForumModel()
    {
        if (null == $this->forumModel) {
            $this->forumModel = $this->container->get('ccdn_forum_forum.model.forum');
        }

        return $this->forumModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel
     */
    protected function getCategoryModel()
    {
        if (null == $this->categoryModel) {
            $this->categoryModel = $this->container->get('ccdn_forum_forum.model.category');
        }

        return $this->categoryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\BoardModel
     */
    protected function getBoardModel()
    {
        if (null == $this->boardModel) {
            $this->boardModel = $this->container->get('ccdn_forum_forum.model.board');
        }

        return $this->boardModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\TopicModel
     */
    protected function getTopicModel()
    {
        if (null == $this->topicModel) {
            $this->topicModel = $this->container->get('ccdn_forum_forum.model.topic');
        }

        return $this->topicModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\PostModel
     */
    protected function getPostModel()
    {
        if (null == $this->postModel) {
            $this->postModel = $this->container->get('ccdn_forum_forum.model.post');
        }

        return $this->postModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel
     */
    protected function getRegistryModel()
    {
        if (null == $this->registryModel) {
            $this->registryModel = $this->container->get('ccdn_forum_forum.model.registry');
        }

        return $this->registryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel
     */
    protected function getSubscriptionModel()
    {
        if (null == $this->subscriptionModel) {
            $this->subscriptionModel = $this->container->get('ccdn_forum_forum.model.subscription');
        }

        return $this->subscriptionModel;
    }

    /**
     *
     * @access protected
     */
    protected function getCrumbs()
    {
        return $this->container->get('ccdn_forum_forum.component.crumb_builder');
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper
     */
    protected function getPageHelper()
    {
        return $this->container->get('ccdn_forum_forum.component.helper.pagination_config');
    }
}
