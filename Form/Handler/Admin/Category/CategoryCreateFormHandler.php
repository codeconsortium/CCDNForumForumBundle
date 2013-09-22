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

namespace CCDNForum\ForumBundle\Form\Handler\Admin\Category;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;

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
class CategoryCreateFormHandler
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Form\FormFactory $factory
     */
    protected $factory;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryCreateFormType $categoryCreateFormType
     */
    protected $categoryCreateFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\CategoryModel $categoryModel
     */
    protected $categoryModel;

    /**
     *
     * @access protected
     * @var \Symfony\Component\Form\Form $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $defaultForum
     */
    protected $defaultForum;

    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     */
    protected $dispatcher;

    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     *
     * @access public
     * @param \Symfony\Component\Form\FormFactory                                        $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryCreateFormType     $categoryCreateFormType
     * @param \CCDNForum\ForumBundle\Model\Model\CategoryModel                           $categoryModel
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     */
    public function __construct(FormFactory $factory, $categoryCreateFormType, $categoryModel, ContainerAwareTraceableEventDispatcher $dispatcher)
    {
        $this->factory = $factory;
        $this->categoryCreateFormType = $categoryCreateFormType;
        $this->categoryModel = $categoryModel;
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Forum                                          $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryCreateFormHandler
     */
    public function setDefaultForum(Forum $forum)
    {
        $this->defaultForum = $forum;

        return $this;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function process()
    {
        $this->getForm();

        if ($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            // Validate
            if ($this->form->isValid()) {
                $formData = $this->form->getData();

                if ($this->getSubmitAction() == 'post') {
                    $this->onSuccess($formData);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getSubmitAction()
    {
        if ($this->request->request->has('submit')) {
            $action = key($this->request->request->get('submit'));
        } else {
            $action = 'post';
        }

        return $action;
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            $category = new Category();

            $options = array(
                'default_forum' => $this->defaultForum
            );

            $this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_CREATE_INITIALISE, new AdminCategoryEvent($this->request, $category));

            $this->form = $this->factory->create($this->categoryCreateFormType, $category, $options);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Category           $category
     * @return \CCDNForum\ForumBundle\Model\Model\CategoryModel
     */
    protected function onSuccess(Category $category)
    {
        $this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_CREATE_SUCCESS, new AdminCategoryEvent($this->request, $category));

        return $this->categoryModel->saveNewCategory($category)->flush();
    }
}
