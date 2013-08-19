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
class UserBoardController extends UserBoardBaseController
{
    /**
     *
     * @access public
     * @param  string                          $forumName
     * @param  int                             $boardId
     * @return RedirectResponse|RenderResponse
     */
    public function showAction($forumName, $boardId)
    {
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        // check board exists.
        $board = $this->getBoardModel()->findOneBoardByIdWithCategory($boardId);
        $this->isFound($board);

		$this->isAuthorised($this->getAuthorizer()->canShowBoard($board, $forum));

		// Get topics.
		$page = $this->getQuery('page', 1);
        $stickyTopics = $this->getTopicModel()->findAllTopicsStickiedByBoardId($boardId, true);
        $topicsPager = $this->getTopicModel()->findAllTopicsPaginatedByBoardId($boardId, $page, true);

        // this is necessary for working out the last page for each topic.
        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');

        // setup bread crumbs.
        $category = $board->getCategory();

		$crumbs = $this->getCrumbs()->addUserBoardShow($forum, $board);

        return $this->renderResponse('CCDNForumForumBundle:User:Board/show.html.',
			array(
	            'crumbs' => $crumbs,
				'forum' => $forum,
	            'board' => $board,
	            'pager' => $topicsPager,
	            'posts_per_page' => $postsPerPage,
	            'sticky_topics' => $stickyTopics,
	        )
		);
    }
}
