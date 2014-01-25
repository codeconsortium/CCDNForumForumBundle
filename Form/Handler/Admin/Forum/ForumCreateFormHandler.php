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
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
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
class ForumCreateFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumCreateFormType $forumCreateFormType
     */
    protected $forumCreateFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     */
    protected $forumModel;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface      $dispatcher
     * @param \Symfony\Component\Form\FormFactory                              $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumCreateFormType $forumCreateFormType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ForumModel               $forumModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $forumCreateFormType, ModelInterface $forumModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->forumCreateFormType = $forumCreateFormType;
        $this->forumModel = $forumModel;
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            $forum = $this->forumModel->createForum();

            $this->dispatcher->dispatch(ForumEvents::ADMIN_FORUM_CREATE_INITIALISE, new AdminForumEvent($this->request, $forum));

            $this->form = $this->factory->create($this->forumCreateFormType, $forum);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected function onSuccess(Forum $forum)
    {
        $this->dispatcher->dispatch(ForumEvents::ADMIN_FORUM_CREATE_SUCCESS, new AdminForumEvent($this->request, $forum));

        $this->forumModel->saveForum($forum);

        $this->dispatcher->dispatch(ForumEvents::ADMIN_FORUM_CREATE_COMPLETE, new AdminForumEvent($this->request, $forum));
    }
}
