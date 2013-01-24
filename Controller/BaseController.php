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

namespace CCDNForum\ForumBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BaseController extends ContainerAware
{

    /**
     *
     * @access protected
     * @return string
     */
    protected function getEngine()
    {
        return $this->container->getParameter('ccdn_forum_forum.template.engine');
    }

    protected function filterViewableBoards($boards)
    {
        foreach ($boards as $boardKey => $board) {
            if (! $board->isAuthorisedToRead($this->container->get('security.context'))) {
                unset($boards[$boardKey]);
            }
        }

        return $boards;
    }

    protected function filterViewableCategories($categories)
    {
        foreach ($categories as $categoryKey => $category) {
            $boards = $category->getBoards();

            foreach($boards as $board) {
                if (! $board->isAuthorisedToRead($this->container->get('security.context'))) {
                    $categories[$categoryKey]->removeBoard($board);
                }
            }
        }

        return $categories;
    }
}