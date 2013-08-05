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
class TopicModel extends BaseModel implements BaseModelInterface
{
    /**
     *
     * @access public
     * @param  int                                 $topicId
     * @return \CCDNForum\ForumBundle\Entity\Topic
     */
    public function findOneTopicByIdWithBoardAndCategory($topicId, $canViewDeletedTopics)
    {
        return $this->getRepository()->findOneTopicByIdWithBoardAndCategory($topicId, $canViewDeletedTopics);
    }
	
	
	
	
	
	




    /**
     *
     * @access public
     * @return bool
     */
    public function allowedToViewDeletedTopics()
    {
        return $this->managerBag->getPolicyManager()->allowedToViewDeletedTopics();
    }

    /**
     *
     * @access public
     * @param  int                                 $boardId
     * @return \CCDNForum\ForumBundle\Entity\Topic
     */
    public function findLastTopicForBoardByIdWithLastPost($boardId)
    {
        return $this->getRepository()->findLastTopicForBoardByIdWithLastPost($boardId);
    }

    /**
     *
     * @access public
     * @param  int                                 $topicId
     * @return \CCDNForum\ForumBundle\Entity\Topic
     */
    public function findOneByIdWithPostsByTopicId($topicId)
    {
        return $this->getRepository()->findOneByIdWithPostsByTopicId($topicId);
    }

    /**
     *
     * @access public
     * @param  int                                          $boardId
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllStickiedByBoardId($boardId)
    {
        return $this->getRepository()->findAllStickiedByBoardId($boardId);
    }

    /**
     *
     * @access public
     * @param  int                    $boardId
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findAllPaginatedByBoardId($boardId, $page)
    {
        return $this->getRepository()->findAllPaginatedByBoardId($boardId, $page);
    }

    /**
     *
     * @access public
     * @param  int   $boardId
     * @return Array
     */
    public function getPostCountForTopicById($topicId)
    {
        return $this->getRepository()->getPostCountForTopicById($topicId);
    }

    /**
     *
     * @access public
     * @param  Array                                        $topicIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findTheseTopicsById($topicIds = array())
    {
        return $this->getRepository()->findTheseTopicsById($topicsIds);
    }

    /**
     *
     * @access public
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findClosedTopicsForModeratorsPaginated($page)
    {
        return $this->getRepository()->findClosedTopicsForModeratorsPaginated($page);
    }

    /**
     *
     * @access public
     * @param  int                    $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function findDeletedTopicsForAdminsPaginated($page)
    {
        return $this->getRepository()->findDeletedTopicsForAdminsPaginated($page);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @param  \CCDNForum\ForumBundle\Entity\Post  $post
     * @return int
     */
    public function getPageForPostOnTopic(Topic $topic, Post $post)
    {
        return $this->getManager()->getPageForPostOnTopic($topic, $post);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function incrementViewCounter(Topic $topic)
    {
        return $this->getManager()->incrementViewCounter($topic);
    }

    /**
     *
     * Post must have a set topic for topic to be set  correctly.
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function postNewTopic(Post $post)
    {
        return $this->getManager()->postNewTopic($post);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateTopic(Topic $topic)
    {
        return $this->getManager()->updateTopic($topic);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateStats(Topic $topic)
    {
        return $this->getManager()->updateStats($topic);
    }

    /**
     *
     * @access public
     * @param  array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkUpdateStats($topics)
    {
        return $this->getManager()->bulkUpdateStats($topics);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function sticky(Topic $topic, UserInterface $user)
    {
        return $this->getManager()->sticky($topic, $user);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsticky(Topic $topic)
    {
        return $this->getManager()->unsticky($topic);
    }

    /**
     *
     * @access public
     * @param Topic $topic
     * @param $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function close(Topic $topic, UserInterface $user)
    {
        return $this->getManager()->close($topic, $user);
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkClose($topics, UserInterface $user)
    {
        return $this->getManager()->bulkClose($topics, $user);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reopen(Topic $topic)
    {
        return $this->getManager()->reopen($topic);
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkReopen($topics)
    {
        return $this->getManager()->bulkReopen($topics);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function restore(Topic $topic)
    {
        return $this->getManager()->restore($topic);
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkRestore($topics)
    {
        return $this->getManager()->bulkRestore($topics);
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @param $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function softDelete(Topic $topic, UserInterface $user)
    {
        return $this->getManager()->softDelete($topic, $user);
    }

    /**
     *
     * @access public
     * @param  array                                               $topics
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkSoftDelete($topics, UserInterface $user)
    {
        return $this->getManager()->bulkSoftDelete($topics, $user);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                 $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function hardDelete(Topic $topic)
    {
        return $this->getManager()->hardDelete($topic);
    }

    /**
     *
     * @access public
     * @param  Array                                               $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkHardDelete($topics)
    {
        return $this->getManager()->bulkHardDelete($topics);
    }
}