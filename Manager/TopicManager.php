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

use CCDNForum\ForumBundle\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicManager extends BaseManager implements ManagerInterface
{
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
            $this->container->get('ccdn_forum_forum.manager.board')->updateStats($topic->getBoard())->flush();
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
        $topicRepository = $this->container->get('ccdn_forum_forum.repository.topic');
        $postRepository = $this->container->get('ccdn_forum_forum.repository.post');

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
            $this->container->get('ccdn_forum_forum.manager.board')->updateStats($topic->getBoard())->flush();
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
