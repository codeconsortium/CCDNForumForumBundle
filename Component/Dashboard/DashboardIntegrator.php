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

namespace CCDNForum\ForumBundle\Component\Dashboard;

use CCDNComponent\DashboardBundle\Component\Integrator\Model\BuilderInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 2.0
 */
class DashboardIntegrator
{
    /**
	 * 
	 * @access public
     * @param CCDNComponent\DashboardBundle\Component\Integrator\Model\BuilderInterface $builder
     */
    public function build(BuilderInterface $builder)
    {
		$builder
			->addCategory('community')
				->setLabel('ccdn_forum_forum.dashboard.categories.forum', array(), 'CCDNForumForumBundle')
				->addPages()
					->addPage('forum')
						->setLabel('ccdn_forum_forum.dashboard.pages.forum', array(), 'CCDNForumForumBundle')
					->end()
				->end()
				->addLinks()	
					->addLink('forum_index')
						->setRoute('ccdn_forum_forum_index')
						->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_discussion.png')
						->setLabel('ccdn_forum_forum.title.category.index', array(), 'CCDNForumForumBundle')
					->end()
					->addLink('forum_drafts')
						->setAuthRole('ROLE_USER')
						->setRoute('ccdn_forum_forum_draft_list')
						->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_pen.png')
						->setLabel('ccdn_forum_forum.title.drafts.show', array(), 'CCDNForumForumBundle')
					->end()
					->addLink('forum_subscriptions')
						->setAuthRole('ROLE_USER')
						->setRoute('ccdn_forum_forum_subscription_list')
						->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_bookmark.png')
						->setLabel('ccdn_forum_forum.title.subscriptions.show', array(), 'CCDNForumForumBundle')
					->end()
				->end()
			->end()
		;
    }
}
