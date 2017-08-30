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
     * Has 2 modes, when forum name is specified, categories for that forum are listed.
     * But, when forumName is ommitted all unassigned categories are listed.
     *
     * Optional Defaults:
     * default = unassigned categories only (will not include categories assigned to a forum, acts as default).
     *
     * @access public
     * @param  string         $forumName
     * @return RenderResponse
     */
    public function indexAction($forumName)
    {
        if ($forumName != 'default') {
            $this->isFound($forum = $this->getForumModel()->findOneForumByName($forumName));
            $this->isAuthorised($this->getAuthorizer()->canShowForum($forum));
        } else {
            $forum = null;
            $this->isAuthorised($this->getAuthorizer()->canShowForumUnassigned());
        }
        $requested = "";
        if(isset($_GET['searchterm']))
        {
          $requested = $_GET['searchterm'];
          $request = $requested;
          parse_str($requested);
          if(!empty($request) || $request!=" ")
          {
            $request = strip_tags($request);
            $request = str_replace("'","",$request);
            $request = str_replace(" ","%' AND p.body LIKE '%",$request);
            $posts = $this->getPostModel()->findAllPostsExtendingString($request);
          } else {
            $posts = [];
          }
        } else {
          $posts = [];        }
        $categories = $this->getCategoryModel()->findAllCategoriesWithBoardsForForumByName($forumName);

        return $this->renderResponse('CCDNForumForumBundle:User:Category/index.html.', array(
            'crumbs' => $this->getCrumbs()->addUserCategoryIndex($forum),
            'forum' => $forum,
            'forumName' => $forumName,
            'categories' => $categories,
            'posts' => $posts,
            'requested' => $requested,
            'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.board.user.show.topics_per_page'),
        ));
    }

    /**
*
* @access public
* @param Request $request
* @return RenderResponse
*
*/


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
            'forumName' => $forumName,
            'category' => $category,
            'categories' => array($category),
            'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.board.user.show.topics_per_page'),
        ));
    }
}
