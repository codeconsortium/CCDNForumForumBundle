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

namespace CCDNForum\ForumBundle\Component\TwigExtension;

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Subscription;

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
class AuthorizerExtension extends \Twig_Extension
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Component\Security\Authorizer $authorizer
     */
    protected $authorizer;

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Security\Authorizer $authorizer
     */
    public function __construct($authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     *
     * @access public
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'canShowForum'            => new \Twig_Function_Method($this, 'canShowForum'),
            'canShowCategory'         => new \Twig_Function_Method($this, 'canShowCategory'),
            'canShowBoard'            => new \Twig_Function_Method($this, 'canShowBoard'),
            'canCreateTopicOnBoard'   => new \Twig_Function_Method($this, 'canCreateTopicOnBoard'),
            'canReplyToTopicOnBoard'  => new \Twig_Function_Method($this, 'canReplyToTopicOnBoard'),
            'canShowTopic'            => new \Twig_Function_Method($this, 'canShowTopic'),
            'canReplyToTopic'         => new \Twig_Function_Method($this, 'canReplyToTopic'),
            'canDeleteTopic'          => new \Twig_Function_Method($this, 'canDeleteTopic'),
            'canRestoreTopic'         => new \Twig_Function_Method($this, 'canRestoreTopic'),
            'canCloseTopic'           => new \Twig_Function_Method($this, 'canCloseTopic'),
            'canReopenTopic'          => new \Twig_Function_Method($this, 'canReopenTopic'),
            'canTopicChangeBoard'     => new \Twig_Function_Method($this, 'canTopicChangeBoard'),
            'canStickyTopic'          => new \Twig_Function_Method($this, 'canStickyTopic'),
            'canUnstickyTopic'        => new \Twig_Function_Method($this, 'canUnstickyTopic'),
            'canShowPost'             => new \Twig_Function_Method($this, 'canShowPost'),
            'canEditPost'             => new \Twig_Function_Method($this, 'canEditPost'),
            'canDeletePost'           => new \Twig_Function_Method($this, 'canDeletePost'),
            'canRestorePost'          => new \Twig_Function_Method($this, 'canRestorePost'),
            'canLockPost'             => new \Twig_Function_Method($this, 'canLockPost'),
            'canUnlockPost'           => new \Twig_Function_Method($this, 'canUnlockPost'),
            'canSubscribeToTopic'     => new \Twig_Function_Method($this, 'canSubscribeToTopic'),
            'canUnsubscribeFromTopic' => new \Twig_Function_Method($this, 'canUnsubscribeFromTopic'),
        );
    }

    public function canShowForum(Forum $forum)
    {
        return $this->authorizer->canShowForum($forum);
    }

    public function canShowCategory(Category $category, Forum $forum = null)
    {
        return $this->authorizer->canShowCategory($category, $forum);
    }

    public function canShowBoard(Board $board, Forum $forum = null)
    {
        return $this->authorizer->canShowBoard($board, $forum);
    }

    public function canCreateTopicOnBoard(Board $board, Forum $forum = null)
    {
        return $this->authorizer->canCreateTopicOnBoard($board, $forum);
    }

    public function canReplyToTopicOnBoard(Board $board, Forum $forum = null)
    {
        return $this->authorizer->canReplyToTopicOnBoard($board, $forum);
    }

    public function canShowTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canShowTopic($topic, $forum);
    }

    public function canReplyToTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canReplyToTopic($topic, $forum);
    }

    public function canDeleteTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canDeleteTopic($topic, $forum);
    }

    public function canRestoreTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canRestoreTopic($topic, $forum);
    }

    public function canCloseTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canCloseTopic($topic, $forum);
    }

    public function canReopenTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canReopenTopic($topic, $forum);
    }

    public function canTopicChangeBoard(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canTopicChangeBoard($topic, $forum);
    }

    public function canStickyTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canStickyTopic($topic, $forum);
    }

    public function canUnstickyTopic(Topic $topic, Forum $forum = null)
    {
        return $this->authorizer->canUnstickyTopic($topic, $forum);
    }

    public function canShowPost(Post $post, Forum $forum = null)
    {
        return $this->authorizer->canShowPost($post, $forum);
    }

    public function canEditPost(Post $post, Forum $forum = null)
    {
        return $this->authorizer->canEditPost($post, $forum);
    }

    public function canDeletePost(Post $post, Forum $forum = null)
    {
        return $this->authorizer->canDeletePost($post, $forum);
    }

    public function canRestorePost(Post $post, Forum $forum = null)
    {
        return $this->authorizer->canRestorePost($post, $forum);
    }

    public function canLockPost(Post $post, Forum $forum = null)
    {
        return $this->authorizer->canLockPost($post, $forum);
    }

    public function canUnlockPost(Post $post, Forum $forum = null)
    {
        return $this->authorizer->canUnlockPost($post, $forum);
    }

    public function canSubscribeToTopic(Topic $topic, Forum $forum = null, Subscription $subscription = null)
    {
        return $this->authorizer->canSubscribeToTopic($topic, $forum, $subscription);
    }

    public function canUnsubscribeFromTopic(Topic $topic, Forum $forum = null, Subscription $subscription = null)
    {
        return $this->authorizer->canUnsubscribeFromTopic($topic, $forum, $subscription);
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'authorizer';
    }
}
