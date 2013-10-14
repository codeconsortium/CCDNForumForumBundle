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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $categories = $this->getCategoryModel()->findAllCategoriesWithBoardsForForumByName($forumName);
        $this->isAuthorised($this->getAuthorizer()->canShowForum($forum, $forum));

        return $this->renderResponse('CCDNForumForumBundle:User:/Category/index.html.', array(
            'crumbs' => $this->getCrumbs()->addUserCategoryIndex($forum),
            'forum' => $forum,
            'categories' => $categories,
            'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.board.user.show.topics_per_page'),
        ));
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
        $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
        $this->isFound($category = $this->getCategoryModel()->findOneCategoryByIdWithBoards($categoryId));
        $this->isAuthorised($this->getAuthorizer()->canShowCategory($category, $forum));

        return $this->renderResponse('CCDNForumForumBundle:User:Category/show.html.', array(
            'crumbs' => $this->getCrumbs()->addUserCategoryShow($forum, $category),
            'forum' => $forum,
            'category' => $category,
            'categories' => array($category),
            'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.board.user.show.topics_per_page'),
        ));
    }
}
