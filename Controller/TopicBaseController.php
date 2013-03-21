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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class TopicBaseController extends BaseController
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
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToCreateTopic(Board $board)
	{
		return $this->isAuthorised($this->getBoardManager()->isAuthorisedToCreateTopic($board));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToReplyToTopic(Topic $topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToReplyToTopic($topic));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToEditTopic(Topic $topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToEditTopic($topic));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToDeleteTopic(Topic $topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToDeleteTopic($topic));
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return bool
	 * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
	 */
	public function isAuthorisedToRestoreTopic(Topic $topic)
	{
		return $this->isAuthorised($this->getTopicManager()->isAuthorisedToRestoreTopic($topic));
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
	 * @param \CCDNForum\ForumBundle\Entity\Board $board
	 * @param int $draftId
	 * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
	 */
	public function getFormHandlerToCreateTopic(Board $board, $draftId)
	{
        //if ( ! empty($draftId)) {
        //    $draft = $this->getDraftManager()->findOneById($draftId);
        //}
		
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.topic_create');
		
		$formHandler->setBoard($board);

		return $formHandler;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @param int $draftId
	 * @param int $quoteId
	 * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
	 */
	public function getFormHandlerToReplyToTopic(Topic $topic, $draftId, $quoteId)
	{		
        //if ( ! empty($draftId)) {
        //    $draft = $this->getDraftManager()->findOneById($draftId);
        //}
	
        //if ( ! empty($quoteId)) {
        //    $quote = $this->container->get('ccdn_forum_forum.repository.post')->find($quoteId);
        //}
		
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.post_create');

		$formHandler->setTopic($topic);
		
		return $formHandler;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNForum\ForumBundle\Entity\Topic $topic
	 * @return \CCDNForum\ForumBundle\Form\Handler\TopicChangeBoardFormHandler
	 */
	public function getFormHandlerToChangeBoardOnTopic(Topic $topic)
	{
		$formHandler = $this->container->get('ccdn_forum_forum.form.handler.change_topics_board');
		
		$formHandler->setTopic($topic);
		
		return $formHandler;
	}
}