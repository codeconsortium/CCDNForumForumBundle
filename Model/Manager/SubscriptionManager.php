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

namespace CCDNForum\ForumBundle\Model\Manager;

use CCDNForum\ForumBundle\Model\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Subscription;
use CCDNForum\ForumBundle\Entity\Topic;

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
class SubscriptionManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  int                                                 $topicId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function subscribe(Topic $topic)
    {
        $subscription = $this->findSubscriptionForTopicById($topic->getId());

        if (! $subscription) {
            $subscription = new Subscription();
        }

        if (! $subscription->getIsSubscribed()) {
            $subscription->setIsSubscribed(true);

            $subscription->setOwnedBy($this->getUser());
            $subscription->setTopic($topic);
            $subscription->setIsRead(true);

            $this->persist($subscription)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  int                                                 $topicId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsubscribe(Topic $topic)
    {
        $subscription = $this->findSubscriptionForTopicById($topic->getId());

        if (! $subscription) {
            return $this;
        }

        $subscription->setIsSubscribed(false);
        $subscription->setIsRead(true);

        $this->persist($subscription)->flush();

        return $this;
    }
}
