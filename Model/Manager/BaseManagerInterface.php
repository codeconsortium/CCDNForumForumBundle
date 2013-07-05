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

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Manager\Bag\ManagerBagInterface;
use CCDNForum\ForumBundle\Model\Gateway\BaseGatewayInterface;

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
interface BaseManagerInterface
{
    /**
     *
     * @access public
     * @param \Doctrine\Bundle\DoctrineBundle\Registry               $doctrine
     * @param \Symfony\Component\Security\Core\SecurityContext       $securityContext
     * @param \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface    $gateway
     * @param \CCDNForum\ForumBundle\Manager\Bag\ManagerBagInterface $managerBag
     */
    public function __construct(Registry $doctrine, SecurityContext $securityContext, BaseGatewayInterface $gateway, ManagerBagInterface $managerBag);

    /**
     *
     * @access public
     * @param  string $role
     * @return bool
     */
    public function isGranted($role);

    /**
     *
     * @access public
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser();

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    public function getGateway();

    /**
     *
     * @access public
     * @param  Array                                        $aliases = null
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function createSelectQuery(Array $aliases = null);

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function one(QueryBuilder $qb);

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function all(QueryBuilder $qb);

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function persist($entity);

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function remove($entity);

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function flush();

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function refresh($entity);
}
