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

namespace CCDNForum\ForumBundle\Model\Manager;

use CCDNForum\ForumBundle\Model\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Draft;

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
class DraftManager extends BaseManager implements BaseManagerInterface
{
    const TOPIC = 0;
    const REPLY = 1;

    /**
     *
     * @access public
     * @param  int                                           $draftId
     * @return \CCDNForum\ForumBundle\Entity\Post|null|array
     */
    public function getDraft($draftId)
    {
        $user = $this->securityContext->getToken()->getUser();

        $draft = $this->repository->findOneByIdForUserById($draftId, $user->getId());

        if (! $draft) {
            return null;
        }

        // is this a topic?
        if (is_object($draft->getTopic()) && $draft->getTopic() instanceof Topic) {
            if ($draft->getTopic()->getId()) {
                // we have a reply
                $type = self::REPLY;
            } else {
                // we have a topic
                $type = self::TOPIC;
            }
        } else {
            // we have a topic
            $type = self::TOPIC;
        }

        // format the entity to be returned.
        if ($type == self::REPLY) {
            $post = new Post();

            $post->setTopic($draft->getTopic());
            $post->setBody($draft->getBody());

            return $post;
        }

        if ($type == self::TOPIC) {
            $topic = new Topic();
            $post = new Post();

            if ($draft->getBoard()) {
                $topic->setBoard($draft->getBoard());
            }
            $topic->setTitle($draft->getTitle());

            $post->setBody($draft->getBody());

            return array('topic' => $topic, 'post' => $post);
        }

        return null;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function create(Post $post)
    {
        $user = $this->securityContext->getToken()->getUser();

        $draft = new Draft();

        // is this a post?
        if (is_object($post) && $post instanceof Post) {
            // is this a topic?
            if (is_object($post->getTopic()) && $post->getTopic() instanceof Topic) {
                if ($post->getTopic()->getId()) {
                    $draft->setTopic($post->getTopic());
                } else {
                    $draft->setTitle($post->getTopic()->getTitle());
                    $draft->setBoard($post->getTopic()->getBoard());
                }
            }

            $draft->setBody($post->getBody());
            $draft->setCreatedBy($user);
            $draft->setCreatedDate(new \DateTime());

            if ($post->getAttachment()) {
                $draft->setAttachment($post->getAttachment());
            }

            // insert a new row
            $this->persist($draft)->flush();
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Draft                 $draft
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function update(Draft $draft)
    {
        // update a record
        $this->persist($draft);

        return $this;
    }
}
