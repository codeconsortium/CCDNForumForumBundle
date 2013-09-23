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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;

use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;

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
class PostCreateFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\User\Post\PostCreateFormType $formPostType
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
     * @var \CCDNForum\ForumBundle\Entity\Topic $topic
     */
    protected $topic;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Post $postToQuote
     */
    protected $postToQuote;

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher $dispatcher
     * @param \Symfony\Component\Form\FormFactory                                        $factory
     * @param \CCDNForum\ForumBundle\Form\Type\User\Post\PostCreateFormType              $formPostType
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
     * @param  \CCDNForum\ForumBundle\Entity\Topic                                 $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\User\Post\PostCreateFormHandler
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                                  $post
     * @return \CCDNForum\ForumBundle\Form\Handler\User\Post\PostCreateFormHandler
     */
    public function setPostToQuote(Post $post)
    {
        $this->postToQuote = $post;

        return $this;
    }

    /**
     *
     * @access protected
     * @return string
     */
    protected function getQuote()
    {
        $quote = "";

        if (is_object($this->postToQuote) && $this->postToQuote instanceof Post) {
            $author = $this->postToQuote->getCreatedBy();
            $body = $this->postToQuote->getBody();

            $quote = '[QUOTE="' . $author . '"]' . $body . '[/QUOTE]';
        }

        return $quote;
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            if (! is_object($this->topic) || ! ($this->topic instanceof Topic)) {
                throw new \Exception('Topic must be specified to create a Reply in PostCreateFormHandler');
            }

            $post = new Post();
            $post->setTopic($this->topic);
            $post->setBody($this->getQuote());

            $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_REPLY_INITIALISE, new UserTopicEvent($this->request, $post->getTopic()));

            $this->form = $this->factory->create($this->formPostType, $post);
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
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($this->user);
        $post->setTopic($this->topic);
        $post->setIsDeleted(false);

        $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_REPLY_SUCCESS, new UserTopicEvent($this->request, $post->getTopic()));

        return $this->postModel->postTopicReply($post);
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function didAuthorSubscribe()
    {
        return $this->form->get('subscribe')->getData();
    }
}
