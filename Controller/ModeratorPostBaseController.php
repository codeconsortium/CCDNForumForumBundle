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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\Event;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\ModeratorTopicResponseEvent;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Post;

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
class ModeratorPostBaseController extends BaseController
{
    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Forum                        $forum
     * @param  \CCDNForum\ForumBundle\Entity\Post                         $post
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\Post\PostUnlockFormHandler
     */
    public function getFormHandlerToUnlockPost(Forum $forum, Post $post)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.post_unlock');

        $formHandler->setPost($post);
		$formHandler->setUser($this->getUser());
		$formHandler->setRequest($this->getRequest());
		
        return $formHandler;
    }
}
