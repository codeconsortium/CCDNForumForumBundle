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

namespace CCDNForum\ForumBundle\Form\Handler\User\Post;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent;

//use CCDNForum\ForumBundle\Model\BaseModelInterface;
use CCDNForum\ForumBundle\Entity\Post;

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
class PostDeleteFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\PostType $formPostType
     */
    protected $formPostType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\BaseModelInterface $postModel
     */
    protected $postModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\PostType $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Post $post
     */
    protected $post;

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
	 * @access protected
	 * @var \Symfony\Component\Security\Core\User\UserInterface
	 */
	protected $user;

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     * @param \Symfony\Component\Form\FormFactory                 $factory
     * @param \CCDNForum\ForumBundle\Form\Type\PostType           $formPostType
     * @param \CCDNForum\ForumBundle\Model\BaseModelInterface     $postModel
     */
    public function __construct(ContainerAwareTraceableEventDispatcher $dispatcher, FormFactory $factory, $formPostType, $postModel)
    {
		$this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formPostType = $formPostType;
        $this->postModel = $postModel;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\Security\Core\User\UserInterface       $user
     * @return \CCDNForum\ForumBundle\Form\Handler\PostUpdateFormHandler
     */
	public function setUser(UserInterface $user)
	{
		$this->user = $user;
		
		return $this;
	}

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                        $post
     * @return \CCDNForum\ForumBundle\Form\Handler\PostUpdateFormHandler
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

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
     * @return Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            if (! is_object($this->post) || ! ($this->post instanceof Post)) {
                throw new \Exception('Post must be specified to delete in PostDeleteFormHandler');
            }

			$this->dispatcher->dispatch(ForumEvents::USER_POST_SOFT_DELETE_INITIALISE, new UserPostEvent($this->request, $this->post));

            $this->form = $this->factory->create($this->formPostType, $this->post);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Post     $post
     * @return \CCDNForum\ForumBundle\Model\PostModel
     */
    protected function onSuccess(Post $post)
    {
//        $post->setDeletedDate(new \DateTime());
//        $post->setDeletedBy($this->user);
//		$post->setIsDeleted(true);

        $this->postModel->softDelete($post, $this->user);

		$this->dispatcher->dispatch(ForumEvents::USER_POST_SOFT_DELETE_SUCCESS, new UserPostEvent($this->request, $this->post));
		
        return $this->postModel;
    }
}
