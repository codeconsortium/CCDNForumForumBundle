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

namespace CCDNForum\ForumBundle\Model\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\BaseModelInterface;

use CCDNForum\ForumBundle\Entity\Subscription;

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
class SubscriptionModel extends BaseModel implements BaseModelInterface
{
    /**
     *
     * @access public
     * @param  int $topicId
     * @return int
     */
    public function countSubscriptionsForTopicById($topicId)
    {
        return $this->getRepository()->countSubscriptionsForTopicById($topicId);
    }

    /**
     *
     * @access public
     * @param  int                                        $topicId
     * @return \CCDNForum\ForumBundle\Entity\Subscription
     */
    public function findSubscriptionForTopicById($topicId)
    {
        return $this->getRepository()->findSubscriptionForTopicById($topicId);
    }

    /**
     *
     * @access public
     * @param  int                                        $topicId
     * @param  int                                        $userId  = null
     * @return \CCDNForum\ForumBundle\Entity\Subscription
     */
    public function findSubscriptionForTopicByIdAndUserId($topicId, $userId = null)
    {
       return $this->getRepository()->findSubscriptionFortopicByIdAndUserId($topicId, $userId);
    }

    /**
     *
     * @access public
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findAllPaginated($page)
    {
        return $this->getRepository()->findAllPaginated($page);
    }

    /**
     *
     * @access public
     * @param  int                                                 $topicId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function subscribe(Topic $topic)
    {
        return $this->getManager()->subscribe($topic);
    }

    /**
     *
     * @access public
     * @param  int                                                 $topicId
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsubscribe(Topic $topic)
    {
        return $this->getManager()->unsubscribe($topic);
    }
}