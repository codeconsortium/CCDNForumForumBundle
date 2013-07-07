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
class AdminPanelController extends BaseController
{
    /**
     *
     * @access public
     * @return RenderResponse
     */
    public function indexAction()
    {
        $this->isAuthorised('ROLE_ADMIN');

        //$crumbs = $this->getCrumbs()
        //    ->add($this->trans('crumbs.category.index'), $this->path('ccdn_forum_user_category_index'))
        //    ->add($category->getName(), $this->path('ccdn_forum_user_category_show', array('categoryId' => $category->getId())))
        //    ->add($board->getName(), $this->path('ccdn_forum_user_board_show', array('boardId' => $board->getId())))
        //    ->add($topic->getTitle(), $this->path('ccdn_forum_user_topic_show', array('topicId' => $topic->getId())))
        //    ->add('#' . $post->getId(), $this->path('ccdn_forum_user_post_show', array('postId' => $post->getId())));

        return $this->renderResponse('CCDNForumForumBundle:Admin:/Panel/index.html.', array(

        ));
    }
}