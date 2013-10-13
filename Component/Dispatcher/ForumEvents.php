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
    /**
     *
     * The ADMIN_FORUM_CREATE_INITIALISE event occurs when the forum creation process is initalised.
     *
     * This event allows you to modify the default values of the forum entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_CREATE_INITIALISE = 'ccdn_forum.admin.forum.create.initialise';

    /**
     *
     * The ADMIN_FORUM_CREATE_SUCCESS event occurs when the forum creation process is successful before persisting.
     *
     * This event allows you to modify the values of the forum entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_CREATE_SUCCESS = 'ccdn_forum.admin.forum.create.success';

    /**
     *
     * The ADMIN_FORUM_CREATE_COMPLETE event occurs when the forum creation process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the forum entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_CREATE_COMPLETE = 'ccdn_forum.admin.forum.create.complete';

    /**
     *
     * The ADMIN_FORUM_CREATE_RESPONSE event occurs when the forum creation process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumResponseEvent instance.
     */
    const ADMIN_FORUM_CREATE_RESPONSE = 'ccdn_forum.admin.forum.create.response';

    /**
     *
     * The ADMIN_FORUM_EDIT_INITIALISE event occurs when the forum editing process is initalised.
     *
     * This event allows you to modify the default values of the forum entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_EDIT_INITIALISE = 'ccdn_forum.admin.forum.edit.initialise';

    /**
     *
     * The ADMIN_FORUM_EDIT_SUCCESS event occurs when the forum editing process is successful before persisting.
     *
     * This event allows you to modify the values of the forum entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_EDIT_SUCCESS = 'ccdn_forum.admin.forum.edit.success';

    /**
     *
     * The ADMIN_FORUM_EDIT_COMPLETE event occurs when the forum editing process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the forum entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_EDIT_COMPLETE = 'ccdn_forum.admin.forum.edit.complete';

    /**
     *
     * The ADMIN_FORUM_EDIT_RESPONS event occurs when the forum editing process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumResponseEvent instance.
     */
    const ADMIN_FORUM_EDIT_RESPONSE = 'ccdn_forum.admin.forum.edit.response';

    /**
     *
     * The ADMIN_FORUM_DELETE_INITIALISE event occurs when the forum deleting process is initalised.
     *
     * This event allows you to modify the default values of the forum entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_DELETE_INITIALISE = 'ccdn_forum.admin.forum.delete.initialise';

    /**
     *
     * The ADMIN_FORUM_DELETE_SUCCESS event occurs when the forum deleting process is successful before persisting.
     *
     * This event allows you to modify the values of the forum entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_DELETE_SUCCESS = 'ccdn_forum.admin.forum.delete.success';

    /**
     *
     * The ADMIN_FORUM_DELETE_COMPLETE event occurs when the forum deleting process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the forum entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent instance.
     */
    const ADMIN_FORUM_DELETE_COMPLETE = 'ccdn_forum.admin.forum.delete.complete';

    /**
     *
     * The ADMIN_FORUM_DELETE_RESPONSE event occurs when the forum deleting process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumResponseEvent instance.
     */
    const ADMIN_FORUM_DELETE_RESPONSE = 'ccdn_forum.admin.forum.delete.response';

    /**
     *
     * The ADMIN_CATEGORY_CREATE_INITIALISE event occurs when the category creation process is initalised.
     *
     * This event allows you to modify the default values of the category entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_CREATE_INITIALISE = 'ccdn_forum.admin.category.create.initialise';

    /**
     *
     * The ADMIN_CATEGORY_CREATE_SUCCESS event occurs when the category creation process is successful before persisting.
     *
     * This event allows you to modify the values of the category entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_CREATE_SUCCESS = 'ccdn_forum.admin.category.create.success';

    /**
     *
     * The ADMIN_CATEGORY_CREATE_COMPLETE event occurs when the category creation process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the category entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_CREATE_COMPLETE = 'ccdn_forum.admin.category.create.complete';

    /**
     *
     * The ADMIN_CATEGORY_CREATE_RESPONSE event occurs when the category creation process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryResponseEvent instance.
     */
    const ADMIN_CATEGORY_CREATE_RESPONSE = 'ccdn_forum.admin.category.create.response';

    /**
     *
     * The ADMIN_CATEGORY_EDIT_INITIALISE event occurs when the category editing process is initalised.
     *
     * This event allows you to modify the default values of the category entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_EDIT_INITIALISE = 'ccdn_forum.admin.category.edit.initialise';

    /**
     *
     * The ADMIN_CATEGORY_EDIT_SUCCESS event occurs when the category editing process is successful before persisting.
     *
     * This event allows you to modify the values of the category entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_EDIT_SUCCESS = 'ccdn_forum.admin.category.edit.success';

    /**
     *
     * The ADMIN_CATEGORY_EDIT_COMPLETE event occurs when the category editing process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the category entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_EDIT_COMPLETE = 'ccdn_forum.admin.category.edit.complete';

    /**
     *
     * The ADMIN_CATEGORY_EDIT_RESPONSE event occurs when the category editing process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryResponseEvent instance.
     */
    const ADMIN_CATEGORY_EDIT_RESPONSE = 'ccdn_forum.admin.category.edit.response';

    /**
     *
     * The ADMIN_CATEGORY_DELETE_INITIALISE event occurs when the category deleting process is initalised.
     *
     * This event allows you to modify the default values of the category entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_DELETE_INITIALISE = 'ccdn_forum.admin.category.delete.initialise';

    /**
     *
     * The ADMIN_CATEGORY_DELETE_SUCCESS event occurs when the category deleting process is successful before persisting.
     *
     * This event allows you to modify the values of the category entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_DELETE_SUCCESS = 'ccdn_forum.admin.category.delete.success';

    /**
     *
     * The ADMIN_CATEGORY_DELETE_COMPLETE event occurs when the category deleting process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the category entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_DELETE_COMPLETE = 'ccdn_forum.admin.category.delete.complete';

    /**
     *
     * The ADMIN_CATEGORY_DELETE_RESPONSE event occurs when the category deleting finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryResponseEvent instance.
     */
    const ADMIN_CATEGORY_DELETE_RESPONSE = 'ccdn_forum.admin.category.delete.response';

    /**
     *
     * The ADMIN_CATEGORY_REORDER_INITIALISE event occurs when the category reorder process is initalised.
     *
     * This event allows you to modify the default values of the category entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_REORDER_INITIALISE = 'ccdn_forum.admin.category.reorder.initialise';

    /**
     *
     * The ADMIN_CATEGORY_REORDER_SUCCESS event occurs when the category reorder process is successful before persisting.
     *
     * This event allows you to modify the values of the category entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_REORDER_SUCCESS = 'ccdn_forum.admin.category.reorder.success';

    /**
     *
     * The ADMIN_CATEGORY_REORDER_COMPLETE event occurs when the category reorder process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the category entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent instance.
     */
    const ADMIN_CATEGORY_REORDER_COMPLETE = 'ccdn_forum.admin.category.reorder.complete';

    /**
     *
     * The ADMIN_CATEGORY_REORDER_RESPONSE event occurs when the category reorder process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryResponseEvent instance.
     */
    const ADMIN_CATEGORY_REORDER_RESPONSE = 'ccdn_forum.admin.category.reorder.response';

    /**
     *
     * The ADMIN_BOARD_CREATE_INITIALISE event occurs when the board creation process is initalised.
     *
     * This event allows you to modify the default values of the board entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_CREATE_INITIALISE = 'ccdn_forum.admin.board.create.initialise';

    /**
     *
     * The ADMIN_BOARD_CREATE_SUCCESS event occurs when the board creation process is successful before persisting.
     *
     * This event allows you to modify the values of the board entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_CREATE_SUCCESS = 'ccdn_forum.admin.board.create.success';

    /**
     *
     * The ADMIN_BOARD_CREATE_COMPLETE event occurs when the board creation process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the board entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_CREATE_COMPLETE = 'ccdn_forum.admin.board.create.complete';

    /**
     *
     * The ADMIN_BOARD_CREATE_RESPONSE event occurs when the board creation process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardResponseEvent instance.
     */
    const ADMIN_BOARD_CREATE_RESPONSE = 'ccdn_forum.admin.board.create.response';

    /**
     *
     * The ADMIN_BOARD_EDIT_INITIALISE event occurs when the board editing process is initalised.
     *
     * This event allows you to modify the default values of the board entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_EDIT_INITIALISE = 'ccdn_forum.admin.board.edit.initialise';

    /**
     *
     * The ADMIN_BOARD_EDIT_SUCCESS event occurs when the board editing process is successful before persisting.
     *
     * This event allows you to modify the values of the board entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_EDIT_SUCCESS = 'ccdn_forum.admin.board.edit.success';

    /**
     *
     * The ADMIN_BOARD_EDIT_COMPLETE event occurs when the board editing process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the board entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_EDIT_COMPLETE = 'ccdn_forum.admin.board.edit.complete';

    /**
     *
     * The ADMIN_BOARD_EDIT_RESPONSE event occurs when the board editing process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardResponseEvent instance.
     */
    const ADMIN_BOARD_EDIT_RESPONSE = 'ccdn_forum.admin.board.edit.response';

    /**
     *
     * The ADMIN_BOARD_DELETE_INITIALISE event occurs when the board deleting process is initalised.
     *
     * This event allows you to modify the default values of the board entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_DELETE_INITIALISE = 'ccdn_forum.admin.board.delete.initialise';

    /**
     *
     * The ADMIN_BOARD_DELETE_SUCCESS event occurs when the board deleting process is successful before persisting.
     *
     * This event allows you to modify the values of the board entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_DELETE_SUCCESS = 'ccdn_forum.admin.board.delete.success';

    /**
     *
     * The ADMIN_BOARD_DELETE_COMPLETE event occurs when the board deleting process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the board entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_DELETE_COMPLETE = 'ccdn_forum.admin.board.delete.complete';

    /**
     *
     * The ADMIN_BOARD_DELETE_RESPONSE event occurs when the board deleting process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardResponseEvent instance.
     */
    const ADMIN_BOARD_DELETE_RESPONSE = 'ccdn_forum.admin.board.delete.response';

    /**
     *
     * The ADMIN_BOARD_REORDER_INITIALISE event occurs when the board reorder process is initalised.
     *
     * This event allows you to modify the default values of the board entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_REORDER_INITIALISE = 'ccdn_forum.admin.board.reorder.initialise';

    /**
     *
     * The ADMIN_BOARD_REORDER_SUCCESS event occurs when the board reorder process is successful before persisting.
     *
     * This event allows you to modify the values of the board entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_REORDER_SUCCESS = 'ccdn_forum.admin.board.reorder.success';

    /**
     *
     * The ADMIN_BOARD_REORDER_COMPLETE event occurs when the board reorder process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the board entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent instance.
     */
    const ADMIN_BOARD_REORDER_COMPLETE = 'ccdn_forum.admin.board.reorder.complete';

    /**
     *
     * The ADMIN_BOARD_REORDER_RESPONSE event occurs when the board reorder process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardResponseEvent instance.
     */
    const ADMIN_BOARD_REORDER_RESPONSE = 'ccdn_forum.admin.board.reorder.response';

    /**
     *
     * The MODERATOR_TOPIC_SOFT_DELETE_INITIALISE event occurs when the topic soft-delete process is initalised.
     *
     * This event allows you to modify the default values of the topic entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_SOFT_DELETE_INITIALISE = 'ccdn_forum.moderator.topic.soft_delete.initialise';

    /**
     *
     * The MODERATOR_TOPIC_SOFT_DELETE_SUCCESS event occurs when the topic soft-delete process is successful before persisting.
     *
     * This event allows you to modify the values of the topic entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_SOFT_DELETE_SUCCESS = 'ccdn_forum.moderator.topic.soft_delete.success';

    /**
     *
     * The MODERATOR_TOPIC_SOFT_DELETE_COMPLETE event occurs when the topic soft-delete process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_SOFT_DELETE_COMPLETE = 'ccdn_forum.moderator.topic.soft_delete.complete';

    /**
     *
     * The MODERATOR_TOPIC_SOFT_DELETE_RESPONSE event occurs when the topic soft-delete process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicResponseEvent instance.
     */
    const MODERATOR_TOPIC_SOFT_DELETE_RESPONSE = 'ccdn_forum.moderator.topic.soft_delete.response';

    /**
     *
     * The MODERATOR_TOPIC_RESTORE_COMPLETE event occurs when the topic restore process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_RESTORE_COMPLETE = 'ccdn_forum.moderator.topic.restore.complete';

    /**
     *
     * The MODERATOR_TOPIC_STICKY_COMPLETE event occurs when the topic sticky process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_STICKY_COMPLETE = 'ccdn_forum.moderator.topic.sticky.complete';

    /**
     *
     * The MODERATOR_TOPIC_UNSTICKY_COMPLETE event occurs when the topic unsticky process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_UNSTICKY_COMPLETE = 'ccdn_forum.moderator.topic.unsticky.complete';

    /**
     *
     * The MODERATOR_TOPIC_CLOSE_COMPLETE event occurs when the topic close process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_CLOSE_COMPLETE = 'ccdn_forum.moderator.topic.close.complete';

    /**
     *
     * The MODERATOR_TOPIC_REOPEN_COMPLETE event occurs when the topic reopen process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_REOPEN_COMPLETE = 'ccdn_forum.moderator.topic.reopen.complete';

    /**
     *
     * The MODERATOR_TOPIC_CHANGE_BOARD_INITIALISE event occurs when the topic move process is initalised.
     *
     * This event allows you to modify the default values of the topic entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_CHANGE_BOARD_INITIALISE = 'ccdn_forum.moderator.topic.change_board.initialise';

    /**
     *
     * The MODERATOR_TOPIC_CHANGE_BOARD_SUCCESS event occurs when the topic move process is successful before persisting.
     *
     * This event allows you to modify the values of the topic entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent instance.
     */
    const MODERATOR_TOPIC_CHANGE_BOARD_SUCCESS = 'ccdn_forum.moderator.topic.change_board.success';

    /**
     *
     * The MODERATOR_TOPIC_CHANGE_BOARD_COMPLETE event occurs when the topic move process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicMoveEvent instance.
     */
    const MODERATOR_TOPIC_CHANGE_BOARD_COMPLETE = 'ccdn_forum.moderator.topic.change_board.complete';

    /**
     *
     * The MODERATOR_TOPIC_CHANGE_BOARD_RESPONSE event occurs when the topic move process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicResponseEvent instance.
     */
    const MODERATOR_TOPIC_CHANGE_BOARD_RESPONSE = 'ccdn_forum.moderator.topic.change_board.response';

    /**
     *
     * The MODERATOR_POST_RESTORE_COMPLETE event occurs when the post restore process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent instance.
     */
    const MODERATOR_POST_RESTORE_COMPLETE = 'ccdn_forum.moderator.post.restore.complete';

    /**
     *
     * The MODERATOR_POST_UNLOCK_INITIALISE event occurs when the post unlock process is initalised.
     *
     * This event allows you to modify the default values of the post entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent instance.
     */
    const MODERATOR_POST_UNLOCK_INITIALISE = 'ccdn_forum.moderator.post.unlock.initialise';

    /**
     *
     * The MODERATOR_POST_UNLOCK_SUCCESS event occurs when the post unlock process is successful before persisting.
     *
     * This event allows you to modify the values of the post entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent instance.
     */
    const MODERATOR_POST_UNLOCK_SUCCESS = 'ccdn_forum.moderator.post.unlock.success';

    /**
     *
     * The MODERATOR_POST_UNLOCK_COMPLETE event occurs when the post unlock process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the post entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent instance.
     */
    const MODERATOR_POST_UNLOCK_COMPLETE = 'ccdn_forum.moderator.post.unlock.complete';

    /**
     *
     * The MODERATOR_POST_UNLOCK_RESPONSE event occurs when the post unlock process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostResponseEvent instance.
     */
    const MODERATOR_POST_UNLOCK_RESPONSE = 'ccdn_forum.moderator.post.unlock.response';

    /**
     *
     * The MODERATOR_POST_LOCK_COMPLETE event occurs when the post lock process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the post entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorPostEvent instance.
     */
    const MODERATOR_POST_LOCK_COMPLETE = 'ccdn_forum.moderator.post.lock.complete';

    /**
     *
     * The USER_TOPIC_CREATE_INITIALISE event occurs when the topic create process is initalised.
     *
     * This event allows you to modify the default values of the topic entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_CREATE_INITIALISE = 'ccdn_forum.user.topic.create.initialise';

    /**
     *
     * The USER_TOPIC_CREATE_SUCCESS event occurs when the topic create process is successful before persisting.
     *
     * This event allows you to modify the values of the topic entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_CREATE_SUCCESS = 'ccdn_forum.user.topic.create.success';

    /**
     *
     * The USER_TOPIC_CREATE_COMPLETE event occurs when the topic create process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_CREATE_COMPLETE = 'ccdn_forum.user.topic.create.complete';

    /**
     *
     * The USER_TOPIC_CREATE_RESPONSE event occurs when the topic create process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicResponseEvent instance.
     */
    const USER_TOPIC_CREATE_RESPONSE = 'ccdn_forum.user.topic.create.response';

    /**
     *
     * The USER_TOPIC_CREATE_FLOODED event occurs when the topic create process fails due to flooding being raised.
     *
     * This event allows you to modify the request object and set a flash message from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicFloodEvent instance.
     */
    const USER_TOPIC_CREATE_FLOODED = 'ccdn_forum.user.topic.create.flooded';

    /**
     *
     * The USER_TOPIC_REPLY_INITIALISE event occurs when the reply create process is initalised.
     *
     * This event allows you to modify the default values of the post and topic entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_REPLY_INITIALISE = 'ccdn_forum.user.topic.reply.initialise';

    /**
     *
     * The USER_TOPIC_REPLY_SUCCESS event occurs when the topic reply process is successful before persisting.
     *
     * This event allows you to modify the values of the post and topic entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_REPLY_SUCCESS = 'ccdn_forum.user.topic.reply.success';

    /**
     *
     * The USER_TOPIC_REPLY_COMPLETE event occurs when the topic reply process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the post and topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_REPLY_COMPLETE = 'ccdn_forum.user.topic.reply.complete';

    /**
     *
     * The USER_TOPIC_REPLY_RESPONSE event occurs when the topic reply process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicResponseEvent instance.
     */
    const USER_TOPIC_REPLY_RESPONSE = 'ccdn_forum.user.topic.reply.response';

    /**
     *
     * The USER_TOPIC_REPLY_FLOODED event occurs when the topic reply process fails due to flooding being raised.
     *
     * This event allows you to modify the request object and set a flash message from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicFloodEvent instance.
     */
    const USER_TOPIC_REPLY_FLOODED = 'ccdn_forum.user.topic.reply.flooded';

    /**
     *
     * The USER_TOPIC_SUBSCRIBE_COMPLETE event occurs when the topic subscribe process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_SUBSCRIBE_COMPLETE = 'ccdn_forum.user.topic.subscribe.complete';

    /**
     *
     * The USER_TOPIC_UNSUBSCRIBE_COMPLETE event occurs when the topic unsubscribe process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the topic entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent instance.
     */
    const USER_TOPIC_UNSUBSCRIBE_COMPLETE = 'ccdn_forum.user.topic.unsubscribe.complete';

    /**
     *
     * The USER_POST_EDIT_INITIALISE event occurs when the post edit process is initalised.
     *
     * This event allows you to modify the default values of the post entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent instance.
     */
    const USER_POST_EDIT_INITIALISE = 'ccdn_forum.user.post.edit.initialise';

    /**
     *
     * The USER_POST_EDIT_SUCCESS event occurs when the post edit process is successful before persisting.
     *
     * This event allows you to modify the values of the post entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent instance.
     */
    const USER_POST_EDIT_SUCCESS = 'ccdn_forum.user.post.edit.success';

    /**
     *
     * The USER_POST_EDIT_COMPLETE event occurs when the post edit process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the post entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent instance.
     */
    const USER_POST_EDIT_COMPLETE = 'ccdn_forum.user.post.edit.complete';

    /**
     *
     * The USER_POST_EDIT_RESPONSE event occurs when the post edit process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostResponseEvent instance.
     */
    const USER_POST_EDIT_RESPONSE = 'ccdn_forum.user.post.edit.response';

    /**
     *
     * The USER_POST_DELETE_INITIALISE event occurs when the post delete process is initalised.
     *
     * This event allows you to modify the default values of the post entity object before binding the form.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent instance.
     */
    const USER_POST_SOFT_DELETE_INITIALISE = 'ccdn_forum.user.post.soft_delete.initialise';

    /**
     *
     * The USER_POST_DELETE_SUCCESS event occurs when the post delete process is successful before persisting.
     *
     * This event allows you to modify the values of the post entity object after form submission before persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent instance.
     */
    const USER_POST_SOFT_DELETE_SUCCESS = 'ccdn_forum.user.post.soft_delete.success';

    /**
     *
     * The USER_POST_DELETE_COMPLETE event occurs when the post delete process is completed successfully after persisting.
     *
     * This event allows you to modify the values of the post entity after persisting.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent instance.
     */
    const USER_POST_SOFT_DELETE_COMPLETE = 'ccdn_forum.user.post.soft_delete.complete';

    /**
     *
     * The USER_POST_DELETE_RESPONSE event occurs when the post delete process finishes and returns a HTTP response.
     *
     * This event allows you to modify the default values of the response object returned from the controller action.
     * The event listener method receives a CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostResponseEvent instance.
     */
    const USER_POST_SOFT_DELETE_RESPONSE = 'ccdn_forum.user.post.soft_delete.response';
}
