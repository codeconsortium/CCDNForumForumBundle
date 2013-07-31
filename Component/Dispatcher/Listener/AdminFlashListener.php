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

namespace CCDNForum\ForumBundle\Component\Dispatcher\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminBoardEvent;

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
class AdminFlashListener implements EventSubscriberInterface
{
	private $session;

	public function __construct($session)
	{
		$this->session = $session;
	}

	/**
	 * 
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			ForumEvents::ADMIN_FORUM_CREATE_COMPLETE    => 'onForumCreateComplete',
			ForumEvents::ADMIN_FORUM_EDIT_COMPLETE      => 'onForumEditComplete',
			ForumEvents::ADMIN_FORUM_DELETE_COMPLETE    => 'onForumDeleteComplete',
			ForumEvents::ADMIN_CATEGORY_CREATE_COMPLETE => 'onCategoryCreateComplete',
			ForumEvents::ADMIN_CATEGORY_EDIT_COMPLETE   => 'onCategoryEditComplete',
			ForumEvents::ADMIN_CATEGORY_DELETE_COMPLETE => 'onCategoryDeleteComplete',
			ForumEvents::ADMIN_BOARD_CREATE_COMPLETE    => 'onBoardCreateComplete',
			ForumEvents::ADMIN_BOARD_EDIT_COMPLETE      => 'onBoardEditComplete',
			ForumEvents::ADMIN_BOARD_DELETE_COMPLETE    => 'onBoardDeleteComplete',
		);
	}
	
	public function onForumCreateComplete(AdminForumEvent $event)
	{
		if ($event->getForum()) {
			if ($event->getForum()->getId()) {
				$this->session->setFlash('success', 'Successfully created new forum "' . $event->getForum()->getName() .'"');
			}
		}
	}

	public function onForumEditComplete(AdminForumEvent $event)
	{
		if ($event->getForum()) {
			if ($event->getForum()->getId()) {
				$this->session->setFlash('success', 'Successfully edited the forum "' . $event->getForum()->getName() .'"');
			}
		}
	}

	public function onForumDeleteComplete(AdminForumEvent $event)
	{
		if ($event->getForum()) {
			if (! $event->getForum()->getId()) {
				$this->session->setFlash('success', 'Successfully deleted the forum "' . $event->getForum()->getName() .'"');
			}
		}
	}

	public function onCategoryCreateComplete(AdminCategoryEvent $event)
	{
		if ($event->getCategory()) {
			if ($event->getCategory()->getId()) {
				$this->session->setFlash('success', 'Successfully created new category "' . $event->getCategory()->getName() .'"');
			}
		}
	}

	public function onCategoryEditComplete(AdminCategoryEvent $event)
	{
		if ($event->getCategory()) {
			if ($event->getCategory()->getId()) {
				$this->session->setFlash('success', 'Successfully edited the category "' . $event->getCategory()->getName() .'"');
			}
		}
	}

	public function onCategoryDeleteComplete(AdminCategoryEvent $event)
	{
		if ($event->getCategory()) {
			if (! $event->getCategory()->getId()) {
				$this->session->setFlash('success', 'Successfully deleted the category "' . $event->getCategory()->getName() .'"');
			}
		}
	}

	public function onBoardCreateComplete(AdminBoardEvent $event)
	{
		if ($event->getBoard()) {
			if ($event->getBoard()->getId()) {
				$this->session->setFlash('success', 'Successfully created new board "' . $event->getBoard()->getName() .'"');
			}
		}
	}

	public function onBoardEditComplete(AdminBoardEvent $event)
	{
		if ($event->getBoard()) {
			if ($event->getBoard()->getId()) {
				$this->session->setFlash('success', 'Successfully edited the board "' . $event->getBoard()->getName() .'"');
			}
		}
	}

	public function onBoardDeleteComplete(AdminBoardEvent $event)
	{
		if ($event->getBoard()) {
			if (! $event->getBoard()->getId()) {
				$this->session->setFlash('success', 'Successfully deleted the board "' . $event->getBoard()->getName() .'"');
			}
		}
	}
}