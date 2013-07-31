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

use Symfony\Component\EventDispatcher\Event;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardResponseEvent;

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
		
		$response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/list.html.', 
			array(
				'forums' => $forums,
				'forum_filter' => $forumFilter,
				'categories' => $categories,
				'category_filter' => $categoryFilter,
				'boards' => $boards,
	        )
		);
		
		return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createAction()
    {
        $this->isAuthorised('ROLE_ADMIN');
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_INITIALISE, new AdminBoardEvent($this->getRequest(), null));
		
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
		$formHandler = $this->getFormHandlerToCreateBoard($categoryFilter);
		
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), null, $response));
		
		return $response;
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createProcessAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		$this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_INITIALISE, new AdminBoardEvent($this->getRequest(), null));

		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
		$formHandler = $this->getFormHandlerToCreateBoard($categoryFilter);
		
		if ($formHandler->process($this->getRequest())) {
			
			$board = $formHandler->getForm()->getData();
			
			$params = $this->getFilterQueryStrings($board);
			
			$this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_COMPLETE, new AdminBoardEvent($this->getRequest(), $board));
			
			$response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		} else {
		
	        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', 
				array(
					'form' => $formHandler->getForm()->createView(),
					'forum_filter' => $forumFilter,
					'category_filter' => $categoryFilter
		        )
			);
		}
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
		return $response;
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

		$this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_INITIALISE, new AdminBoardEvent($this->getRequest(), $board));
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToUpdateBoard($board);
		
		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('forum_filter', null);
		
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board,
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
		return $response;
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
	
		$this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_INITIALISE, new AdminBoardEvent($this->getRequest(), $board));
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToUpdateBoard($board);

		if ($formHandler->process($this->getRequest())) {

			$board = $formHandler->getForm()->getData();
			
			$params = $this->getFilterQueryStrings($board);
			
			$this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_COMPLETE, new AdminBoardEvent($this->getRequest(), $board));
			
			$response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		} else {
		
			$forumFilter = $this->getQuery('forum_filter', null);
			$categoryFilter = $this->getQuery('category_filter', null);
		
	        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', 
				array(
					'form' => $formHandler->getForm()->createView(),
					'board' => $board,
					'forum_filter' => $forumFilter,
					'category_filter' => $categoryFilter
		        )
			);
		}
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
		return $response;
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
	
		$this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_INITIALISE, new AdminBoardEvent($this->getRequest(), $board));
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToDeleteBoard($board);

		$forumFilter = $this->getQuery('forum_filter', null);
		$categoryFilter = $this->getQuery('category_filter', null);
		
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board,
				'forum_filter' => $forumFilter,
				'category_filter' => $categoryFilter
	        )
		);
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
		return $response;
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
	
		$this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_INITIALISE, new AdminBoardEvent($this->getRequest(), $board));
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToDeleteBoard($board);
		
		if ($formHandler->process($this->getRequest())) {
			
			$board = $formHandler->getForm()->getData();
			
			$params = $this->getFilterQueryStrings($board);
			
			$this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_COMPLETE, new AdminBoardEvent($this->getRequest(), $board));
			
			$response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		} else {
		
			$forumFilter = $this->getQuery('forum_filter', null);
			$categoryFilter = $this->getQuery('category_filter', null);
		
	        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', 
				array(
					'form' => $formHandler->getForm()->createView(),
					'board' => $board,
					'forum_filter' => $forumFilter,
					'category_filter' => $categoryFilter
		        )
			);
		}
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));
		
		return $response;
    }

    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function reorderAction($boardId, $direction)
    {
        $this->isAuthorised('ROLE_ADMIN');
    	
		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->dispatch(ForumEvents::ADMIN_BOARD_REORDER_INITIALISE, new AdminBoardEvent($this->getRequest(), $board));
	
		$this->isFound($board);
		
		$params = array();
		
		// We do not re-order boards not set to a category.
		if ($board->getCategory()) {
			$categoryFilter = $board->getCategory()->getId();
			
			$params['category_filter'] = $categoryFilter;
			
			if ($board->getCategory()->getForum()) {
				$params['forum_filter'] = $board->getCategory()->getForum()->getId();
			}
		
			$boards = $this->getBoardModel()->findAllBoardsForCategory($categoryFilter);
			
			$this->getBoardModel()->reorderBoards($boards, $board, $direction);
			
			$this->dispatch(ForumEvents::ADMIN_BOARD_REORDER_COMPLETE, new AdminBoardEvent($this->getRequest(), $board));
		}
	
        $response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
		
		$this->dispatch(ForumEvents::ADMIN_BOARD_REORDER_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $board, $response));
		
		return $response;
    }
}