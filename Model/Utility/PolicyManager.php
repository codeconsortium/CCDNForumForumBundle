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

namespace CCDNForum\ForumBundle\Model\Utility;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\SecurityContext;

use CCDNForum\ForumBundle\Model\Model\Bag\ModelBagInterface;

use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
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
class PolicyManager
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    protected $securityContext;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\Model\Bag\ModelBagInterface $modelBag
     */
    protected $modelBag;

    /**
     *
     * @access protected
     * @var string $roleToViewDeletedTopics
     */
    protected $roleToViewDeletedTopics = 'ROLE_MODERATOR';

    /**
     *
     * @access public
     * @param \Symfony\Component\Security\Core\SecurityContext         $securityContext
     * @param \CCDNForum\ForumBundle\Model\Model\Bag\ModelBagInterface $modelBag
     */
    public function __construct(SecurityContext $securityContext, ModelBagInterface $modelBag)
    {
        $this->securityContext = $securityContext;

        $this->modelBag = $modelBag;
    }

    /**
     *
     * @access public
     * @param  string $role
     * @return bool
     */
    public function isGranted($role)
    {
        return $this->securityContext->isGranted($role);
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function allowedToViewDeletedTopics()
    {
        return $this->securityContext->isGranted($this->roleToViewDeletedTopics);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board $board
     * @return bool
     */
    public function isAuthorisedToViewBoard(Board $board)
    {
        if (! $board->isAuthorisedToRead($this->securityContext)) {
            return false;
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Board $board
     * @return bool
     */
    public function isAuthorisedToCreateTopic(Board $board)
    {
        if (! $board->isAuthorisedToCreateTopic($this->securityContext)) {
            return false;
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return bool
     */
    public function isAuthorisedToViewTopic(Topic $topic)
    {
        if (! $topic->getBoard()->isAuthorisedToRead($this->securityContext)) {
            return false;
        }

        if ($topic->getIsDeleted()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return bool
     */
    public function isAuthorisedToReplyToTopic(Topic $topic)
    {
        if (! $topic->getBoard()->isAuthorisedToTopicReply($this->securityContext)) {
            return false;
        }

        if ($topic->getIsClosed() || $topic->getIsDeleted()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return bool
     */
    public function isAuthorisedToEditTopic(Topic $topic)
    {
        if ($topic->getIsDeleted() || $topic->getIsClosed()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($this->isGranted('ROLE_USER')) {
            if ($topic->getFirstPost()) {
                if ($topic->getFirstPost()->getCreatedBy()) {
                    $post = $topic->getFirstPost();

                    // if user does not own post, or is not a mod
                    if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
                        if (! $this->isGranted('ROLE_MODERATOR')) {
                            return false;
                        }
                    }
                } else {
                    if (! $this->isGranted('ROLE_MODERATOR')) {
                        return false;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return bool
     */
    public function isAuthorisedToDeleteTopic(Topic $topic)
    {
        if ($topic->getIsDeleted() || $topic->getIsClosed()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($topic->getCachedReplyCount() > 0) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($this->isGranted('ROLE_USER')) {
            if ($topic->getFirstPost()) {
                if ($topic->getFirstPost()->getCreatedBy()) {
                    $post = $topic->getFirstPost();

                    // if user does not own post, or is not a mod
                    if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
                        if (! $this->isGranted('ROLE_MODERATOR')) {
                            return false;
                        }
                    }
                } else {
                    if (! $this->isGranted('ROLE_MODERATOR')) {
                        return false;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Topic $topic
     * @return bool
     */
    public function isAuthorisedToRestoreTopic(Topic $topic)
    {
        if ($topic->getIsDeleted() || $topic->getIsClosed()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($this->isGranted('ROLE_MODERATOR')) {
            if ($topic->getFirstPost()) {
                if ($topic->getFirstPost()->getCreatedBy()) {
                    $post = $topic->getFirstPost();

                    // if user does not own post, or is not a mod
                    if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
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
        return $this->isAuthorisedToEditTopic($topic);
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
        return $this->isAuthorisedToEditTopic($topic);
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
        return $this->isAuthorisedToEditTopic($topic);
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
        return $this->isAuthorisedToEditTopic($topic);
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
        return $this->isAuthorisedToEditTopic($topic);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post $post
     * @return bool
     */
    public function isAuthorisedToViewPost(Post $post)
    {
        if (! $post->getTopic()->getBoard()->isAuthorisedToRead($this->securityContext)) {
            return false;
        }

        if ($post->getTopic()->getIsDeleted()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post $post
     * @return bool
     */
    public function isAuthorisedToEditPost(Post $post)
    {
        if ($post->getIsDeleted() || $post->getIsLocked() || $post->getTopic()->getIsDeleted() || $post->getTopic()->getIsClosed()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($this->isGranted('ROLE_USER')) {
            if ($post->getCreatedBy()) {
                // if user does not own post, or is not a mod
                if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
                    if (! $this->isGranted('ROLE_MODERATOR')) {
                        return false;
                    }
                }
            } else {
                if (! $this->isGranted('ROLE_MODERATOR')) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post $post
     * @return bool
     */
    public function isAuthorisedToDeletePost(Post $post)
    {
        if ($post->getIsDeleted() || $post->getIsLocked() || $post->getTopic()->getIsDeleted() || $post->getTopic()->getIsClosed()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($this->isGranted('ROLE_USER')) {
            if ($post->getCreatedBy()) {
                // if user does not own post, or is not a mod
                if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
                    if (! $this->isGranted('ROLE_MODERATOR')) {
                        return false;
                    }
                }
            } else {
                if (! $this->isGranted('ROLE_MODERATOR')) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post $post
     * @return bool
     */
    public function isAuthorisedToRestorePost(Post $post)
    {
        if ($post->getIsDeleted() || $post->getIsLocked() || $post->getTopic()->getIsDeleted() || $post->getTopic()->getIsClosed()) {
            if (! $this->isGranted('ROLE_MODERATOR')) {
                return false;
            }
        }

        if ($this->isGranted('ROLE_MODERATOR')) {
            if ($post->getCreatedBy()) {
                // if user does not own post, or is not a mod
                if ($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
                    if (! $this->isGranted('ROLE_MODERATOR')) {
                        return false;
                    }
                }
            } else {
                if (! $this->isGranted('ROLE_MODERATOR')) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }
}
