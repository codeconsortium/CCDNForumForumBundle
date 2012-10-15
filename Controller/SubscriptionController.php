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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class SubscriptionController extends ContainerAware
{

    /**
     *
     * @access public
     * @param int $page
     * @return RenderResponse
     */
    public function showAction($page)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $subscriptions = $this->container->get('ccdn_forum_forum.repository.subscription')->findForUserById($user->getId());

        // deal with pagination.
        $topicsPerPage = $this->container->getParameter('ccdn_forum_forum.subscription.list.topics_per_page');
        $subscriptions->setMaxPerPage($topicsPerPage);
        $subscriptions->setCurrentPage($page, false, true);

        // this is necessary for working out the last page for each topic.
        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');

        $crumbs = $this->container->get('ccdn_component_crumb.trail')
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.forum_index', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_category_index'), "home")
            ->add($this->container->get('translator')->trans('ccdn_forum_forum.crumbs.topic.subscriptions', array(), 'CCDNForumForumBundle'), $this->container->get('router')->generate('ccdn_forum_forum_subscription_list'), "bookmark");

        return $this->container->get('templating')->renderResponse('CCDNForumForumBundle:Subscription:list.html.' . $this->getEngine(), array(
            'user_profile_route' => $this->container->getParameter('ccdn_forum_forum.user.profile_route'),
            'crumbs' => $crumbs,
            'pager' => $subscriptions,
            'posts_per_page' => $postsPerPage,
        ));

    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function subscribeAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $this->container->get('ccdn_forum_forum.manager.subscription')->subscribe($topicId, $user)->flush();

        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topicId)) );
    }

    /**
     *
     * @access public
     * @param int $topicId
     * @return RedirectResponse
     */
    public function unsubscribeAction($topicId)
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $this->container->get('ccdn_forum_forum.manager.subscription')->unsubscribe($topicId, $user)->flush();

        return new RedirectResponse($this->container->get('router')->generate('ccdn_forum_forum_topic_show', array('topicId' => $topicId)) );
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

}
