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
     * @param  int                                    $categoryId
     * @return \CCDNForum\ForumBundle\Entity\Category
     */
    public function findOneById($categoryId)
    {
        if (null == $categoryId || ! is_numeric($categoryId) || $categoryId == 0) {
            throw new \Exception('Category id "' . $categoryId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('c'));

        $qb->where('c.id = :categoryId');

        $category = $this->gateway->findCategory($qb, array(':categoryId' => $categoryId));

        $categories = $this->filterViewableCategoriesAndBoards($category);

        if (count($categories)) {
            return $categories[0];
        } else {
            return null;
        }
    }
}
