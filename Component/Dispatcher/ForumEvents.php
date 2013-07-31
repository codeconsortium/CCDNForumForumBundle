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

namespace CCDNForum\ForumBundle\Component\Dispatcher;

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
final class ForumEvents
{
	const ADMIN_FORUM_CREATE_INITIALISE = 'ccdn_forum.admin.forum.create.initialise';
	const ADMIN_FORUM_CREATE_SUCCESS    = 'ccdn_forum.admin.forum.create.success';
	const ADMIN_FORUM_CREATE_COMPLETE   = 'ccdn_forum.admin.forum.create.complete';
	const ADMIN_FORUM_CREATE_RESPONSE   = 'ccdn_forum.admin.forum.create.response';

	const ADMIN_FORUM_EDIT_INITIALISE   = 'ccdn_forum.admin.forum.edit.initialise';
	const ADMIN_FORUM_EDIT_SUCCESS      = 'ccdn_forum.admin.forum.edit.success';
	const ADMIN_FORUM_EDIT_COMPLETE     = 'ccdn_forum.admin.forum.edit.complete';
	const ADMIN_FORUM_EDIT_RESPONSE     = 'ccdn_forum.admin.forum.edit.response';

	const ADMIN_FORUM_DELETE_INITIALISE   = 'ccdn_forum.admin.forum.delete.initialise';
	const ADMIN_FORUM_DELETE_SUCCESS      = 'ccdn_forum.admin.forum.delete.success';
	const ADMIN_FORUM_DELETE_COMPLETE     = 'ccdn_forum.admin.forum.delete.complete';
	const ADMIN_FORUM_DELETE_RESPONSE     = 'ccdn_forum.admin.forum.delete.response';

	const ADMIN_CATEGORY_CREATE_INITIALISE = 'ccdn_forum.admin.category.create.initialise';
	const ADMIN_CATEGORY_CREATE_SUCCESS    = 'ccdn_forum.admin.category.create.success';
	const ADMIN_CATEGORY_CREATE_COMPLETE   = 'ccdn_forum.admin.category.create.complete';
	const ADMIN_CATEGORY_CREATE_RESPONSE   = 'ccdn_forum.admin.category.create.response';

	const ADMIN_CATEGORY_EDIT_INITIALISE   = 'ccdn_forum.admin.category.edit.initialise';
	const ADMIN_CATEGORY_EDIT_SUCCESS      = 'ccdn_forum.admin.category.edit.success';
	const ADMIN_CATEGORY_EDIT_COMPLETE     = 'ccdn_forum.admin.category.edit.complete';
	const ADMIN_CATEGORY_EDIT_RESPONSE     = 'ccdn_forum.admin.category.edit.response';

	const ADMIN_CATEGORY_DELETE_INITIALISE   = 'ccdn_forum.admin.category.delete.initialise';
	const ADMIN_CATEGORY_DELETE_SUCCESS      = 'ccdn_forum.admin.category.delete.success';
	const ADMIN_CATEGORY_DELETE_COMPLETE     = 'ccdn_forum.admin.category.delete.complete';
	const ADMIN_CATEGORY_DELETE_RESPONSE     = 'ccdn_forum.admin.category.delete.response';

	const ADMIN_CATEGORY_REORDER_INITIALISE   = 'ccdn_forum.admin.category.reorder.initialise';
	const ADMIN_CATEGORY_REORDER_SUCCESS      = 'ccdn_forum.admin.category.reorder.success';
	const ADMIN_CATEGORY_REORDER_COMPLETE     = 'ccdn_forum.admin.category.reorder.complete';
	const ADMIN_CATEGORY_REORDER_RESPONSE     = 'ccdn_forum.admin.category.reorder.response';

	const ADMIN_BOARD_CREATE_INITIALISE = 'ccdn_forum.admin.board.create.initialise';
	const ADMIN_BOARD_CREATE_SUCCESS    = 'ccdn_forum.admin.board.create.success';
	const ADMIN_BOARD_CREATE_COMPLETE   = 'ccdn_forum.admin.board.create.complete';
	const ADMIN_BOARD_CREATE_RESPONSE   = 'ccdn_forum.admin.board.create.response';

	const ADMIN_BOARD_EDIT_INITIALISE   = 'ccdn_forum.admin.board.edit.initialise';
	const ADMIN_BOARD_EDIT_SUCCESS      = 'ccdn_forum.admin.board.edit.success';
	const ADMIN_BOARD_EDIT_COMPLETE     = 'ccdn_forum.admin.board.edit.complete';
	const ADMIN_BOARD_EDIT_RESPONSE     = 'ccdn_forum.admin.board.edit.response';

	const ADMIN_BOARD_DELETE_INITIALISE   = 'ccdn_forum.admin.board.delete.initialise';
	const ADMIN_BOARD_DELETE_SUCCESS      = 'ccdn_forum.admin.board.delete.success';
	const ADMIN_BOARD_DELETE_COMPLETE     = 'ccdn_forum.admin.board.delete.complete';
	const ADMIN_BOARD_DELETE_RESPONSE     = 'ccdn_forum.admin.board.delete.response';

	const ADMIN_BOARD_REORDER_INITIALISE   = 'ccdn_forum.admin.board.reorder.initialise';
	const ADMIN_BOARD_REORDER_SUCCESS      = 'ccdn_forum.admin.board.reorder.success';
	const ADMIN_BOARD_REORDER_COMPLETE     = 'ccdn_forum.admin.board.reorder.complete';
	const ADMIN_BOARD_REORDER_RESPONSE     = 'ccdn_forum.admin.board.reorder.response';
}