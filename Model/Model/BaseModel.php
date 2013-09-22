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

use CCDNForum\ForumBundle\Model\Model\BaseModel;
use CCDNForum\ForumBundle\Model\Model\Bag\ModelBagInterface;

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
 */
abstract class BaseModel
{
    protected $repository;

    protected $manager;

    protected $modelBag;

    public function __construct($repository, $manager, ModelBagInterface $modelBag)
    {
        $repository->setModel($this);
        $this->repository = $repository;

        $manager->setModel($this);
        $this->manager = $manager;

        $this->modelBag = $modelBag;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getManager()
    {
        return $this->manager;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Model\Model\Bag\ModelBagInterface
     */
    public function getModelBag()
    {
        return $this->modelBag;
    }
}
