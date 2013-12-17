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

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;

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
class UserSubscriptionController extends BaseController
{
    /**
     *
     * @access public
     * @param  string         $forumName
     * @return RenderResponse
     */
    public function indexAction($forumName)
    {
        $this->isAuthorised('ROLE_USER');

        if ($forumName != '~') {
            $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        } else {
            $forum = null;
        }

        $page = $this->getQuery('page', 1);
        $filter = $this->getQuery('filter', 'all');
        // Use this for the sidebar counters
        $subscriptionForums = $this->getSubscriptionModel()->findAllSubscriptionsForUserById($this->getUser()->getId(), true);
        $forumsSubscribed = array();
        $totalForumsSubscribed = array('count_read' => 0, 'count_unread' => 0, 'count_total' => 0);
        foreach ($subscriptionForums as $subscription) {
            $forumSubscribed = $subscription->getForum();

            if ($forumSubscribed) {
                $forumSubscribedId = $forumSubscribed->getId();

                if (! array_key_exists($forumSubscribedId, $forumsSubscribed)) {
                    $forumsSubscribed[$forumSubscribedId] = array(
                        'forum' => $forumSubscribed,
                        'count_read' => 0,
                        'count_unread' => 0,
                        'count_total' => 0,
                    );
                }

                $forumsSubscribed[$forumSubscribedId]['count_total']++;
                if ($subscription->isRead()) {
                    $forumsSubscribed[$forumSubscribedId]['count_read']++;
                } else {
                    $forumsSubscribed[$forumSubscribedId]['count_unread']++;
                }

                if ($forum) {
                    if ($forum->getId() == $forumSubscribedId) {
                        $totalForumsSubscribed['count_total']++;
                        if ($subscription->isRead()) {
                            $totalForumsSubscribed['count_read']++;
                        } else {
                            $totalForumsSubscribed['count_unread']++;
                        }
                    }
                } else {
                    $totalForumsSubscribed['count_total']++;
                    if ($subscription->isRead()) {
                        $totalForumsSubscribed['count_read']++;
                    } else {
                        $totalForumsSubscribed['count_unread']++;
                    }
                }
            }
        }

        // Use this for the ALL/READ/UNREAD tab
        $itemsPerPage = $this->getPageHelper()->getTopicsPerPageOnSubscriptions();
        if ($forumName == '~') {
            $subscriptionPager = $this->getSubscriptionModel()->findAllSubscriptionsPaginatedForUserById($this->getUser()->getId(), $page, $itemsPerPage, $filter, true);
        } else {
            $subscriptionPager = $this->getSubscriptionModel()->findAllSubscriptionsPaginatedForUserByIdAndForumById($forum->getId(), $this->getUser()->getId(), $page, $itemsPerPage, $filter, true);
        }

        return $this->renderResponse('CCDNForumForumBundle:User:Subscription/show.html.', array(
            'forum' => $forum,
            'forumName' => $forumName,
            'subscribed_forums' => $forumsSubscribed,
            'total_subscribed_forums' => $totalForumsSubscribed,
            'filter' => $filter,
            'pager' => $subscriptionPager,
            'posts_per_page' => $this->container->getParameter('ccdn_forum_forum.topic.user.show.posts_per_page')
        ));
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function subscribeAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_USER');

        if ($forumName != '~') {
            $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        } else {
            $forum = null;
        }

        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId));
        $this->isAuthorised($this->getAuthorizer()->canSubscribeToTopic($topic, $forum));
        $this->getSubscriptionModel()->subscribe($topic, $this->getUser())->flush();
        $this->dispatch(ForumEvents::USER_TOPIC_SUBSCRIBE_COMPLETE, new UserTopicEvent($this->getRequest(), $topic, true));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topicId
        )));
    }

    /**
     *
     * @access public
     * @param  string           $forumName
     * @param  int              $topicId
     * @return RedirectResponse
     */
    public function unsubscribeAction($forumName, $topicId)
    {
        $this->isAuthorised('ROLE_USER');

        if ($forumName != '~') {
            $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        } else {
            $forum = null;
        }

        $this->isFound($topic = $this->getTopicModel()->findOneTopicByIdWithBoardAndCategory($topicId));
        $subscription = $this->getSubscriptionModel()->findOneSubscriptionForTopicByIdAndUserById($topicId, $this->getUser()->getId());
        $this->isAuthorised($this->getAuthorizer()->canUnsubscribeFromTopic($topic, $forum, $subscription));
        $this->getSubscriptionModel()->unsubscribe($topic, $this->getUser()->getId())->flush();
        $this->dispatch(ForumEvents::USER_TOPIC_UNSUBSCRIBE_COMPLETE, new UserTopicEvent($this->getRequest(), $topic, false));

        return $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topicId
        )));
    }
}
