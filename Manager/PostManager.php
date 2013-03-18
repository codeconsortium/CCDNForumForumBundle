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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostManager extends BaseManager implements BaseManagerInterface
{
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return bool
	 */
	public function isAuthorisedToEditPost($post)
	{
		if ($post->getIsDeleted() || $post->getIsLocked() || $post->getTopic()->getIsDeleted() || $post->getTopic()->getIsClosed()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		
		if ($this->isGranted('ROLE_USER')) {
	        if ($post->getCreatedBy()) {
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
		
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return bool
	 */
	public function isAuthorisedToDeletePost($post)
	{
		if ($post->getIsDeleted() || $post->getIsLocked() || $post->getTopic()->getIsDeleted() || $post->getTopic()->getIsClosed()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		
		if ($this->isGranted('ROLE_USER')) {
	        if ($post->getCreatedBy()) {
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
		
		return true;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Post $post
	 * @return bool
	 */
	public function isAuthorisedToRestorePost($post)
	{
		if ($post->getIsDeleted() || $post->getIsLocked() || $post->getTopic()->getIsDeleted() || $post->getTopic()->getIsClosed()) {
			if (! $this->isGranted('ROLE_MODERATOR')) {
				return false;
			}
		}
		        
		if ($this->isGranted('ROLE_MODERATOR')) {
	        if ($post->getCreatedBy()) {
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
		
		return true;
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
	 * @param int $topicId
	 * @return \CCDNForum\ForumBundle\Entity\Post
	 */	
	public function getFirstPostForTopicById($topicId)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}
		
		$qb = $this->createSelectQuery(array('p'));
		
		$qb
			->where('p.topic = :topicId')
			->orderBy('p.createdDate', 'ASC')
			->setMaxResults(1);
		
		return $this->gateway->findPost($qb, array(':topicId' => $topicId));
	}
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 * @return \CCDNForum\ForumBundle\Entity\Post
	 */	
	public function getLastPostForTopicById($topicId)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}

		$qb = $this->createSelectQuery(array('p'));

		$qb
			->where('p.topic = :topicId')
			->orderBy('p.createdDate', 'DESC')
			->setMaxResults(1);

		return $this->gateway->findPost($qb, array(':topicId' => $topicId));
	}
	
	/**
	 *
	 * @access public
	 * @param int $postId
	 * @return \CCDNForum\ForumBundle\Entity\Post
	 */	
	public function findOneByIdWithTopicAndBoard($postId)
	{
		if (null == $postId || ! is_numeric($postId) || $postId == 0) {
			throw new \Exception('Post id "' . $postId . '" is invalid!');
		}
		
		$qb = $this->createSelectQuery(array('p', 't', 'b'));
		
		$qb
			->innerJoin('p.topic', 't')
			->leftJoin('t.firstPost', 'fp')
			->leftJoin('t.lastPost', 'lp')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->where(
				$this->limitQueryByTopicsDeletedStateAndByPostId($qb)
			);
		
		return $this->gateway->findPost($qb, array(':postId' => $postId));
	}
	
	/**
	 *
	 * @access public
	 * @param int $topicId
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */	
	public function findAllPaginatedByTopicId($topicId, $page)
	{
		if (null == $topicId || ! is_numeric($topicId) || $topicId == 0) {
			throw new \Exception('Topic id "' . $topicId . '" is invalid!');
		}
		
		$params = array(':topicId' => $topicId);
		
		$qb = $this->createSelectQuery(array('p', 't', 'b', 'c', 'createdBy', 'editedBy', 'deletedBy'));
		
		$qb
			->join('p.topic', 't')
			->leftJoin('t.board', 'b')
			->leftJoin('b.category', 'c')
			->leftJoin('p.createdBy', 'createdBy')
			->leftJoin('p.editedBy', 'editedBy')
			->leftJoin('p.deletedBy', 'deletedBy')
			->where('p.topic = :topicId')
			->setParameters($params)
			->orderBy('p.createdDate', 'ASC');

		return $this->gateway->paginateQuery($qb, $this->getPostsPerPageOnTopics(), $page);
	}
	
	/**
	 *
	 * @access protected
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function limitQueryByTopicsDeletedStateAndByPostId(QueryBuilder $qb)
	{
		if ($this->managerBag->getTopicManager()->allowedToViewDeletedTopics()) {
			$expr = $qb->expr()->eq('p.id', ':postId');
		} else {
			$expr = $qb->expr()->andX(
				$qb->expr()->eq('p.id', ':postId'),
				$qb->expr()->eq('t.isDeleted', false)
			);
		}
		
		return $expr;
	}
	
    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function create($post)
    {
        // insert a new row
        $this->persist($post)->flush();

        // refresh the user so that we have an PostId to work with.
        $this->refresh($post);

        // Update affected Topic stats.
        $this->managerBag->getTopicManager()->updateStats($post->getTopic());

		// Subscribe the user to the topic.
		$this->managerBag->getSubscriptionManager()->subscribe($post->getTopic())->flush();
		
        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function update($post)
    {
        // update a record
        $this->persist($post);

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
	 * @todo get rid of user parameter and use $this->getUser()
     */
    public function softDelete($post, $user)
    {
        // Don't overwite previous users accountability.
        if ( ! $post->getDeletedBy() && ! $post->getDeletedDate()) {
            $post->setIsDeleted(true);
            $post->setDeletedBy($user);
            $post->setDeletedDate(new \DateTime());

            // Lock the post as a precaution.
            $post->setIsLocked(true);
            $post->setLockedBy($user);
            $post->setLockedDate(new \DateTime());

            // update the record
            $this->persist($post)->flush();

            if ($post->getTopic()) {
                $topic = $post->getTopic();

                // if this is the first post and only post, then soft delete the topic aswell.
                if ($topic->getCachedReplyCount() < 1) {
                    // Don't overwite previous users accountability.
                    if ( ! $topic->getDeletedBy() && ! $topic->getDeletedDate()) {
                        $topic->setIsDeleted(true);
                        $topic->setDeletedBy($user);
                        $topic->setDeletedDate(new \DateTime());

                        // Close the topic as a precaution.
                        $topic->setIsClosed(true);
                        $topic->setClosedBy($user);
                        $topic->setClosedDate(new \DateTime());

                        $this->persist($topic)->flush();

                        // Update affected Topic stats.
                        $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
                    }
                }
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function restore($post)
    {
        $post->setIsDeleted(false);
        $post->setDeletedBy(null);
        $post->setDeletedDate(null);

        // update the record
        $this->persist($post)->flush();

        if ($post->getTopic()) {
            $topic = $post->getTopic();

            // if this is the first post and only post,
            // then restore the topic aswell.
            if ($topic->getCachedReplyCount() < 1) {
                $topic->setIsDeleted(false);
                $topic->setDeletedBy(null);
                $topic->setDeletedDate(null);

                $this->persist($topic)->flush();

                // Update affected Topic stats.
                $this->managerBag->getTopicManager()->updateStats($post->getTopic())->flush();
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post, $user
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function lock($post, $user)
    {
        // Don't overwite previous users accountability.
        if (! $post->getLockedBy() && ! $post->getLockedDate()) {
            $post->setIsLocked(true);
            $post->setLockedBy($user);
            $post->setLockedDate(new \DateTime());

            $this->persist($post);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function unlock($post)
    {
        $post->setIsLocked(false);
        $post->setLockedBy(null);
        $post->setLockedDate(null);

        $this->persist($post);

        return $this;
    }
}