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

namespace CCDNForum\ForumBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Board;

abstract class Category
{
    /** @var ArrayCollection $boards */
    protected $boards;

    public function __construct()
    {
        $this->boards = new ArrayCollection();
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
     * Set boards
     *
     * @param ArrayCollection $boards
     */
    public function setBoards(ArrayCollection $boards)
    {
        $this->boards = $boards;
    }

    /**
     * Add boards
     *
     * @param ArrayCollection $boards
     */
    public function addBoards(ArrayCollection $boards)
    {
        foreach ($boards as $board) {
            $this->boards->add($board);
        }
    }

    /**
     * Add board
     *
     * @param Board $board
     */
    public function addBoard(Board $board)
    {
        $this->boards[] = $board;
    }
}
