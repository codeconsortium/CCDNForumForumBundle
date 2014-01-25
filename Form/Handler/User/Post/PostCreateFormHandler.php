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
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicFloodEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Component\FloodControl;

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
     * @var \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
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
     * @access private
     * @var \CCDNForum\ForumBundle\Component\FloodControl $floodControl
     */
    private $floodControl;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface   $dispatcher
     * @param \Symfony\Component\Form\FormFactory                           $factory
     * @param \CCDNForum\ForumBundle\Form\Type\User\Post\PostCreateFormType $formPostType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\PostModel             $postModel
     * @param \CCDNForum\ForumBundle\Component\FloodControl                 $floodControl
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $formPostType, ModelInterface $postModel, FloodControl $floodControl)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formPostType = $formPostType;
        $this->postModel = $postModel;
        $this->floodControl = $floodControl;
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
     * @access public
     * @return bool
     */
    public function process()
    {
        $this->getForm();

        if ($this->floodControl->isFlooded()) {
            $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_REPLY_FLOODED, new UserTopicFloodEvent($this->request));

            return false;
        }

        $this->floodControl->incrementCounter();

        if ($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            // Validate
            if ($this->form->isValid()) {
                if ($this->getSubmitAction() == 'post') {
                    $formData = $this->form->getData();

                    $this->onSuccess($formData);

                    return true;
                }
            }
        }

        return false;
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

            $post = $this->postModel->createPost();
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
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     */
    protected function onSuccess(Post $post)
    {
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($this->user);
        $post->setTopic($this->topic);
        $post->setDeleted(false);

        $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_REPLY_SUCCESS, new UserTopicEvent($this->request, $post->getTopic()));

        $this->postModel->savePost($post);

        $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_REPLY_COMPLETE, new UserTopicEvent($this->request, $this->topic, $this->didAuthorSubscribe()));
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
