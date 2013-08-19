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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Model\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Model\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\Forum;

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
class ForumManager extends BaseManager implements BaseManagerInterface
{
	/**
	 * 
	 * @access public
	 * @param  \CCDNForum\ForumBundle\Entity\Forum $forum
	 */
	public function saveNewForum(Forum $forum)
	{
        // insert a new row
        $this->persist($forum)->flush();

		$this->refresh($forum);
		
        return $this;
	}

	/**
	 * 
	 * @access public
	 * @param  \CCDNForum\ForumBundle\Entity\Forum $forum
	 */
	public function updateForum(Forum $forum)
	{
        $this->persist($forum)->flush();

		$this->refresh($forum);
		
        return $this;
	}

	/**
	 * 
	 * @access public
	 * @param  \CCDNForum\ForumBundle\Entity\Forum $forum
	 */
	public function deleteForum(Forum $forum)
	{
		// If we do not refresh the forum, AND we have reassigned the categories to null, 
		// then its lazy-loaded categories are dirty, as the categories in memory will
		// still have the old category id set. Removing the forum will cascade into deleting
		// categories aswell, even though in the db the relation has been set to null.
		$this->refresh($forum);
		
		$this->remove($forum)->flush();
		
		return $this;
	}

	/**
	 * 
	 * @access public
	 * @param  \Doctrine\Common\Collections\ArrayCollection $categories
	 * @param  \CCDNForum\ForumBundle\Entity\Forum          $forum
	 */
	public function reassignCategoriesToForum(ArrayCollection $categories, Forum $forum = null)
	{
		foreach ($categories as $category) {
			$category->setForum($forum);
			
			$this->persist($category);
		}

		$this->flush();
		
		return $this;
	}
}
