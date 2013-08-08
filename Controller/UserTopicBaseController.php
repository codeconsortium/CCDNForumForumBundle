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

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Board;
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
class UserTopicBaseController extends BaseController
{
    /**
     *
     * @access private
     * @var \CCDNForum\ForumBundle\Component\FloodControl $floodControl
     */
    private $floodControl;

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToViewTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToViewTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board                              $board
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToCreateTopic(Board $board)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToCreateTopic($board));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToReplyToTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToReplyToTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToEditTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToEditTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToDeleteTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToDeleteTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToRestoreTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToRestoreTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToMoveTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToMoveTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToCloseTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToCloseTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToReOpenTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToReOpenTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToStickyTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToStickyTopic($topic));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                              $topic
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToUnStickyTopic(Topic $topic)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToUnstickyTopic($topic));
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Component\FloodControl
     */
    public function getFloodControl()
    {
        if (null == $this->floodControl) {
            $this->floodControl = $this->container->get('ccdn_forum_forum.component.flood_control');
        }

        return $this->floodControl;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Forum                        $forum
     * @param  \CCDNForum\ForumBundle\Entity\Board                        $board
     * @param  int                                                        $draftId
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
     */
    public function getFormHandlerToCreateTopic(Forum $forum, Board $board)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.topic_create');

		$formHandler->setForum($forum);
        $formHandler->setBoard($board);
		$formHandler->setUser($this->getUser());
		$formHandler->setRequest($this->getRequest());
		
        //if ( ! empty($draftId)) {
        //    $draft = $this->getDraftManager()->findOneById($draftId);
        //}
        return $formHandler;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                        $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
     */
    public function getFormHandlerToReplyToTopic(Topic $topic)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.post_create');

        $formHandler->setTopic($topic);
		$formHandler->setUser($this->getUser());
		$formHandler->setRequest($this->getRequest());

        //if ( ! empty($draftId)) {
        //    $draft = $this->getDraftManager()->findOneById($draftId);
        //}

        //if ( ! empty($quoteId)) {
        //    $quote = $this->getPostModel()->findOneByIdWithTopicAndBoard($quoteId);
        //
        //    $formHandler->setPostToQuote($quote);
        //}

        return $formHandler;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic                             $topic
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicChangeBoardFormHandler
     */
    public function getFormHandlerToChangeBoardOnTopic(Topic $topic)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.change_topics_board');

        $formHandler->setTopic($topic);

        return $formHandler;
    }
}
