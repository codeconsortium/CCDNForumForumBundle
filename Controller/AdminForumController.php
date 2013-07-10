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
class AdminForumController extends AdminForumBaseController
{
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function listAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		$forums = $this->getForumModel()->findAllForums();
		
		return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/list.html.', 
			array(
				'forums' => $forums
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
		
		$formHandler = $this->getFormHandlerToCreateForum();
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.', 
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

		$formHandler = $this->getFormHandlerToCreateForum();
		
		if ($formHandler->process($this->getRequest())) {
			return $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.', 
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
    public function editAction($forumId)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$forum = $this->getForumModel()->findOneForumById($forumId);
	
		$this->isFound($forum);
		
		$formHandler = $this->getFormHandlerToUpdateForum($forum);
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum' => $forum
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editProcessAction($forumId)
    {
        $this->isAuthorised('ROLE_ADMIN');

		$forum = $this->getForumModel()->findOneForumById($forumId);
	
		$this->isFound($forum);
		
		$formHandler = $this->getFormHandlerToUpdateForum($forum);

		if ($formHandler->process($this->getRequest())) {
			return $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
		}
		
        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.', 
			array(
				'form' => $formHandler->getForm()->createView(),
				'forum' => $forum
	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function deleteAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/delete.html.', 
			array(

	        )
		);
    }
	
    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function deleteConfirmedAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

		return $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
    }
}