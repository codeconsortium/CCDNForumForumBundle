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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class BoardController extends BoardBaseController
{
    /**
     *
     * @access public
     * @param int $boardId, int $page
     * @return RedirectResponse|RenderResponse
     */
    public function showAction($boardId, $page)
    {	
		$board = $this->getBoardManager()->findOneByIdWithCategory($boardId);

		$stickyTopics = $this->getTopicManager()->findAllStickiedByBoardId($boardId);
		$topicsPager = $this->getTopicManager()->findAllPaginatedByBoardId($boardId, $page);

        // check board exists.
		$this->isFound($board);
		$this->isAuthorisedToViewBoard($board);
		
        // this is necessary for working out the last page for each topic.
        $postsPerPage = $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page');

        // setup bread crumbs.
        $category = $board->getCategory();

        $crumbs = $this->getCrumbs()
            ->add($this->trans('ccdn_forum_forum.crumbs.forum_index'), $this->path('ccdn_forum_forum_category_index'))
            ->add($category->getName(), $this->path('ccdn_forum_forum_category_show', array('categoryId' => $category->getId())))
            ->add($board->getName(), $this->path('ccdn_forum_forum_board_show', array('boardId' => $boardId)));

        return $this->renderResponse('CCDNForumForumBundle:Board:show.html.', array(
            'crumbs' => $crumbs,
            'board' => $board,
            'pager' => $topicsPager,
            'posts_per_page' => $postsPerPage,
            'sticky_topics' => $stickyTopics,
        ));
    }
}
