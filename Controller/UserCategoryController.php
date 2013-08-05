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
class UserCategoryController extends BaseController
{
    /**
     *
     * @access public
     * @param  string         $forumName
     * @return RenderResponse
     */
    public function indexAction($forumName)
    {
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $categories = $this->getCategoryModel()->findAllCategoriesWithBoardsForForumByName($forumName);

        $topicsPerPage = $this->container->getParameter('ccdn_forum_forum.board.show.topics_per_page');

		$crumbs = $this->getCrumbs()->addUserCategoryIndex($forum);
		
        return $this->renderResponse('CCDNForumForumBundle:User:/Category/index.html.',
			array(
	            'crumbs' => $crumbs,
				'forum' => $forum,
	            'categories' => $categories,
	            'topics_per_page' => $topicsPerPage,
	        )
		);
    }

    /**
     *
     * @access public
     * @param  string         $forumName
     * @param  int            $categoryId
     * @return RenderResponse
     */
    public function showAction($forumName, $categoryId)
    {
		$forum = $this->getForumModel()->findOneForumByName($forumName);
		$this->isFound($forum);
		
        $category = $this->getCategoryModel()->findOneCategoryByIdWithBoards($categoryId);
        $this->isFound($category);

        $topicsPerPage = $this->container->getParameter('ccdn_forum_forum.board.show.topics_per_page');

		$crumbs = $this->getCrumbs()->addUserCategoryShow($forum, $category);
		
        return $this->renderResponse('CCDNForumForumBundle:User:Category/show.html.',
			array(
	            'crumbs' => $crumbs,
				'forum' => $forum,
	            'category' => $category,
	            'categories' => array($category),
	            'topics_per_page' => $topicsPerPage,
	        )
		);
    }
}
