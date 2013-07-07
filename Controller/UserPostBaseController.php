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
class UserPostBaseController extends BaseController
{
    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                               $post
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToViewPost(Post $post)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToViewPost($post));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                               $post
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToEditPost(Post $post)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToEditPost($post));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                               $post
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToDeletePost(Post $post)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToDeletePost($post));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                               $post
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isAuthorisedToRestorePost(Post $post)
    {
        return $this->isAuthorised($this->getPolicyManager()->isAuthorisedToRestorePost($post));
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                         $post
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicUpdateFormHandler
     */
    public function getFormHandlerToEditTopic(Post $post)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.topic_update');

        $formHandler->setPost($post);

        return $formHandler;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                        $post
     * @return \CCDNForum\ForumBundle\Form\Handler\PostUpdateFormHandler
     */
    public function getFormHandlerToEditPost(Post $post)
    {
        $formHandler = $this->container->get('ccdn_forum_forum.form.handler.post_update');

        $formHandler->setPost($post);

        return $formHandler;
    }
}
