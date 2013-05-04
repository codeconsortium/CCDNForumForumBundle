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

namespace CCDNForum\ForumBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;

use CCDNForum\ForumBundle\Entity\Topic;
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
class TopicUpdateFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\TopicType $formTopicType
     */
    protected $formTopicType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\PostType $formPostType
     */
    protected $formPostType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $topicManager
     */
    protected $topicManager;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $postManager
     */
    protected $postManager;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\TopicType $form
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
     * @access public
     * @param \Symfony\Component\Form\FormFactory                 $factory
     * @param \CCDNForum\ForumBundle\Form\Type\TopicType          $formTopicType
     * @param \CCDNForum\ForumBundle\Form\Type\PostType           $formPostType
     * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface $topicManager
     * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface $postManager
     */
    public function __construct(FormFactory $factory, $formTopicType, $formPostType, BaseManagerInterface $topicManager, BaseManagerInterface $postManager)
    {
        $this->factory = $factory;
        $this->formTopicType = $formTopicType;
        $this->formPostType = $formPostType;
        $this->topicManager = $topicManager;
        $this->postManager = $postManager;
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
     * @return Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            if (! is_object($this->post) || ! ($this->post instanceof Post)) {
                throw new \Exception('Post must be specified to be update a Topic in TopicUpdateFormHandler');
            }

            $topic = $this->post->getTopic();

            $this->form = $this->factory->create($this->formPostType, $this->post);
            $this->form->add($this->factory->create($this->formTopicType, $topic));
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Post          $post
     * @return \CCDNForum\ForumBundle\Manager\TopicManager
     */
    protected function onSuccess(Post $post)
    {
        // get the current time, and compare to when the post was made.
        $now = new \DateTime();
        $interval = $now->diff($post->getCreatedDate());

        // if post is less than 15 minutes old, don't add that it was edited.
        if ($interval->format('%i') > 15) {
            $post->setEditedDate(new \DateTime());
            $post->setEditedBy($this->postManager->getUser());
        }

        return $this->postManager->updatePost($post)->flush();
    }
}
