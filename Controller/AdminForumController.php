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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumResponseEvent;

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
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $forums = $this->getForumModel()->findAllForums();
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/list.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageForumsIndex(),
            'forums' => $forums
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
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $formHandler = $this->getFormHandlerToCreateForum();
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageForumsCreate(),
            'form' => $formHandler->getForm()->createView()
        ));

        $this->dispatch(ForumEvents::ADMIN_FORUM_CREATE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $response));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function createProcessAction()
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $formHandler = $this->getFormHandlerToCreateForum();

        if ($formHandler->process()) {
            $response = $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.', array(
                'crumbs' => $this->getCrumbs()->addAdminManageForumsCreate(),
                'form' => $formHandler->getForm()->createView()
            ));
        }

        $this->dispatch(ForumEvents::ADMIN_FORUM_CREATE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editAction($forumId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $this->isFound($forum = $this->getForumModel()->findOneForumById($forumId));
        $formHandler = $this->getFormHandlerToUpdateForum($forum);
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageForumsEdit($forum),
            'form' => $formHandler->getForm()->createView(),
            'forum' => $forum
        ));

        $this->dispatch(ForumEvents::ADMIN_FORUM_EDIT_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editProcessAction($forumId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $this->isFound($forum = $this->getForumModel()->findOneForumById($forumId));
        $formHandler = $this->getFormHandlerToUpdateForum($forum);

        if ($formHandler->process()) {
            $response = $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.', array(
                'crumbs' => $this->getCrumbs()->addAdminManageForumsEdit($forum),
                'form' => $formHandler->getForm()->createView(),
                'forum' => $forum
            ));
        }

        $this->dispatch(ForumEvents::ADMIN_FORUM_EDIT_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function deleteAction($forumId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $this->isFound($forum = $this->getForumModel()->findOneForumById($forumId));
        $formHandler = $this->getFormHandlerToDeleteForum($forum);
        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/delete.html.', array(
            'crumbs' => $this->getCrumbs()->addAdminManageForumsDelete($forum),
            'form' => $formHandler->getForm()->createView(),
            'forum' => $forum
        ));

        $this->dispatch(ForumEvents::ADMIN_FORUM_DELETE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function deleteProcessAction($forumId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');
        $this->isFound($forum = $this->getForumModel()->findOneForumById($forumId));
        $formHandler = $this->getFormHandlerToDeleteForum($forum);

        if ($formHandler->process()) {
            $response = $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
        } else {
            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/delete.html.', array(
                'crumbs' => $this->getCrumbs()->addAdminManageForumsDelete($forum),
                'form' => $formHandler->getForm()->createView(),
                'forum' => $forum
            ));
        }

        $this->dispatch(ForumEvents::ADMIN_FORUM_DELETE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }
}
