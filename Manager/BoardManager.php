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
class BoardManager extends BaseManager implements ManagerInterface
{

    /**
     *
     * @access public
     * @param Board $board
     * @return self
     */
    public function updateStats($board)
    {
        $counters = $this->container->get('ccdn_forum_forum.repository.board')->getTopicAndPostCountsForBoard($board->getId());

        // set the board topic / post count
        $board->setCachedTopicCount($counters['topicCount']);
        $board->setCachedPostCount($counters['postCount']);

        $last_topic = $this->container->get('ccdn_forum_forum.repository.board')->findLastTopicForBoard($board->getId());

        // set last_post for board
        if ($last_topic) {
            $board->setLastPost( (($last_topic->getLastPost()) ? $last_topic->getLastPost() : null) );
        } else {
            $board->setLastPost(null);
        }

        $this->persist($board);

        return $this;
    }

    /**
     *
     * @access public
     * @param $boards
     * @return self
     */
    public function bulkUpdateStats($boards)
    {
        foreach ($boards as $board) {
            $this->updateStats($board);
        }

        $this->flush();

        return $this;
    }

}
