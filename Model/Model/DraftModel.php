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

namespace CCDNForum\ForumBundle\Model\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\BaseModelInterface;

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
class DraftModel extends BaseModel implements BaseModelInterface
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
        return $this->getManager()->getDraft($draftId);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Post                  $post
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function create(Post $post)
    {
        return $this->getManager()->create($post);
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Draft                 $draft
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function update(Draft $draft)
    {
        return $this->getManager()->update($draft);
    }
}