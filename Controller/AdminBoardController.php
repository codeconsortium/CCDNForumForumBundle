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
        $forumFilter = $this->getQuery('forum_filter', null);
        $categoryFilter = $this->getQuery('category_filter', null);

        if ($categoryFilter) { // Corrective Measure incase forum/category filters fall out of sync.
            $category = $this->getCategoryModel()->findOneCategoryById($categoryFilter);
            if ($category) {
                if ($category->getForum()) {
                    $forumFilter = $category->getForum()->getId();
                } else {
                    $forumFilter = null; // Force it to be blank so 'unassigned' is highlighted.
                }
            } else {
                $forumFilter = null;
            }
        }

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/list.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageBoardsIndex(),
            'forums' => $this->getForumModel()->findAllForums(), // Forums for the parametric filter.,
            'forum_filter' => $forumFilter,
            'categories' => $this->getCategoryModel()->findAllCategoriesForForumById($forumFilter), // Categories for the parametric filter.,
            'category_filter' => $categoryFilter,
            'boards' => $this->getBoardModel()->findAllBoardsForCategoryById($categoryFilter), // Boards for the configuration table list.,
        ));

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
        $categoryFilter = $this->getQuery('category_filter', null);
        $formHandler = $this->getFormHandlerToCreateBoard($categoryFilter);
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageBoardsCreate(),
            'form' => $formHandler->getForm()->createView(),
            'forum_filter' => $this->getQuery('forum_filter', null),
            'category_filter' => $categoryFilter
        ));

        $this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response));

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
        $categoryFilter = $this->getQuery('category_filter', null);
        $formHandler = $this->getFormHandlerToCreateBoard($categoryFilter);

        if ($formHandler->process()) {
            $params = $this->getFilterQueryStrings($formHandler->getForm()->getData());
            $response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', array(
                'crumbs' => $this->getCrumbs()->addAdminManageBoardsCreate(),
                'form' => $formHandler->getForm()->createView(),
                'forum_filter' => $this->getQuery('forum_filter', null),
                'category_filter' => $categoryFilter
            ));
        }

        $this->dispatch(ForumEvents::ADMIN_BOARD_CREATE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

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
        $this->isFound($board = $this->getBoardModel()->findOneBoardById($boardId));
        $formHandler = $this->getFormHandlerToUpdateBoard($board);
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageBoardsEdit($board),
            'form' => $formHandler->getForm()->createView(),
            'board' => $board,
            'forum_filter' => $this->getQuery('forum_filter', null),
            'category_filter' => $this->getQuery('category_filter', null)
        ));

        $this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

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
        $this->isFound($board = $this->getBoardModel()->findOneBoardById($boardId));
        $formHandler = $this->getFormHandlerToUpdateBoard($board);

        if ($formHandler->process()) {
            $response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $this->getFilterQueryStrings($formHandler->getForm()->getData())));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', array(
                'crumbs' => $this->getCrumbs()->addAdminManageBoardsEdit($board),
                'form' => $formHandler->getForm()->createView(),
                'board' => $board,
                'forum_filter' => $this->getQuery('forum_filter', null),
                'category_filter' => $this->getQuery('category_filter', null)
            ));
        }

        $this->dispatch(ForumEvents::ADMIN_BOARD_EDIT_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

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
        $this->isFound($board = $this->getBoardModel()->findOneBoardById($boardId));
        $formHandler = $this->getFormHandlerToDeleteBoard($board);
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageBoardsDelete($board),
            'form' => $formHandler->getForm()->createView(),
            'board' => $board,
            'forum_filter' => $this->getQuery('forum_filter', null),
            'category_filter' => $this->getQuery('category_filter', null)
        ));

        $this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

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
        $this->isFound($board = $this->getBoardModel()->findOneBoardById($boardId));
        $formHandler = $this->getFormHandlerToDeleteBoard($board);

        if ($formHandler->process()) {
            $params = $this->getFilterQueryStrings($formHandler->getForm()->getData());
            $response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', array(
                'crumbs' => $this->getCrumbs()->addAdminManageBoardsIndex($board),
                'form' => $formHandler->getForm()->createView(),
                'board' => $board,
                'forum_filter' => $this->getQuery('forum_filter', null),
                'category_filter' => $this->getQuery('category_filter', null)
            ));
        }

        $this->dispatch(ForumEvents::ADMIN_BOARD_DELETE_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

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
        $this->isFound($board = $this->getBoardModel()->findOneBoardById($boardId));
        $this->dispatch(ForumEvents::ADMIN_BOARD_REORDER_INITIALISE, new AdminBoardEvent($this->getRequest(), $board));
        $params = array();

        if ($board->getCategory()) { // We do not re-order boards not set to a category.
            $categoryFilter = $board->getCategory()->getId();
            $params['category_filter'] = $categoryFilter;
            if ($board->getCategory()->getForum()) {
                $params['forum_filter'] = $board->getCategory()->getForum()->getId();
            }
            $boards = $this->getBoardModel()->findAllBoardsForCategoryById($categoryFilter);
            $this->getBoardModel()->reorderBoards($boards, $board, $direction);
            $this->dispatch(ForumEvents::ADMIN_BOARD_REORDER_COMPLETE, new AdminBoardEvent($this->getRequest(), $board));
        }

        $response = $this->redirectResponse($this->path('ccdn_forum_admin_board_list', $params));
        $this->dispatch(ForumEvents::ADMIN_BOARD_REORDER_RESPONSE, new AdminBoardResponseEvent($this->getRequest(), $response, $board));

        return $response;
    }
}
