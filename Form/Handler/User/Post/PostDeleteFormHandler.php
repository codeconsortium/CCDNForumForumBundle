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
use Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent;

use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;

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
class PostDeleteFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\User\Post\PostDeleteFormType $formPostType
     */
    protected $formPostType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\PostModel $postModel
     */
    protected $postModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Post $post
     */
    protected $post;

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                        $factory
     * @param \CCDNForum\ForumBundle\Form\Type\User\Post\PostDeleteFormType              $formPostType
     * @param \CCDNForum\ForumBundle\Model\Model\PostModel                               $postModel
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
     * @param  \CCDNForum\ForumBundle\Entity\Post                                  $post
     * @return \CCDNForum\ForumBundle\Form\Handler\User\Post\PostDeleteFormHandler
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Form\Form
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
     * @param  \CCDNForum\ForumBundle\Entity\Post           $post
     * @return \CCDNForum\ForumBundle\Model\Model\PostModel
     */
    protected function onSuccess(Post $post)
    {
        $this->dispatcher->dispatch(ForumEvents::USER_POST_SOFT_DELETE_SUCCESS, new UserPostEvent($this->request, $this->post));

        return $this->postModel->softDelete($post, $this->user);
    }
}
