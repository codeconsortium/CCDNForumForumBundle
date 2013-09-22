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

namespace CCDNForum\ForumBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

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
class CCDNForumForumBundle extends Bundle
{
    public function boot()
    {
        $twig = $this->container->get('twig');

        $twig->addGlobal(
            'ccdn_forum_forum',
            array(
                'seo' => array(
                    'title_length' => $this->container->getParameter('ccdn_forum_forum.seo.title_length'),
                ),
                'category' => array(
                    'last_post_datetime_format' => $this->container->getParameter('ccdn_forum_forum.category.last_post_datetime_format'),
                    'index' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.category.index.layout_template'),
                    ),
                    'show' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.category.show.layout_template'),
                    ),
                ),
                'board' => array(
                    'show' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.board.show.layout_template'),
                        'topic_title_truncate' => $this->container->getParameter('ccdn_forum_forum.board.show.topic_title_truncate'),
                        'first_post_datetime_format' => $this->container->getParameter('ccdn_forum_forum.board.show.first_post_datetime_format'),
                        'last_post_datetime_format' => $this->container->getParameter('ccdn_forum_forum.board.show.last_post_datetime_format'),
                        'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.board.show.topics_per_page'),
                    ),
                ),
                'topic' => array(
                    'show' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.show.layout_template'),
                        'closed_datetime_format' => $this->container->getParameter('ccdn_forum_forum.topic.show.closed_datetime_format'),
                        'deleted_datetime_format' => $this->container->getParameter('ccdn_forum_forum.topic.show.deleted_datetime_format'),
                        'posts_per_page' => $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page'),
                    ),
                    'create' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.create.layout_template'),
                        'form_theme' => $this->container->getParameter('ccdn_forum_forum.topic.create.form_theme'),
                    ),
                    'reply' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.reply.layout_template'),
                        'form_theme' => $this->container->getParameter('ccdn_forum_forum.topic.reply.form_theme'),
                    ),
                    'change_board' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.change_board.layout_template'),
                        'form_theme' => $this->container->getParameter('ccdn_forum_forum.topic.change_board.form_theme'),
                    ),
                ),
                'post' => array(
                    'show' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.post.show.layout_template'),
                    ),
                    'edit_post' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.post.edit_post.layout_template'),
                        'form_theme' => $this->container->getParameter('ccdn_forum_forum.post.edit_post.form_theme'),
                    ),
                    'edit_topic' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.post.edit_topic.layout_template'),
                        'form_theme' => $this->container->getParameter('ccdn_forum_forum.post.edit_topic.form_theme'),
                    ),
                    'delete_post' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.post.delete_post.layout_template'),
                    ),
                ),
                'item_post' => array(
                    'created_datetime_format' => $this->container->getParameter('ccdn_forum_forum.item_post.created_datetime_format'),
                    'edited_datetime_format' => $this->container->getParameter('ccdn_forum_forum.item_post.edited_datetime_format'),
                    'deleted_datetime_format' => $this->container->getParameter('ccdn_forum_forum.item_post.deleted_datetime_format'),
                ),
                'subscription' => array(
                    'list' => array(
                        'layout_template' => $this->container->getParameter('ccdn_forum_forum.subscription.list.layout_template'),
                        'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.subscription.list.topics_per_page'),
                    ),
                ),
            )
        ); // End Twig Globals.
    }
}
