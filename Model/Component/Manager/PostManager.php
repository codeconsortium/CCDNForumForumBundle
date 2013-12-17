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

namespace CCDNForum\ForumBundle\Model\Component\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNForum\ForumBundle\Model\Component\Gateway\GatewayInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\BaseManager;
use CCDNForum\ForumBundle\Component\Helper\PostLockHelper;

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
class PostManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Component\Helper\PostLockHelper $postLockHelper
     */
    protected $postLockHelper;

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Gateway\GatewayInterface        $gateway
     * @param \CCDNForum\ForumBundle\Component\Helper\PostLockHelper $postLockHelper
     */
    public function __construct(GatewayInterface $gateway, PostLockHelper $postLockHelper)
    {
        $this->gateway = $gateway;
        $this->postLockHelper = $postLockHelper;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Post
     */
    public function createPost()
    {
        return $this->gateway->createPost();
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post              $post
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function savePost(Post $post)
    {
        $this->postLockHelper->setLockLimitOnPost($post);

        $this->gateway->savePost($post);

        // refresh the user so that we have an PostId to work with.
        $this->refresh($post);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post              $post
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function updatePost(Post $post)
    {
        $this->gateway->updatePost($post);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post              $post
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function lock(Post $post)
    {
        $post->setUnlockedUntilDate(new \Datetime('now'));
        $this->persist($post)->flush();
        $this->refresh($post);

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post              $post
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function restore(Post $post)
    {
        $post->setDeleted(false);
        $post->setDeletedBy(null);
        $post->setDeletedDate(null);

        // update the record
        $this->persist($post)->flush();

        if ($post->getTopic()) {
            $topic = $post->getTopic();

            // if this is the first post and only post,
            // then restore the topic aswell.
            if ($topic->getCachedReplyCount() < 1) {
                $topic->setDeleted(false);
                $topic->setDeletedBy(null);
                $topic->setDeletedDate(null);

                $this->persist($topic)->flush();
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @param  \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function softDelete(Post $post, UserInterface $user)
    {
        // Don't overwite previous users accountability.
        if (! $post->getDeletedBy() && ! $post->getDeletedDate()) {
            $post->setDeleted(true);
            $post->setDeletedBy($user);
            $post->setDeletedDate(new \DateTime());

            // update the record
            $this->persist($post)->flush();
        }

        return $this;
    }
}
