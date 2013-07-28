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
class AdminBoardController extends AdminBoardBaseController
{
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function listAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		// Forum / Category Parametric Filter.
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);

		// Corrective Measure incase forum/category filters fall out of sync.
		if ($categoryFilter) {
			$category = $this->getCategoryModel()->findOneById($categoryFilter);
			
			if ($category->getForum()) {
				$forumFilter = $category->getForum()->getId();
			} else {
				$forumFilter = null; // Force it to be blank so 'unassigned' is highlighted.
			}
		}

		// Forums for the parametric filter.
		$forums = $this->getForumModel()->findAllForums();

		// Categories for the parametric filter.
		$categories = $this->getCategoryModel()->findAllCategoriesForForum($forumFilter);

		// Boards for the configuration table list.
		$boards = $this->getBoardModel()->findAllBoardsForCategory($categoryFilter);
		
		return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/list.html.', 
			array(
				'forums' => $forums,
				'forum_filter' => $forumFilter,
				'categories' => $categories,
				'category_filter' => $categoryFilter,
				'boards' => $boards,
	        )
		);
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createAction()
    {
        $this->isAuthorised('ROLE_ADMIN');
		
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
		$formHandler = $this->getFormHandlerToCreateBoard($categoryFilter);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createProcessAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
		$formHandler = $this->getFormHandlerToCreateBoard($categoryFilter);
		
		if ($formHandler->process($this->getRequest())) {
			
			$params = $this->getFilterQueryStrings($formHandler->getForm()->getData());
			
			return $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editAction($boardId)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToUpdateBoard($board);
		
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('forum_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board,
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editProcessAction($boardId)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToUpdateBoard($board);

		if ($formHandler->process($this->getRequest())) {

			$params = $this->getFilterQueryStrings($board);
			
			return $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		}
		
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board,
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function deleteAction($boardId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');

		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToDeleteBoard($board);

		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board,
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function deleteProcessAction($boardId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');

		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToDeleteBoard($board);
		
		if ($formHandler->process($this->getRequest())) {
			
			$params = $this->getFilterQueryStrings($board);
			
			return $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		}
		
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board,
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
    }
}