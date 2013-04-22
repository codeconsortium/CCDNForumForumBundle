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

use Symfony\Component\HttpFoundation\RedirectResponse;

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
class SubscriptionController extends TopicBaseController
{
    /**
     *
     * @access public
     * @param  int            $page
     * @return RenderResponse
     */
    public function showAction($page)
    {
        $this->isAuthorised('ROLE_USER');

        $subscriptionPager = $this->getSubscriptionManager()->findAllPaginated($page);
        $this->isFound($subscriptionPager->getCurrentPageResults());

        // this is necessary for working out the last page for each topic.
        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');

        $crumbs = $this->getCrumbs()
            ->add($this->trans('ccdn_forum_forum.crumbs.forum_index'), $this->path('ccdn_forum_forum_category_index'))
            ->add($this->trans('ccdn_forum_forum.crumbs.topic.subscriptions'), $this->path('ccdn_forum_forum_subscription_list'));

        return $this->renderResponse('CCDNForumForumBundle:Subscription:list.html.', array(
            'crumbs' => $crumbs,
            'pager' => $subscriptionPager,
            'posts_per_page' => $postsPerPage,
        ));
    }

    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function subscribeAction($topicId)
    {
        $this->isAuthorised('ROLE_USER');

        $topic = $this->getTopicManager()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);

        $this->getSubscriptionManager()->subscribe($topic)->flush();

        $this->setFlash('notice', $this->trans('ccdn_forum_forum.flash.subscription.topic.subscribed', array('%topic_title%' => $topic->getTitle() )));

        return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topicId)) );
    }

    /**
     *
     * @access public
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function unsubscribeAction($topicId)
    {
        $this->isAuthorised('ROLE_USER');

        $topic = $this->getTopicManager()->findOneByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);

        $this->getSubscriptionManager()->unsubscribe($topic)->flush();

        $this->setFlash('notice', $this->trans('ccdn_forum_forum.flash.subscription.topic.unsubscribed', array('%topic_title%' => $topic->getTitle() )));

        return $this->redirectResponse($this->path('ccdn_forum_forum_topic_show', array('topicId' => $topicId)) );
    }
}
