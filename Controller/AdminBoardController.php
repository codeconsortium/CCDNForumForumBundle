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

		$boards = $this->getBoardModel()->findAllBoards();
		
		return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/list.html.', 
			array(
				'boards' => $boards
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
		
		$formHandler = $this->getFormHandlerToCreateBoard();
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView()
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

		$formHandler = $this->getFormHandlerToCreateBoard();
		
		if ($formHandler->process($this->getRequest())) {
			return $this->redirectResponse($this->path('ccdn_forum_admin_board_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/create.html.', 
			array(
				'form' => $formHandler->getForm()->createView()
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
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board
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
			return $this->redirectResponse($this->path('ccdn_forum_admin_board_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board
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
        $this->isAuthorised('ROLE_ADMIN');

		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToDeleteBoard($board);

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board
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
        $this->isAuthorised('ROLE_ADMIN');

		$board = $this->getBoardModel()->findOneBoardById($boardId);
	
		$this->isFound($board);
		
		$formHandler = $this->getFormHandlerToDeleteBoard($board);

		if ($formHandler->process($this->getRequest())) {
			return $this->redirectResponse($this->path('ccdn_forum_admin_board_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Board/delete.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'board' => $board
	        )
		);
    }
}