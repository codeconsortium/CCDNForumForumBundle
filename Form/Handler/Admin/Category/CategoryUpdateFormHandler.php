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
class CategoryUpdateFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryUpdateFormType $categoryUpdateFormType
     */
    protected $categoryUpdateFormType;

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
     * @var \CCDNForum\ForumBundle\Entity\Category $category
     */
    protected $category;

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
     * @param \CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryUpdateFormType     $categoryUpdateFormType
     * @param \CCDNForum\ForumBundle\Model\Model\CategoryModel                           $categoryModel
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     */
    public function __construct(FormFactory $factory, $categoryUpdateFormType, $categoryModel, ContainerAwareTraceableEventDispatcher $dispatcher)
    {
        $this->factory = $factory;
        $this->categoryUpdateFormType = $categoryUpdateFormType;
        $this->categoryModel = $categoryModel;
		$this->dispatcher = $dispatcher;
    }

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Category $category
	 * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryUpdateFormHandler
	 */
	public function setCategory(Category $category)
	{
		$this->category = $category;
		
		return $this;
	}

    /**
     *
     * @access public
     * @param  \Symfony\Component\HttpFoundation\Request $request
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
			if (!is_object($this->category) && !$this->category instanceof Category) {
				throw new \Exception('Category object must be specified to edit.');
			}
			
			$this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_EDIT_INITIALISE, new AdminCategoryEvent($this->request, $this->category));
			
            $this->form = $this->factory->create($this->categoryUpdateFormType, $this->category);
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
		$this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_EDIT_SUCCESS, new AdminCategoryEvent($this->request, $category));
		
        return $this->categoryModel->updateCategory($category)->flush();
    }
}
