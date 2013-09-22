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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent;
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

        $crumbs = $this->getCrumbs()->addAdminManageForumsIndex();

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/list.html.',
            array(
                'crumbs' => $crumbs,
                'forums' => $forums
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
        $this->isAuthorised('ROLE_SUPER_ADMIN');

        $formHandler = $this->getFormHandlerToCreateForum();

        $crumbs = $this->getCrumbs()->addAdminManageForumsCreate();

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.',
            array(
                'crumbs' => $crumbs,
                'form' => $formHandler->getForm()->createView()
            )
        );

        $this->dispatch(ForumEvents::ADMIN_FORUM_CREATE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), null, $response));

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

            $forum = $formHandler->getForm()->getData();

            $this->dispatch(ForumEvents::ADMIN_FORUM_CREATE_COMPLETE, new AdminForumEvent($this->getRequest(), $forum));

            $response = $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
        } else {

            $crumbs = $this->getCrumbs()->addAdminManageForumsCreate();

            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/create.html.',
                array(
                    'crumbs' => $crumbs,
                    'form' => $formHandler->getForm()->createView()
                )
            );
        }

        $this->dispatch(ForumEvents::ADMIN_FORUM_CREATE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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

        $forum = $this->getForumModel()->findOneForumById($forumId);

        $this->isFound($forum);

        $formHandler = $this->getFormHandlerToUpdateForum($forum);

        $crumbs = $this->getCrumbs()->addAdminManageForumsEdit($forum);

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.',
            array(
                'crumbs' => $crumbs,
                'form' => $formHandler->getForm()->createView(),
                'forum' => $forum
            )
        );

        $this->dispatch(ForumEvents::ADMIN_FORUM_EDIT_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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

        $forum = $this->getForumModel()->findOneForumById($forumId);

        $this->isFound($forum);

        $formHandler = $this->getFormHandlerToUpdateForum($forum);

        if ($formHandler->process()) {

            $forum = $formHandler->getForm()->getData();

            $this->dispatch(ForumEvents::ADMIN_FORUM_EDIT_COMPLETE, new AdminForumEvent($this->getRequest(), $forum));

            $response = $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
        } else {

            $crumbs = $this->getCrumbs()->addAdminManageForumsEdit($forum);

            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/edit.html.',
                array(
                    'crumbs' => $crumbs,
                    'form' => $formHandler->getForm()->createView(),
                    'forum' => $forum
                )
            );
        }

        $this->dispatch(ForumEvents::ADMIN_FORUM_EDIT_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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

        $forum = $this->getForumModel()->findOneForumById($forumId);

        $this->isFound($forum);

        $formHandler = $this->getFormHandlerToDeleteForum($forum);

        $crumbs = $this->getCrumbs()->addAdminManageForumsDelete($forum);

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/delete.html.',
            array(
                'crumbs' => $crumbs,
                'form' => $formHandler->getForm()->createView(),
                'forum' => $forum
            )
        );

        $this->dispatch(ForumEvents::ADMIN_FORUM_DELETE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

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

        $forum = $this->getForumModel()->findOneForumById($forumId);

        $this->isFound($forum);

        $formHandler = $this->getFormHandlerToDeleteForum($forum);

        if ($formHandler->process()) {

            $forum = $formHandler->getForm()->getData();

            $this->dispatch(ForumEvents::ADMIN_FORUM_DELETE_COMPLETE, new AdminForumEvent($this->getRequest(), $forum));

            $response = $this->redirectResponse($this->path('ccdn_forum_admin_forum_list'));
        } else {

            $crumbs = $this->getCrumbs()->addAdminManageForumsDelete($forum);

            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Forum/delete.html.',
                array(
                    'crumbs' => $crumbs,
                    'form' => $formHandler->getForm()->createView(),
                    'forum' => $forum
                )
            );
        }

        $this->dispatch(ForumEvents::ADMIN_FORUM_DELETE_RESPONSE, new AdminForumResponseEvent($this->getRequest(), $formHandler->getForm()->getData(), $response));

        return $response;
    }
}
