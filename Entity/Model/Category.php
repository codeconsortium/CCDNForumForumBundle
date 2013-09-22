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

namespace CCDNForum\ForumBundle\Entity\Model;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Forum as ConcreteForum;
use CCDNForum\ForumBundle\Entity\Board as ConcreteBoard;

abstract class Category
{
    /** @var Forum $forum */
    protected $forum;

    /** @var ArrayCollection $boards */
    protected $boards;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
        $this->boards = new ArrayCollection();
    }

    /**
     *
     * Get Forum
     *
     * @return Forum
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     *
     * Set Forum
     *
     * @param  Forum    $forum
     * @return Category
     */
    public function setForum(ConcreteForum $forum = null)
    {
        if ($this->forum) {
            if ($forum) {
                if ($this->forum->getId() != $forum->getId()) {
                    $this->setListOrderPriority(count($forum->getCategories()) + 1);
                }
            } else {
                $this->setListOrderPriority(0);
            }
        } else {
            if ($forum) {
                $this->setListOrderPriority(count($forum->getCategories()) + 1);
            } else {
                $this->setListOrderPriority(0);
            }
        }

        $this->forum = $forum;

        return $this;
    }

    /**
     * Get boards
     *
     * @return ArrayCollection
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     *
     * Set boards
     *
     * @param  ArrayCollection $boards
     * @return Category
     */
    public function setBoards(ArrayCollection $boards = null)
    {
        $this->boards = $boards;

        return $this;
    }

    /**
     *
     * Add board
     *
     * @param  Board    $board
     * @return Category
     */
    public function addBoard(ConcreteBoard $board)
    {
        $this->boards->add($board);

        return $this;
    }

    /**
     *
     * Remove Board
     *
     * @param  Board    $board
     * @return Category
     */
    public function removeBoard(ConcreteBoard $board)
    {
        $this->boards->removeElement($board);

        return $this;
    }
}
