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
class UserSubscriptionController extends UserTopicBaseController
{
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function showAction()
    {
        $this->isAuthorised('ROLE_USER');

		$page = $this->getQuery('page', 1);

        $subscriptionPager = $this->getSubscriptionModel()->findAllPaginated($page);

        // this is necessary for working out the last page for each topic.
        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');

        //$crumbs = $this->getCrumbs()
        //    ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_user_category_index'))
        //    ->add($this->trans('crumbs.subscription.index'), $this->path('ccdn_forum_user_subscription_list'));

        return $this->renderResponse('CCDNForumForumBundle:Subscription:list.html.', array(
        //    'crumbs' => $crumbs,
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

        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);

        $this->getSubscriptionModel()->subscribe($topic)->flush();

        $this->setFlash('notice', $this->trans('flash.subscription.topic.subscribed', array('%topic_title%' => $topic->getTitle() )));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $topicId)) );
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

        $topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId);
        $this->isFound($topic);
        $this->isAuthorisedToViewTopic($topic);

        $this->getSubscriptionModel()->unsubscribe($topic)->flush();

        $this->setFlash('notice', $this->trans('flash.subscription.topic.unsubscribed', array('%topic_title%' => $topic->getTitle() )));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array('topicId' => $topicId)) );
    }
}
