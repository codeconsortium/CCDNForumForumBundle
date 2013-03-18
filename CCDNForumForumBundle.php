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
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CCDNForumForumBundle extends Bundle
{
	public function boot()
	{
		$twig = $this->container->get('twig');	
		$twig->addGlobal('ccdn_forum_forum', array(
			'seo' => array(
				'title_length' => $this->container->getParameter('ccdn_forum_forum.seo.title_length'),
			),
			'category' => array(
				'enable_bb_parser' => $this->container->getParameter('ccdn_forum_forum.category.enable_bb_parser'),
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
			'item_board' => array(
				'enable_bb_parser' => $this->container->getParameter('ccdn_forum_forum.item_board.enable_bb_parser'),
				
			),
			'topic' => array(
				'show' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.show.layout_template'),
					'topic_closed_datetime_format' => $this->container->getParameter('ccdn_forum_forum.topic.show.topic_closed_datetime_format'),
					'topic_deleted_datetime_format' => $this->container->getParameter('ccdn_forum_forum.topic.show.topic_deleted_datetime_format'),
					'posts_per_page' => $this->container->getParameter('ccdn_forum_forum.topic.show.posts_per_page'),
				),
				'create' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.create.layout_template'),
					'form_theme' => $this->container->getParameter('ccdn_forum_forum.topic.create.form_theme'),
					'enable_bb_editor' => $this->container->getParameter('ccdn_forum_forum.topic.create.enable_bb_editor'),
				),
				'reply' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.topic.reply.layout_template'),
					'form_theme' => $this->container->getParameter('ccdn_forum_forum.topic.reply.form_theme'),
					'enable_bb_editor' => $this->container->getParameter('ccdn_forum_forum.topic.reply.enable_bb_editor'),
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
					'enable_bb_editor' => $this->container->getParameter('ccdn_forum_forum.post.edit_post.enable_bb_editor'),
				),
				'edit_topic' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.post.edit_topic.layout_template'),
					'form_theme' => $this->container->getParameter('ccdn_forum_forum.post.edit_topic.form_theme'),
					'enable_bb_editor' => $this->container->getParameter('ccdn_forum_forum.post.edit_topic.enable_bb_editor'),
				),
				'delete_post' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.post.delete_post.layout_template'),
				),
			),
			'item_post' => array(
				'post_created_datetime_format' => $this->container->getParameter('ccdn_forum_forum.item_post.post_created_datetime_format'),
				'post_edited_datetime_format' => $this->container->getParameter('ccdn_forum_forum.item_post.post_edited_datetime_format'),
				'post_deleted_datetime_format' => $this->container->getParameter('ccdn_forum_forum.item_post.post_deleted_datetime_format'),
				'enable_bb_parser' => $this->container->getParameter('ccdn_forum_forum.item_post.enable_bb_parser'),
			),
			'item_signature' => array(
				'enable_bb_parser' => $this->container->getParameter('ccdn_forum_forum.item_signature.enable_bb_parser'),
			),
			'transcript' => array(
				'post_creation_datetime_format' => $this->container->getParameter('ccdn_forum_forum.transcript.post_creation_datetime_format'),
				'post_deleted_datetime_format' => $this->container->getParameter('ccdn_forum_forum.transcript.post_deleted_datetime_format'),
			),
			'draft' => array(
				'list' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.draft.list.layout_template'),
					'topic_title_truncate' => $this->container->getParameter('ccdn_forum_forum.draft.list.topic_title_truncate'),
					'creation_datetime_format' => $this->container->getParameter('ccdn_forum_forum.draft.list.creation_datetime_format'),
					'drafts_per_page' => $this->container->getParameter('ccdn_forum_forum.draft.list.drafts_per_page'),
				),
			),
			'subscription' => array(
				'list' => array(
					'layout_template' => $this->container->getParameter('ccdn_forum_forum.subscription.list.layout_template'),
					'topics_per_page' => $this->container->getParameter('ccdn_forum_forum.subscription.list.topics_per_page'),
				),
			),
		));
	}
}