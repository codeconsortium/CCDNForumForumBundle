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
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PostCreateFormHandler
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
	 * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $manager
	 */
    protected $manager;

    /**
	 * 
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Form\Type\PostType $form 
	 */
    protected $form;

    /**
	 * 
	 * @access protected
	 * @var \CCDNForum\ForumBundle\Entity\Topic $topic 
	 */
	protected $topic;
	
    /**
     *
     * @access public
     * @param \Symfony\Component\Form\FormFactory $factory
	 * @param \CCDNForum\ForumBundle\Form\Type\PostType $formPostType
	 * @param \CCDNForum\ForumBundle\Manager\BaseManagerInterface $manager
     */
    public function __construct(FormFactory $factory, $formPostType, BaseManagerInterface $manager)
    {
        $this->factory = $factory;
		$this->formPostType = $formPostType;
        $this->manager = $manager;
    }

    /**
     *
     * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return \CCDNForum\ForumBundle\Form\Handler\PostCreateFormHandler
     */
	public function setTopic(Topic $topic)
	{
		$this->topic = $topic;
		
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

				if ($this->getAction($request) == 'post') {
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
	public function getAction(Request $request)
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
        if (! $this->form) {
            if (! is_object($this->topic) || ! ($this->topic instanceof Topic)) {
                throw new \Exception('Topic must be specified to create a Reply in PostCreateFormHandler');
            }

            $post = new Post();
            $post->setTopic($this->topic);

            $this->form = $this->factory->create($this->formPostType, $post);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Manager\PostManager
     */
    protected function onSuccess(Post $post)
    {
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($this->manager->getUser());
        $post->setTopic($this->topic);
        $post->setIsLocked(false);
        $post->setIsDeleted(false);
		
        return $this->manager->postTopicReply($post)->flush();
    }
}