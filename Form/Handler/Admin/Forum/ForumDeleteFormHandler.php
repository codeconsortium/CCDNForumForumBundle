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

namespace CCDNForum\ForumBundle\Form\Handler\Admin\Forum;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Forum;

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
class ForumDeleteFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumDeleteFormType $forumDeleteFormType
     */
    protected $forumDeleteFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\ForumModel $forumModel
     */
    protected $forumModel;

    /**
     *
     * @access protected
     * @var \Symfony\Component\Form\Form $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected $forum;
	
    /**
     *
     * @access public
     * @param \Symfony\Component\Form\FormFactory                              $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumDeleteFormType $forumDeleteFormType
     * @param \CCDNForum\ForumBundle\Model\Model\ForumModel                    $forumModel
     */
    public function __construct(FormFactory $factory, $forumDeleteFormType, $forumModel)
    {
        $this->factory = $factory;
        $this->forumDeleteFormType = $forumDeleteFormType;
        $this->forumModel = $forumModel;
    }

	/**
	 * 
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Forum $forum
	 * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumDeleteFormHandler
	 */
	public function setForum(Forum $forum)
	{
		$this->forum = $forum;
		
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
			if (!is_object($this->forum) && !$this->forum instanceof Forum) {
				throw new \Exception('Forum object must be specified to delete.');
			}
			
            $this->form = $this->factory->create($this->forumDeleteFormType, $this->forum);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Forum           $forum
     * @return \CCDNForum\ForumBundle\Model\Model\ForumModel
     */
    protected function onSuccess(Forum $forum)
    {
		$confirmA = $this->form->get('confirm_delete')->getData();
		$confirmB = $this->form->get('confirm_subordinates')->getData();
		$confirm = array_merge($confirmA, $confirmB);
		
		if (in_array('delete_forum', $confirm)) {
			if (! in_array('delete_subordinates', $confirm)) {
				$categories = new ArrayCollection($forum->getCategories()->toArray());
				
				$this->forumModel->reassignCategoriesToForum($categories, null)->flush();
			}

	        $this->forumModel->deleteForum($forum)->flush();
		}
		
		return $this->forumModel;
    }
}
