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

namespace CCDNForum\ForumBundle\Model\Component\Repository;

use CCDNForum\ForumBundle\Model\Component\Repository\Repository;
use CCDNForum\ForumBundle\Model\Component\Repository\RepositoryInterface;

/**
 * ForumRepository
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 */
class ForumRepository extends BaseRepository implements RepositoryInterface
{
    /**
     *
     * @access public
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllForums()
    {
        $qb = $this->createSelectQuery(array('f'));

        return $this->gateway->findForums($qb);
    }

    /**
     *
     * @access public
     * @param  int                                 $forumId
     * @return \CCDNForum\ForumBundle\Entity\Forum
     */
    public function findOneForumById($forumId)
    {
        $params = array(':forumId' => $forumId);

        $qb = $this->createSelectQuery(array('f'));

        $qb
            ->where('f.id = :forumId');
        ;

        return $this->gateway->findForum($qb, $params);
    }

    /**
     *
     * @access public
     * @param  string                              $forumName
     * @return \CCDNForum\ForumBundle\Entity\Forum
     */
    public function findOneForumByName($forumName)
    {
        $params = array(':forumName' => $forumName);

        $qb = $this->createSelectQuery(array('f'));

        $qb
            ->where('f.name = :forumName');
        ;

        return $this->gateway->findForum($qb, $params);
    }
}
