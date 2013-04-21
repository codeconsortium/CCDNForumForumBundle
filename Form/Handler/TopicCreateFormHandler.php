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
use Symfony\Component\DependencyInjection\ContainerInterface;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
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
	 * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $topicManager
	 */
    protected $topicManager;

    /**
	 *
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $boardManager
	 */
    protected $boardManager;

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
     * @access public
     * @param \Symfony\Component\Form\FormFactory $factory
	 * @param \CCDNForum\ForumBundle\Form\Type\TopicType $formTopicType
	 * @param \CCDNForum\ForumBundle\Form\Type\PostType $formPostType
	 * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface $topicManager
	 * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface $boardManager
     */
    public function __construct(FormFactory $factory, $formTopicType, $formPostType, BaseManagerInterface $topicManager, BaseManagerInterface $boardManager)
    {
        $this->factory = $factory;
		$this->formTopicType = $formTopicType;
		$this->formPostType = $formPostType;
        $this->topicManager = $topicManager;
        $this->boardManager = $boardManager;
    }

    /**
     *
     * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
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
	 * @param \Symfony\Component\HttpFoundation\Request $request
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
	 * @param \Symfony\Component\HttpFoundation\Request $request
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
            if (! is_object($this->board) || ! ($this->board instanceof Board)) {
                throw new \Exception('Board must be specified to be create a Topic in TopicCreateFormHandler');
            }

			$filteredBoards = $this->boardManager->findAllForFormDropDown();
			$topicOptions = array(
				'boards' => $filteredBoards,
			);
			
            $topic = new Topic();
            $topic->setBoard($this->board);

            $post = new Post();
            $post->setTopic($topic);
            $post->setCreatedBy($this->topicManager->getUser());

            $this->form = $this->factory->create($this->formPostType, $post);
            $this->form->add($this->factory->create($this->formTopicType, $topic, $topicOptions));
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\TopicManager
     */
    protected function onSuccess(Post $post)
    {
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($this->topicManager->getUser());
        $post->setIsLocked(false);
        $post->setIsDeleted(false);

        $post->getTopic()->setCachedViewCount(0);
        $post->getTopic()->setCachedReplyCount(0);
        $post->getTopic()->setIsClosed(false);
        $post->getTopic()->setIsDeleted(false);
        $post->getTopic()->setIsSticky(false);
		
        return $this->topicManager->postNewTopic($post)->flush();
    }
}