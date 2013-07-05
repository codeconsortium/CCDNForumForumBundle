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

namespace CCDNForum\ForumBundle\Model\Gateway;

use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Gateway\BaseGatewayInterface;
use CCDNForum\ForumBundle\Model\Gateway\BaseGateway;

use CCDNForum\ForumBundle\Entity\Category;

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
class DraftGateway extends BaseGateway implements BaseGatewayInterface
{
    /**
     *
     * @access private
     * @var string $queryAlias
     */
    protected $queryAlias = 'd';

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findDraft(QueryBuilder $qb = null, $parameters = null)
    {
        if (null == $qb) {
            $qb = $this->createSelectQuery();
        }

        return $this->one($qb, $parameters);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findDrafts(QueryBuilder $qb = null, $parameters = null)
    {
        if (null == $qb) {
            $qb = $this->createSelectQuery();
        }

        return $this->all($qb, $parameters);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @param  Array                      $parameters
     * @return int
     */
    public function countDrafts(QueryBuilder $qb = null, $parameters = null)
    {
        if (null == $qb) {
            $qb = $this->createCountQuery();
        }

        if (null == $parameters) {
            $parameters = array();
        }

        $qb->setParameters($parameters);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Draft                 $draft
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    public function persistDraft(Draft $draft)
    {
        $this->persist($draft)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Draft                 $draft
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    public function updateDraft(Draft $draft)
    {
        $this->persist($draft)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNForum\ForumBundle\Entity\Draft                 $draft
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    public function deleteDraft(Draft $draft)
    {
        $this->remove($draft)->flush();

        return $this;
    }
}
