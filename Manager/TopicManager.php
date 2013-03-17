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
	 * @param int $boardId
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function findAllStickiedByBoardId($boardId)
	{
		if (null == $boardId || ! is_numeric($boardId) || $boardId == 0) {
			throw new \Exception('Board id "' . $boardId . '" is invalid!');
		}
		
		$params = array(':boardId' => $boardId, ':isSticky' => true);
		
		$qb = $this->createSelectQuery(array('t', 'lp', 'lp_author'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
			->innerJoin('t.lastPost', 'lp')
			->innerJoin('fp.createdBy', 'fp_author')
			->innerJoin('lp.createdBy', 'lp_author')
			->where(
				$this->limitQueryByStickiedAndDeletedState($qb)
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
		
		$params = array(':boardId' => $boardId, ':isSticky' => false);
		
		$qb = $this->createSelectQuery(array('t', 'lp', 'lp_author'));
		
		$qb
			->innerJoin('t.firstPost', 'fp')
			->innerJoin('t.lastPost', 'lp')
			->innerJoin('fp.createdBy', 'fp_author')
			->innerJoin('lp.createdBy', 'lp_author')
			->where(
				$this->limitQueryByStickiedAndDeletedState($qb)
			)
			->setParameters($params)
			->orderBy('lp.createdDate', 'DESC');

		return $this->gateway->paginateQuery($qb, $this->getTopicsPerPageOnBoards(), $page);
	}
	
	/**
	 *
	 * @access protected
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function limitQueryByStickiedAndDeletedState(QueryBuilder $qb)
	{
		if ($this->allowedToViewDeletedTopics()) {
			$expr = $qb->expr()->andX(
				$qb->expr()->eq('t.board', ':boardId'),
				$qb->expr()->eq('t.isSticky', ':isSticky')
			);
		} else {
			$expr = $qb->expr()->andX(
				$qb->expr()->eq('t.board', ':boardId'),
				$qb->expr()->andX(
					$qb->expr()->eq('t.isSticky', ':isSticky'),
					$qb->expr()->eq('t.isDeleted', false)
				)
			);
		}
		
		return $expr;
	}
	
    /**
     *
     * @access public
     * @param Post $post
     * @return self
     */
    public function create($post)
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
		//$this->container->get('ccdn_forum_forum.manager.subscription')->subscribe($topic->getId(), $post->getCreatedBy());
		
        return $this;
    }

    /**
     *
     * @access public
     * @param Topic $topic
     * @return self
     */
    public function update($topic)
    {
        // update the record
        $this->persist($topic);

        return $this;
    }

    /**
     *
     * @access public
     * @param Topic $topic
     * @return self
     */
    public function restore($topic)
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
     * @param Topic $topic, $user
     * @return self
     */
    public function softDelete($topic, $user)
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
     * @param Topic $topic, $user
     * @return self
     */
    public function close($topic, $user)
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
     * @param Topic $topic
     * @return self
     */
    public function reopen($topic)
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
     * @param Topic $topic
     * @return self
     */
    public function sticky($topic, $user)
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
     * @param Topic $topic
     * @return self
     */
    public function unsticky($topic)
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
     * @return self
     */
    public function updateStats($topic)
    {
        $topicRepository = $this->repository;
        $postRepository = $this->managerBag->getPostManager()->getRepository();

        // Gets stats.
        $topicReplyCount = $postRepository->getPostCountForTopicById($topic->getId());
        $topicFirstPost = $topicRepository->getFirstPostForTopic($topic->getId());
        $topicLastPost = $topicRepository->getLastPostForTopic($topic->getId());

        // Set the board / topic last post.
        $topic->setCachedReplyCount( (($topicReplyCount) ? --$topicReplyCount : 0) );
        $topic->setFirstPost( (($topicFirstPost) ? $topicFirstPost : null) );
        $topic->setLastPost( (($topicLastPost) ? $topicLastPost : null) );

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
     * @return self
     */
    public function bulkUpdateStats($topics)
    {
        foreach ($topics as $topic) {
            $this->updateStats($topic);
        }
    }

    /**
     *
     * @access public
     * @param Topic $topic
     */
    public function incrementViewCounter($topic)
    {
        // set the new counters
        $topic->setCachedViewCount($topic->getCachedViewCount() + 1);

        $this->persist($topic)->flush();
    }
}