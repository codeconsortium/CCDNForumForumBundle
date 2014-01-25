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

namespace CCDNForum\ForumBundle\Form\Handler\Moderator\Post;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
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
class PostUnlockFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Moderator\Post\PostUnlockFormType $formPostType
     */
    protected $formPostType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
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
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface        $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Moderator\Post\PostUnlockFormType $formPostType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\PostModel                  $postModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $formPostType, ModelInterface $postModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formPostType = $formPostType;
        $this->postModel = $postModel;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                                       $post
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\Post\PostUnlockFormHandler
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
                throw new \Exception('Post must be specified to unlock in PostUnlockFormHandler');
            }

            $this->dispatcher->dispatch(ForumEvents::MODERATOR_POST_UNLOCK_INITIALISE, new ModeratorPostEvent($this->request, $this->post));

            $this->form = $this->factory->create($this->formPostType, $this->post);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     */
    protected function onSuccess(Post $post)
    {
        $post->setUnlockedDate(new \Datetime('now'));
        $post->setUnlockedBy($this->user);

        $this->dispatcher->dispatch(ForumEvents::MODERATOR_POST_UNLOCK_SUCCESS, new ModeratorPostEvent($this->request, $this->post));

        $this->postModel->updatePost($post);

        $this->dispatcher->dispatch(ForumEvents::MODERATOR_POST_UNLOCK_COMPLETE, new ModeratorPostEvent($this->request, $post));
    }
}
