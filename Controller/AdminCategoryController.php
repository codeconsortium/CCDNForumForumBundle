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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryResponseEvent;

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
class AdminCategoryController extends AdminCategoryBaseController
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

        $forums = $this->getForumModel()->findAllForums();

        $categories = $this->getCategoryModel()->findAllCategoriesForForumById($forumFilter);

        $crumbs = $this->getCrumbs()->addAdminManageCategoriesIndex();

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/list.html.',
            array(
                'crumbs' => $crumbs,
                'forums' => $forums,
                'forum_filter' => $forumFilter,
                'categories' => $categories,
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

        $forumFilter = $this->getQuery('forum_filter', null);

        $formHandler = $this->getFormHandlerToCreateCategory($forumFilter);

        $crumbs = $this->getCrumbs()->addAdminManageCategoriesCreate();

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/create.html.',
            array(
                'crumbs' => $crumbs,
                'form' => $formHandler->getForm()->createView(),
                'forum_filter' => $forumFilter
            )
        );

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_CREATE_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, null));

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

        $forumFilter = $this->getQuery('forum_filter', null);

        $formHandler = $this->getFormHandlerToCreateCategory($forumFilter);

        if ($formHandler->process()) {

            $category = $formHandler->getForm()->getData();

            $params = $this->getFilterQueryStrings($category);

            $this->dispatch(ForumEvents::ADMIN_CATEGORY_CREATE_COMPLETE, new AdminCategoryEvent($this->getRequest(), $category));

            $response = $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
        } else {
            $crumbs = $this->getCrumbs()->addAdminManageCategoriesCreate();

            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/create.html.',
                array(
                    'crumbs' => $crumbs,
                    'form' => $formHandler->getForm()->createView(),
                    'forum_filter' => $forumFilter
                )
            );
        }

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_CREATE_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editAction($categoryId)
    {
        $this->isAuthorised('ROLE_ADMIN');

        $category = $this->getCategoryModel()->findOneCategoryById($categoryId);

        $this->isFound($category);

        $formHandler = $this->getFormHandlerToUpdateCategory($category);

        $forumFilter = $this->getQuery('forum_filter', null);

        $crumbs = $this->getCrumbs()->addAdminManageCategoriesEdit($category);

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/edit.html.',
            array(
                'crumbs' => $crumbs,
                'form' => $formHandler->getForm()->createView(),
                'category' => $category,
                'forum_filter' => $forumFilter
            )
        );

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_EDIT_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function editProcessAction($categoryId)
    {
        $this->isAuthorised('ROLE_ADMIN');

        $category = $this->getCategoryModel()->findOneCategoryById($categoryId);

        $this->isFound($category);

        $formHandler = $this->getFormHandlerToUpdateCategory($category);

        if ($formHandler->process()) {

            $category = $formHandler->getForm()->getData();

            $params = $this->getFilterQueryStrings($category);

            $this->dispatch(ForumEvents::ADMIN_CATEGORY_EDIT_COMPLETE, new AdminCategoryEvent($this->getRequest(), $category));

            $response = $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
        } else {

            $forumFilter = $this->getQuery('forum_filter', null);

            $crumbs = $this->getCrumbs()->addAdminManageCategoriesEdit($category);

            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/edit.html.',
                array(
                    'crumbs' => $crumbs,
                    'form' => $formHandler->getForm()->createView(),
                    'category' => $category,
                    'forum_filter' => $forumFilter
                )
            );
        }

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_EDIT_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function deleteAction($categoryId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');

        $category = $this->getCategoryModel()->findOneCategoryById($categoryId);

        $this->isFound($category);

        $formHandler = $this->getFormHandlerToDeleteCategory($category);

        $forumFilter = $this->getQuery('forum_filter', null);

        $crumbs = $this->getCrumbs()->addAdminManageCategoriesDelete($category);

        $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/delete.html.',
            array(
                'crumbs' => $crumbs,
                'form' => $formHandler->getForm()->createView(),
                'category' => $category,
                'forum_filter' => $forumFilter
            )
        );

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_DELETE_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function deleteProcessAction($categoryId)
    {
        $this->isAuthorised('ROLE_SUPER_ADMIN');

        $category = $this->getCategoryModel()->findOneCategoryById($categoryId);

        $this->isFound($category);

        $formHandler = $this->getFormHandlerToDeleteCategory($category);

        if ($formHandler->process()) {

            $category = $formHandler->getForm()->getData();

            $params = $this->getFilterQueryStrings($category);

            $this->dispatch(ForumEvents::ADMIN_CATEGORY_DELETE_COMPLETE, new AdminCategoryEvent($this->getRequest(), $category));

            $response = $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));
        } else {

            $forumFilter = $this->getQuery('forum_filter', null);

            $crumbs = $this->getCrumbs()->addAdminManageCategoriesDelete($category);

            $response = $this->renderResponse('CCDNForumForumBundle:Admin:/Category/delete.html.',
                array(
                    'crumbs' => $crumbs,
                    'form' => $formHandler->getForm()->createView(),
                    'category' => $category,
                    'forum_filter' => $forumFilter
                )
            );
        }

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_DELETE_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, $formHandler->getForm()->getData()));

        return $response;
    }

    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function reorderAction($categoryId, $direction)
    {
        $this->isAuthorised('ROLE_ADMIN');

        $category = $this->getCategoryModel()->findOneCategoryById($categoryId);

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_REORDER_INITIALISE, new AdminCategoryEvent($this->getRequest(), $category));

        $this->isFound($category);

        $params = array();

        // We do not re-order categories not set to a forum.
        if ($category->getForum()) {
            $forumFilter = $category->getForum()->getId();

            $params['forum_filter'] = $forumFilter;

            $categories = $this->getCategoryModel()->findAllCategoriesForForumById($forumFilter);

            $this->getCategoryModel()->reorderCategories($categories, $category, $direction);

            $this->dispatch(ForumEvents::ADMIN_CATEGORY_REORDER_COMPLETE, new AdminCategoryEvent($this->getRequest(), $category));
        }

        $response = $this->redirectResponse($this->path('ccdn_forum_admin_category_list', $params));

        $this->dispatch(ForumEvents::ADMIN_CATEGORY_REORDER_RESPONSE, new AdminCategoryResponseEvent($this->getRequest(), $response, $category));

        return $response;
    }
}
