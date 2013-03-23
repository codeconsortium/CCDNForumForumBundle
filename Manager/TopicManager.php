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

namespace CCDNForum\ForumBundle\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicManager extends BaseManager implements BaseManagerInterface
{
	/**
	 *
	 * @access protected
	 * @var string $roleToViewDeletedTopics
	 */	
	protected $roleToViewDeletedTopics = 'ROLE_MODERATOR';
	
	/**
	 *
	 * @access public
	 * @return bool
	 */	
	public function allowedToViewDeletedTopics()
	{
		return $this->securityContext->isGranted($this->roleToViewDeletedTopics);
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 */
	public function isAuthorisedToReplyToTopic(Topic $topic)
	{
        if (! $topic->getBoard()->isAuthorisedToTopicReply($this->securityContext)) {
        	return false;
		}
        
        if ($topic->getIsClosed() || $topic->getIsDeleted()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
        }
				
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 */
	public function isAuthorisedToEditTopic(Topic $topic)
	{
		if ($topic->getIsDeleted() || $topic->getIsClosed()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		
		if ($this->isGranted('ROLE_USER')) {
			if ($topic->getFirstPost()) {
		        if ($topic->getFirstPost()->getCreatedBy()) {
					$post = $topic->getFirstPost();
				
		            // if user does not own post, or is not a mod
		            if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
						if (! $this->isGranted('ROLE_MODERATOR')) {
							return false;
						}
		            }
				} else {
					if (! $this->isGranted('ROLE_MODERATOR')) {
						return false;
					}
				}
	        } else {
				return false;
	        }
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 */
	public function isAuthorisedToDeleteTopic(Topic $topic)
	{
		if ($topic->getIsDeleted() || $topic->getIsClosed()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		
		if ($topic->getCachedReplyCount() > 0) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		
		if ($this->isGranted('ROLE_USER')) {
			if ($topic->getFirstPost()) {
		        if ($topic->getFirstPost()->getCreatedBy()) {
					$post = $topic->getFirstPost();
				
		            // if user does not own post, or is not a mod
		            if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
						if (! $this->isGranted('ROLE_MODERATOR')) {
							return false;
						}
		            }
				} else {
					if (! $this->isGranted('ROLE_MODERATOR')) {
						return false;
					}
				}
	        } else {
				return false;
	        }
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 */
	public function isAuthorisedToRestoreTopic(Topic $topic)
	{
		if ($topic->getIsDeleted() || $topic->getIsClosed()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		
		if ($this->isGranted('ROLE_MODERATOR')) {
			if ($topic->getFirstPost()) {
		        if ($topic->getFirstPost()->getCreatedBy()) {
					$post = $topic->getFirstPost();
				
		            // if user does not own post, or is not a mod
		            if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
						return false;
		            }
				} else {
					return false;
				}
	        } else {
				return false;
	        }
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @return \CCDNForum\ForumBundle\Entity\Topic
	 */	
	public function findLastTopicForBoardByIdWithLastPost($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$params = array(':boardId' => $boardId);
		
		$qb = $this->createSelectQuery(array('t', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')				
			->where('t.board = :boardId')
			->andWhere('t.isDeleted = FALSE')
			->orderBy('lp.createdDate', 'DESC')
			->setMaxResults(1);
		
		return $this->gateway->findTopic($qb, $params);
	}
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 * @return \CCDNForum\ForumBundle\Entity\Topic
	 */	
	public function findOneByIdWithPostsByTopicId($topicId)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}
		
		$canViewDeleted = $this->allowedToViewDeletedTopics();
		
		$params = array(':topicId' => $topicId);
		
		$qb = $this->createSelectQuery(array('t', 'p', 'fp', 'lp', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')
			->innerJoin('t.posts', 'p')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where(
				$this->limitQueryByDeletedStateAndByTopicId($qb, $canViewDeleted)
			);
		
		return $this->gateway->findTopic($qb, $params);
	}
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 * @return \CCDNForum\ForumBundle\Entity\Topic
	 */	
	public function findOneByIdWithBoardAndCategory($topicId)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}
		
		$canViewDeleted = $this->allowedToViewDeletedTopics();
		
		$params = array(':topicId' => $topicId);
		
		$qb = $this->createSelectQuery(array('t', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where(
				$this->limitQueryByDeletedStateAndByTopicId($qb, $canViewDeleted)
			);
		
		return $this->gateway->findTopic($qb, $params);
	}
	
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function findAllStickiedByBoardId($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$canViewDeleted = $this->allowedToViewDeletedTopics();
		
		$params = array(':boardId' => $boardId, ':isSticky' => true);
		
		$qb = $this->createSelectQuery(array('t', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where(
				$this->limitQueryByStickiedAndDeletedStateByBoardId($qb, $canViewDeleted)
			)
			->orderBy('lp.createdDate', 'DESC');
		
		return $this->gateway->findTopics($qb, $params);
	}
	
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */
	public function findAllPaginatedByBoardId($boardId, $page)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}

		$canViewDeleted = $this->allowedToViewDeletedTopics();
		
		$params = array(':boardId' => $boardId, ':isSticky' => false);
		
		$qb = $this->createSelectQuery(array('t', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
			
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where(
				$this->limitQueryByStickiedAndDeletedStateByBoardId($qb, $canViewDeleted)
			)
			->setParameters($params)
			->orderBy('lp.createdDate', 'DESC');

		return $this->gateway->paginateQuery($qb, $this->getTopicsPerPageOnBoards(), $page);
	}
	
	/**
	 *
	 * @access protected
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param bool $canViewDeletedTopics
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function limitQueryByDeletedStateAndByTopicId(QueryBuilder $qb, $canViewDeletedTopics)
	{
		if ($canViewDeletedTopics) {
			$expr = $qb->expr()->eq('t.id', ':topicId');
		} else {
			$expr = $qb->expr()->andX(
				$qb->expr()->eq('t.id', ':topicId'),
				$qb->expr()->eq('t.isDeleted', 'FALSE')
			);
		}
		
		return $expr;
	}
		
	/**
	 *
	 * @access protected
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param bool $canViewDeletedTopics
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function limitQueryByStickiedAndDeletedStateByBoardId(QueryBuilder $qb, $canViewDeletedTopics)
	{
		if ($canViewDeletedTopics) {
			$expr = $qb->expr()->andX(
				$qb->expr()->eq('t.board', ':boardId'),
				$qb->expr()->eq('t.isSticky', ':isSticky')
			);
		} else {
			$expr = $qb->expr()->andX(
				$qb->expr()->eq('t.board', ':boardId'),
				$qb->expr()->andX(
					$qb->expr()->eq('t.isSticky', ':isSticky'),
					$qb->expr()->eq('t.isDeleted', 'FALSE')
				)
			);
		}
		
		return $expr;
	}
		
	/**
	 *
	 * @access public
	 * @param int $boardId
	 * @return Array
	 */	
	public function getPostCountForTopicById($topicId)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}
		
		$qb = $this->getQueryBuilder();

		$topicEntityClass = $this->managerBag->getTopicManager()->getGateway()->getEntityClass();
			
		$qb
			->select('COUNT(DISTINCT p.id) AS postCount')
			->from($topicEntityClass, 't')
			->leftJoin('t.posts', 'p')
			->where('t.id = :topicId')
			->setParameter(':topicId', $topicId);
		
		try {
			return $qb->getQuery()->getSingleResult();			
		} catch (\Doctrine\ORM\NoResultException $e) {
			return array('postCount' => null);
		} catch (\Exception $e) {
			return array('postCount' => null);			
		}
	}
	
	/**
	 *
	 * @access public
	 * @param Array $topicIds
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function findTheseTopicsById($topicIds = array())
	{
		if (! is_array($topicIds) || count($topicIds) < 1) {
			throw new \Exception('Parameter 1 must be an array and contain at least 1 topic id!');
		}
		
		$qb = $this->createSelectQuery(array('t'));
		
		$qb->where($qb->expr()->in('t.id', $topicIds));
		
		return $this->gateway->findTopics($qb);
	}
	
	/**
	 *
	 * @access public
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */
	public function findClosedTopicsForModeratorsPaginated($page)
	{
		$params = array(':isClosed' => true);
	
		$qb = $this->createSelectQuery(array('t', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where('t.isClosed = :isClosed')
			->setParameters($params)
			->orderBy('lp.createdDate', 'DESC');

		return $this->gateway->paginateQuery($qb, $this->getTopicsPerPageOnBoards(), $page);
	}
	
	/**
	 *
	 * @access public
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */
	public function findDeletedTopicsForAdminsPaginated($page)
	{
		$params = array(':isDeleted' => true);
	
		$qb = $this->createSelectQuery(array('t', 'b', 'c', 'fp', 'fp_author', 'lp', 'lp_author', 't_closedBy', 't_deletedBy', 't_stickiedBy'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
				->leftJoin('fp.createdBy', 'fp_author')
			->leftJoin('t.lastPost', 'lp')
				->leftJoin('lp.createdBy', 'lp_author')
			->leftJoin('t.closedBy', 't_closedBy')
			->leftJoin('t.deletedBy', 't_deletedBy')
			->leftJoin('t.stickiedBy', 't_stickiedBy')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where('t.isDeleted = :isDeleted')
			->setParameters($params)
			->orderBy('lp.createdDate', 'DESC');

		return $this->gateway->paginateQuery($qb, $this->getTopicsPerPageOnBoards(), $page);
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return int
	 */
	public function getPageForPostOnTopic(Topic $topic, Post $post)
	{
		$postsPerPage = $this->getPostsPerPageOnTopics();
		$page = 1;
		
        foreach ($topic->getPosts() as $index => $postTest) {
            if ($post->getId() == $postTest->getId()) {
                $page = ceil($index / $postsPerPage);
                break;
            }
        }
		
		return $page;
	}
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function incrementViewCounter(Topic $topic)
    {
        // set the new counters
        $topic->setCachedViewCount($topic->getCachedViewCount() + 1);

        $this->persist($topic)->flush();
		
		return $this;
    }
	
    /**
     *
	 * Post must have a set topic for topic to be set  correctly.
	 *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function postNewTopic(Post $post)
    {
        // insert a new row.
        $this->persist($post)->flush();

        // get the topic.
        $topic = $post->getTopic();

        // set topic last_post and first_post, board's last_post.
        $topic->setFirstPost($post);
        $topic->setLastPost($post);

        // persist and refresh after a flush to get topic id.
        $this->persist($topic)->flush();
        $this->refresh($topic);

        if ($topic->getBoard()) {
            // Update affected Board stats.
            $this->managerBag->getBoardManager()->updateStats($topic->getBoard())->flush();
        }

		// Subscribe the user to the topic.
		$this->managerBag->getSubscriptionManager()->subscribe($topic)->flush();
		
		$this->managerBag->getRegistryManager()->updateCachedPostCountForUser($post->getCreatedBy())->flush();
		
        return $this;
    }

    /**
	 *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateTopic(Topic $topic)
    {
        // update the record
        $this->persist($topic);

        return $this;
    }
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function updateStats(Topic $topic)
    {
		$postManager = $this->managerBag->getPostManager();

		// Get stats.
        $topicPostCount = $this->getPostCountForTopicById($topic->getId());
        $topicFirstPost = $postManager->getFirstPostForTopicById($topic->getId());
        $topicLastPost = $postManager->getLastPostForTopicById($topic->getId());

        // Set the board / topic last post.
        $topic->setCachedReplyCount($topicPostCount['postCount'] > 0 ? --$topicPostCount['postCount'] : 0);
        $topic->setFirstPost($topicFirstPost ?: null);
        $topic->setLastPost($topicLastPost ?: null);

        $this->persist($topic)->flush();

        if ($topic->getBoard()) {
            // Update affected Board stats.
            $this->managerBag->getBoardManager()->updateStats($topic->getBoard())->flush();
        }

        return $this;
    }
	
    /**
     *
     * @access public
     * @param array $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkUpdateStats($topics)
    {
        foreach ($topics as $topic) {
            $this->updateStats($topic);
        }
		
		return $this;
    }
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function sticky(Topic $topic, UserInterface $user)
    {
        $topic->setIsSticky(true);
        $topic->setStickiedBy($user);
        $topic->setStickiedDate(new \DateTime());

        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unsticky(Topic $topic)
    {
        $topic->setIsSticky(false);
        $topic->setStickiedBy(null);
        $topic->setStickiedDate(null);

        $this->persist($topic);

        return $this;
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
        // Don't overwite previous users accountability.
        if ( ! $topic->getClosedBy() && ! $topic->getClosedDate()) {
            $topic->setIsClosed(true);
            $topic->setClosedBy($user);
            $topic->setClosedDate(new \DateTime());

            $this->persist($topic);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param Array $topics
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkClose($topics, UserInterface $user)
    {
        foreach ($topics as $topic) {
            // Don't overwite previous users accountability.
            if ( ! $topic->getClosedBy() && ! $topic->getClosedDate()) {
                $topic->setIsClosed(true);
                $topic->setClosedBy($user);
                $topic->setClosedDate(new \DateTime());

                $this->persist($topic);
            }
        }

        return $this;
    }
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function reopen(Topic $topic)
    {
        $topic->setIsClosed(false);
        $topic->setClosedBy(null);
        $topic->setClosedDate(null);

		if ($topic->getIsDeleted()) {	
	        $topic->setIsDeleted(false);
	        $topic->setDeletedBy(null);
	        $topic->setDeletedDate(null);			
		}
		
        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param Array $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkReopen($topics)
    {
        foreach ($topics as $topic) {
            $topic->setIsClosed(false);
            $topic->setClosedBy(null);
            $topic->setClosedDate(null);

            $this->persist($topic);
        }

        return $this;
    }
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function restore(Topic $topic)
    {
        $topic->setIsDeleted(false);
        $topic->setDeletedBy(null);
        $topic->setDeletedDate(null);

        $this->persist($topic)->flush();

        // Update affected Topic stats.
        $this->updateStats($topic);

        return $this;
    }
	
    /**
     *
     * @access public
     * @param Array $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkRestore($topics)
    {
        $boardsToUpdate = array();

        foreach ($topics as $topic) {
            // Add the board of the topic to be updated.
            if ($topic->getBoard()) {
                if ( ! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                    $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                }
            }
	
			// Remove deletion attributes.
            $topic->setIsDeleted(false);
            $topic->setDeletedBy(null);
            $topic->setDeletedDate(null);

            $this->persist($topic);
        }

        $this->flush();

        if (count($boardsToUpdate) > 0) {
            // Update all affected board stats.
            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
        }
		
        return $this;
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
        // Don't overwite previous users accountability.
        if ( ! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
            $topic->setIsDeleted(true);
            $topic->setDeletedBy($user);
            $topic->setDeletedDate(new \DateTime());

            // Close the topic as a precaution.
            $topic->setIsClosed(true);
            $topic->setClosedBy($user);
            $topic->setClosedDate(new \DateTime());

            // update the record before doing record counts
            $this->persist($topic)->flush();

            // Update affected Topic stats.
            $this->updateStats($topic);
        }

        return $this;
    }
	
    /**
     *
     * @access public
     * @param array $topics
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkSoftDelete($topics, UserInterface $user)
    {
        $boardsToUpdate = array();
		
        foreach ($topics as $topic) {
            // Don't overwite previous users accountability.
            if ( ! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
                // Add the board of the topic to be updated.
                if ($topic->getBoard()) {
                    if ( ! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                        $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                    }
                }

				// Set the deletion attributes.
                $topic->setIsDeleted(true);
                $topic->setDeletedBy($user);
                $topic->setDeletedDate(new \DateTime());

                $this->persist($topic);
            }
        }

        $this->flush();

        if (count($boardsToUpdate) > 0) {
            // Update all affected board stats.
            $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
        }
		
        return $this;
    }
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
	public function hardDelete(Topic $topic)
	{
        $usersPostCountToUpdate = array();

		// Add the board of the topic to be updated.
		if ($topic->getBoard()) {
			$boardToUpdate = $topic->getBoard();
		}

		// Add author of each post to chain of cached post counts to update.
		if (count($topic->getPosts()) > 0) {
			foreach($topic->getPosts() as $postKey => $post) {
				if ($post->getCreatedBy()) {
			         $author = $post->getCreatedBy();
        
			         if ( ! array_key_exists($author->getId(), $usersPostCountToUpdate)) {
			             $usersPostCountToUpdate[$author->getId()] = $author;
			         }
			     }
			}
		}

		$this->remove($topic);

        $this->flush();

        // Update all affected Board stats.
		if (is_object($boardToUpdate) && $boardToUpdate instanceof Board) {
	        $this->managerBag->getBoardManager()->bulkUpdateStats(array($boardToUpdate))->flush();
		}
		
        // Update all affected Users cached post counts.
        if (count($usersPostCountToUpdate) > 0) {
			$this->managerBag->getRegistryManager()->bulkUpdateCachedPostCountForUsers($usersPostCountToUpdate)->flush();
		}

		return $this;
	}

    /**
     *
     * @access public
     * @param Array $topics
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function bulkHardDelete($topics)
    {
        $boardsToUpdate = array();
        $usersPostCountToUpdate = array();

        // Remove topics.
        foreach ($topics as $topic) {
            // Add the board of the topic to be updated.
            if ($topic->getBoard()) {
                if ( ! array_key_exists($topic->getBoard()->getId(), $boardsToUpdate)) {
                    $boardsToUpdate[$topic->getBoard()->getId()] = $topic->getBoard();
                }
            }

            // Add author of each post to chain of cached post counts to update.
            if (count($topic->getPosts()) > 0) {
				foreach($topic->getPosts() as $postKey => $post) {
					if ($post->getCreatedBy()) {
	                    $author = $post->getCreatedBy();

	                    if ( ! array_key_exists($author->getId(), $usersPostCountToUpdate)) {
	                        $usersPostCountToUpdate[$author->getId()] = $author;
	                    }
	                }
				}
            }

            $this->remove($topic);
        }

        $this->flush();

        // Update all affected Board stats.
		if (count($boardsToUpdate) > 0) {
	        $this->managerBag->getBoardManager()->bulkUpdateStats($boardsToUpdate)->flush();
		}
		
        // Update all affected Users cached post counts.
        if (count($usersPostCountToUpdate) > 0) {
			$this->managerBag->getRegistryManager()->bulkUpdateCachedPostCountForUsers($usersPostCountToUpdate)->flush();
		}

        return $this;
    }
}