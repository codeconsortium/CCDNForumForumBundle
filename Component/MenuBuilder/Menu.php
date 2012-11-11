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

namespace CCDNForum\ForumBundle\Component\MenuBuilder;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class Menu
{

    /**
     *      
	 * @access public
	 * @return array
     */
    public function buildMenu($builder)
    {
		$builder
			->arrayNode('layout')
				->arrayNode('header')
					->arrayNode('top')
						->linkNode('ccdn_forum_forum.layout.header_links.forum', 'ccdn_forum_forum_index', array(
							'label_trans_bundle' => 'CCDNForumForumBundle',
							'class'	=> 'nav_link',
						))->end()
					->end()
				->end()
				->arrayNode('footer')
					->arrayNode('sections')
						->htmlNode('sections_header', '<div class="footer_block"><h6>Sections</h6>')->end()
						->unorderedListNode('sections')
							->linkNode('ccdn_forum_forum.layout.header_links.forum', 'ccdn_forum_forum_index', array(
								'label_trans_bundle' => 'CCDNForumForumBundle'
							))->end()
						->end()
						->htmlNode('sections_footer', '</div>')->end()
					->end()
				->end()
			->end();	
    }

}
