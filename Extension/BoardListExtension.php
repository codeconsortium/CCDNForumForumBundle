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

namespace CCDNForum\ForumBundle\Extension;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BoardListExtension extends \Twig_Extension
{

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     *
     * @access public
     * @return Array()
     */
    public function getFunctions()
    {
        return array(
            'board_list' => new \Twig_Function_Method($this, 'boardList'),
        );
    }

    /**
     * Gets all boards available with their categories.
     *
     * @access public
     * @param  object $user
     * @return int
     */
    public function boardList()
    {
        $boards = $this->container->get('ccdn_forum_forum.board.repository')->findAllBoardsGroupedByCategoryHydratedAsArray();

        return $boards;
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'boardList';
    }

}
