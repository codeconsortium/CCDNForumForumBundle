<?php

/*
 * This file is part of the CCDN ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/> 
 * 
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\Entity\Manager;

use CCDNComponent\CommonBundle\Entity\Manager\EntityManagerInterface;
use CCDNComponent\CommonBundle\Entity\Manager\BaseManager;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class FlagManager extends BaseManager implements EntityManagerInterface
{
	
	
	
	/**
	 *
	 * @access public
	 * @param $flag
	 * @return $this
	 */
	public function insert($flag)
	{
		// insert a new row
		$this->persist($flag);
		
		return $this;
	}	
	
	
	
	/**
	 *
	 * @access public
	 * @param $flag
	 * @return $this
	 */
	public function update($flag)
	{
		// update a record
		$this->persist($flag);
		
		return $this;
	}
	
}