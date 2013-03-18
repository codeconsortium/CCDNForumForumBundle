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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

use CCDNForum\ForumBundle\Manager\BaseManagerInterface;
use CCDNForum\ForumBundle\Manager\BaseManager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CategoryManager extends BaseManager implements BaseManagerInterface
{
	/**
	 *
	 * @access public
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function findAllWithBoards()
	{
		$qb = $this->createSelectQuery(array('c', 'b', 'lp', 't', 'lp_author'));

		$qb = $this->joinToQueryBoardsAndLastPost($qb);
		
		$categories = $this->gateway->findCategories($qb);
		
		return $this->filterViewableCategoriesAndBoards($categories);
	}
	
	/**
	 *
	 * @access public
	 * @param int $categoryId
	 * @return \CCDNForum\ForumBundle\Entity\Category
	 */	
	public function findOneByIdWithBoards($categoryId)
	{
		if (null == $categoryId || ! is_numeric($categoryId) || $categoryId == 0) {
			throw new \Exception('Category id "' . $categoryId . '" is invalid!');
		}
		
		$qb = $this->createSelectQuery(array('c', 'b', 'lp', 't', 'lp_author'));
		
		$qb = $this->joinToQueryBoardsAndLastPost($qb);
		
		$qb->where('c.id = :categoryId');
		
		$categories = $this->gateway->findCategory($qb, array(':categoryId' => $categoryId));
		
		return $this->filterViewableCategoriesAndBoards($categories);
	}
	
	/**
	 *
	 * @access protected
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @return \Doctrine\ORM\QueryBuilder
	 */	
	protected function joinToQueryBoardsAndLastPost(QueryBuilder $qb)
	{
		$qb
			->leftjoin('c.boards', 'b')
			->leftJoin('b.lastPost', 'lp')
			->leftJoin('lp.topic', 't')
			->leftJoin('lp.createdBy', 'lp_author');
			
		return $qb;
	}

	/**
	 *
	 * @access public
	 * @param Array $categories
	 * @return Array
	 */
    public function filterViewableCategoriesAndBoards($categories)
    {
        foreach ($categories as $categoryKey => $category) {
            $boards = $category->getBoards();

            foreach($boards as $board) {
                if (! $board->isAuthorisedToRead($this->securityContext)) {
                    $categories[$categoryKey]->removeBoard($board);
                }
            }
        }

        return $categories;
    }
}