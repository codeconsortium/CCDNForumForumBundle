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

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Topic;

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
class ModeratorTopicBaseController extends BaseController
{
    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Topic                        $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
     */
    protected function getFormHandlerToDeleteTopic(Topic $topic)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.topic_delete');

        $formHandler->setTopic($topic);
        $formHandler->setUser($this->getUser());
        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Forum                             $forum
     * @param  \CCDNForum\ForumBundle\Entity\Topic                             $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicChangeBoardFormHandler
     */
    protected function getFormHandlerToChangeBoardOnTopic(Forum $forum, Topic $topic)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.change_topics_board');

        $formHandler->setForum($forum);
        $formHandler->setTopic($topic);
        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }
}
