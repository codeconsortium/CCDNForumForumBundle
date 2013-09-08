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

namespace CCDNForum\ForumBundle\Form\Handler\User\Topic;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher;


use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;

//use CCDNForum\ForumBundle\Model\BaseModelInterface;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Board;
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
class TopicCreateFormHandler
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
     * @var \CCDNForum\ForumBundle\Form\Type\TopicType $formType
     */
    protected $formTopicType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\PostType $formType
     */
    protected $formPostType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\BaseModelInterface $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\BaseModelInterface $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\TopicType $form
     */
    protected $form;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $board
     */
    protected $board;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected $forum;

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
     * @param \CCDNForum\ForumBundle\Form\Type\TopicType          $formTopicType
     * @param \CCDNForum\ForumBundle\Form\Type\PostType           $formPostType
     * @param \CCDNForum\ForumBundle\Model\BaseModelInterface $topicModel
     * @param \CCDNForum\ForumBundle\Model\BaseModelInterface $boardModel
     */
    public function __construct(ContainerAwareTraceableEventDispatcher $dispatcher, FormFactory $factory, $formTopicType, $formPostType, $topicModel, $boardModel)
    {
		$this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formTopicType = $formTopicType;
        $this->formPostType = $formPostType;
        $this->topicModel = $topicModel;
        $this->boardModel = $boardModel;
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
     * @param  \CCDNForum\ForumBundle\Entity\Forum                        $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
     */
    public function setForum(Forum $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                        $board
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
     */
    public function setBoard(Board $board)
    {
        $this->board = $board;

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
            if (! is_object($this->board) || ! ($this->board instanceof Board)) {
                throw new \Exception('Board must be specified to be create a Topic in TopicCreateFormHandler');
            }

            $filteredBoards = $this->boardModel->findAllBoardsForForumById($this->forum->getId());
            $topicOptions = array(
                'boards' => $filteredBoards,
            );

            $topic = new Topic();
            $topic->setBoard($this->board);

            $post = new Post();
            $post->setTopic($topic);
            $post->setCreatedBy($this->user);

			$this->dispatcher->dispatch(ForumEvents::USER_TOPIC_CREATE_INITIALISE, new UserTopicEvent($this->request, $post->getTopic()));

            $this->form = $this->factory->create($this->formPostType, $post);
            $this->form->add($this->factory->create($this->formTopicType, $topic, $topicOptions));
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Post      $post
     * @return \CCDNForum\ForumBundle\Model\TopicModel
     */
    protected function onSuccess(Post $post)
    {
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($this->user);
        $post->setIsLocked(false);
        $post->setIsDeleted(false);

        $post->getTopic()->setCachedViewCount(0);
        $post->getTopic()->setCachedReplyCount(0);
        $post->getTopic()->setIsClosed(false);
        $post->getTopic()->setIsDeleted(false);
        $post->getTopic()->setIsSticky(false);

		$this->dispatcher->dispatch(ForumEvents::USER_TOPIC_CREATE_SUCCESS, new UserTopicEvent($this->request, $post->getTopic()));

        return $this->topicModel->saveNewTopic($post)->flush();
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
