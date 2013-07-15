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

namespace CCDNForum\ForumBundle\Form\Handler\Admin;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Collections\ArrayCollection;

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
class CategoryDeleteFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\CategoryDeleteFormType $categoryDeleteFormType
     */
    protected $categoryDeleteFormType;

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
     * @access public
     * @param \Symfony\Component\Form\FormFactory                     $factory
     * @param \CCDNForum\ForumBundle\Form\Type\CategoryDeleteFormType $categoryDeleteFormType
     * @param \CCDNForum\ForumBundle\Model\Model\CategoryModel        $categoryModel
     */
    public function __construct(FormFactory $factory, $categoryDeleteFormType, $categoryModel)
    {
        $this->factory = $factory;
        $this->categoryDeleteFormType = $categoryDeleteFormType;
        $this->categoryModel = $categoryModel;
    }

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Category $category
	 * @return \CCDNForum\ForumBundle\Form\Handler\Admin\CategoryDeleteFormHandler
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
     * @return bool
     */
    public function process(Request $request)
    {
        $this->getForm();

        if ($request->getMethod() == 'POST') {
            $this->form->bind($request);

            // Validate
            if ($this->form->isValid()) {
                $formData = $this->form->getData();

                if ($this->getSubmitAction($request) == 'post') {
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
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function getSubmitAction(Request $request)
    {
        if ($request->request->has('submit')) {
            $action = key($request->request->get('submit'));
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
				throw new \Exception('Category object must be specified to delete.');
			}
			
            $this->form = $this->factory->create($this->categoryDeleteFormType, $this->category);
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
		$confirmA = $this->form->get('confirm_delete')->getData();
		$confirmB = $this->form->get('confirm_subordinates')->getData();
		$confirm = array_merge($confirmA, $confirmB);
		
		if (in_array('delete_category', $confirm)) {
			if (! in_array('delete_subordinates', $confirm)) {
				$boards = new ArrayCollection($category->getBoards()->toArray());
				
				$this->categoryModel->reassignBoardsToCategory($boards, null)->flush();
			}

	        $this->categoryModel->deleteCategory($category)->flush();
		}
		
		return $this->categoryModel;
    }
}
