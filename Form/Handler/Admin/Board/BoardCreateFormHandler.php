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

namespace CCDNForum\ForumBundle\Form\Handler\Admin\Board;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;

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
class BoardCreateFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Board\BoardCreateFormType $boardCreateFormType
     */
    protected $boardCreateFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\BoardModel $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \Symfony\Component\Form\Form $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Category $defaultCategory
     */
    protected $defaultCategory;

    /**
     *
     * @access public
     * @param \Symfony\Component\Form\FormFactory                        $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Board\BoardCreateFormType $boardCreateFormType
     * @param \CCDNForum\ForumBundle\Model\Model\BoardModel              $boardModel
     */
    public function __construct(FormFactory $factory, $boardCreateFormType, $boardModel)
    {
        $this->factory = $factory;
        $this->boardCreateFormType = $boardCreateFormType;
        $this->boardModel = $boardModel;
    }

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Forum $forum
	 * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryCreateFormHandler
	 */
	public function setDefaultCategory(Category $category)
	{
		$this->defaultCategory = $category;
		
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
			$options = array(
				'default_category' => $this->defaultCategory
			);
			
            $this->form = $this->factory->create($this->boardCreateFormType, null, $options);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Board           $board
     * @return \CCDNForum\ForumBundle\Model\Model\BoardModel
     */
    protected function onSuccess(Board $board)
    {
        return $this->boardModel->saveNewBoard($board)->flush();
    }
}
