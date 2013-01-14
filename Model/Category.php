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

abstract class Category
{
    /** @var ArrayCollection $boards */
    protected $boards;

    public function __construct()
    {
        $this->boards = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add boards
     *
     * @param CCDNForum\ForumBundle\Entity\Board $boards
     */
    public function addBoards(\CCDNForum\ForumBundle\Entity\Board $boards)
    {
        $this->boards[] = $boards;
    }

    /**
     * Get boards
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add boards
     *
     * @param CCDNForum\ForumBundle\Entity\Board $boards
     */
    public function addBoard(\CCDNForum\ForumBundle\Entity\Board $boards)
    {
        $this->boards[] = $boards;
    }
}
