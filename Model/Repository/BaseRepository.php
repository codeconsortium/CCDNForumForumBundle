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

namespace CCDNForum\ForumBundle\Model\Repository;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Repository\BaseRepositoryInterface;

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
 * @abstract
 *
 */
abstract class BaseRepository implements BaseRepositoryInterface
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
     * @var \CCDNForum\ForumBundle\Model\Gateway\BaseGatewayInterface $gateway
     */
    protected $gateway;

	protected $model;
	protected $utilBag;
	
    /**
     *
     * @access public
     * @param \Doctrine\Bundle\DoctrineBundle\Registry               $doctrine
     * @param \Symfony\Component\Security\Core\SecurityContext       $securityContext
     * @param \CCDNForum\ForumBundle\Gateway\BaseGatewayInterface    $gateway
     * @param \CCDNForum\ForumBundle\Manager\Bag\ManagerBagInterface $managerBag
     */
    public function __construct(Registry $doctrine, SecurityContext $securityContext, BaseGatewayInterface $gateway, $utilBag)
    {
        $this->doctrine = $doctrine;

        $this->em = $doctrine->getEntityManager();

        $this->securityContext = $securityContext;

        $this->gateway = $gateway;
		
		$this->utilBag = $utilBag;
    }

	public function setModel($model)
	{
		$this->model = $model;
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
     * @return int
     */
    public function getTopicsPerPageOnSubscriptions()
    {
        return $this->utilBag->getPaginationUtil()->getTopicsPerPageOnSubscriptions();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getTopicsPerPageOnBoards()
    {
        return $this->utilBag->getPaginationUtil()->getTopicsPerPageOnBoards();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getPostsPerPageOnTopics()
    {
        return $this->utilBag->getPaginationUtil()->getPostsPerPageOnTopics();
    }
}
