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

namespace CCDNForum\ForumBundle\Manager;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\Bag\ManagerBagInterface;

use CCDNForum\ForumBundle\Gateway\BaseGatewayInterface;

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
 * @abstract
 *
 */
abstract class BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access protected
     * @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    protected $doctrine;

    /**
     *
     * @access protected
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $em;

    /**
     *
     * @access protected
     * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    protected $securityContext;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Manager\BaseManagerInterface $gateway
     */
    protected $gateway;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Manager\Bag\ManagerBagInterface $managerBag
     */
    protected $managerBag;

    /**
     *
     * @access public
     * @param \Doctrine\Bundle\DoctrineBundle\Registry               $doctrine
     * @param \Symfony\Component\Security\Core\SecurityContext       $securityContext
     * @param \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface    $gateway
     * @param \CCDNForum\ForumBundle\Manager\Bag\ManagerBagInterface $managerBag
     */
    public function __construct(Registry $doctrine, SecurityContext $securityContext, BaseGatewayInterface $gateway, ManagerBagInterface $managerBag)
    {
        $this->doctrine = $doctrine;

        $this->em = $doctrine->getEntityManager();

        $this->securityContext = $securityContext;

        $this->gateway = $gateway;

        $this->managerBag = $managerBag;
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
     * @return \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     *
     * @access public
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->gateway->getQueryBuilder();
    }

    /**
     *
     * @access public
     * @param  string                                       $column  = null
     * @param  Array                                        $aliases = null
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function createCountQuery($column = null, Array $aliases = null)
    {
        return $this->gateway->createCountQuery($column, $aliases);
    }

    /**
     *
     * @access public
     * @param  Array                                        $aliases = null
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function createSelectQuery(Array $aliases = null)
    {
        return $this->gateway->createSelectQuery($aliases);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function one(QueryBuilder $qb)
    {
        return $this->gateway->one($qb);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function all(QueryBuilder $qb)
    {
        return $this->gateway->all($qb);
    }

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function persist($entity)
    {
        $this->em->persist($entity);

        return $this;
    }

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function remove($entity)
    {
        $this->em->remove($entity);

        return $this;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function flush()
    {
        $this->em->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNForum\ForumBundle\Manager\BaseManagerInterface
     */
    public function refresh($entity)
    {
        $this->em->refresh($entity);

        return $this;
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getTopicsPerPageOnSubscriptions()
    {
        return $this->managerBag->getTopicsPerPageOnSubscriptions();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getTopicsPerPageOnBoards()
    {
        return $this->managerBag->getTopicsPerPageOnBoards();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getPostsPerPageOnTopics()
    {
        return $this->managerBag->getPostsPerPageOnTopics();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getDraftsPerPageOnDrafts()
    {
        return $this->managerBag->getDraftsPerPageOnDrafts();
    }
}
