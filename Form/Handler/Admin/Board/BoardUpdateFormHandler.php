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

namespace CCDNForum\ForumBundle\Form\Handler\Admin\Board;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\Board;

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
class BoardUpdateFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Board\BoardUpdateFormType $boardUpdateFormType
     */
    protected $boardUpdateFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $board
     */
    protected $board;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface      $dispatcher
     * @param \Symfony\Component\Form\FormFactory                              $factory
     * @param \CCDNForum\ForumBundle\Form\Type\Admin\Board\BoardUpdateFormType $boardUpdateFormType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\BoardModel               $boardModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $boardUpdateFormType, ModelInterface $boardModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->boardUpdateFormType = $boardUpdateFormType;
        $this->boardModel = $boardModel;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                                    $board
     * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardUpdateFormHandler
     */
    public function setBoard(Board $board)
    {
        $this->board = $board;

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
            if (!is_object($this->board) && !$this->board instanceof Board) {
                throw new \Exception('Board object must be specified to edit.');
            }

            $this->dispatcher->dispatch(ForumEvents::ADMIN_BOARD_EDIT_INITIALISE, new AdminBoardEvent($this->request, $this->board));

            $this->form = $this->factory->create($this->boardUpdateFormType, $this->board);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     */
    protected function onSuccess(Board $board)
    {
        $this->dispatcher->dispatch(ForumEvents::ADMIN_BOARD_EDIT_SUCCESS, new AdminBoardEvent($this->request, $board));

        $this->boardModel->updateBoard($board);

        $this->dispatcher->dispatch(ForumEvents::ADMIN_BOARD_EDIT_COMPLETE, new AdminBoardEvent($this->request, $board));
    }
}
