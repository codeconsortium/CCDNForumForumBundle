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
class UserBoardController extends BaseController
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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($board = $this->getBoardModel()->findOneBoardByIdWithCategory($boardId));
        $this->isAuthorised($this->getAuthorizer()->canShowBoard($board, $forum));
        $itemsPerPage = $this->getPageHelper()->getTopicsPerPageOnBoards();
        $stickyTopics = $this->getTopicModel()->findAllTopicsStickiedByBoardId($boardId, true);
        $topicsPager = $this->getTopicModel()->findAllTopicsPaginatedByBoardId($boardId, $this->getQuery('page', 1), $itemsPerPage, true);

        return $this->renderResponse('CCDNForumForumBundle:User:Board/show.html.', array(
            'crumbs' => $this->getCrumbs()->addUserBoardShow($forum, $board),
            'forum' => $forum,
            'forumName' => $forumName,
            'board' => $board,
            'pager' => $topicsPager,
            'posts_per_page' => $this->container->getParameter('ccdn_forum_forum.topic.user.show.posts_per_page'), // for working out last page per topic.
            'sticky_topics' => $stickyTopics,
        ));
    }
}
