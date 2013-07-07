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
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
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
                ->setLabel('dashboard.categories.forum', array(), 'CCDNForumForumBundle')
                ->addPages()
                    ->addPage('forum')
                        ->setLabel('dashboard.pages.forum', array(), 'CCDNForumForumBundle')
                    ->end()
                ->end()
                ->addLinks()
                    ->addLink('forum_index')
                        ->setRoute('ccdn_forum_user_category_index')
                        ->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_discussion.png')
                        ->setLabel('title.category.index', array(), 'CCDNForumForumBundle')
                    ->end()
                    ->addLink('forum_drafts')
                        ->setAuthRole('ROLE_USER')
                        ->setRoute('ccdn_forum_user_draft_list')
                        ->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_pen.png')
                        ->setLabel('title.drafts.show', array(), 'CCDNForumForumBundle')
                    ->end()
                    ->addLink('forum_subscriptions')
                        ->setAuthRole('ROLE_USER')
                        ->setRoute('ccdn_forum_user_subscription_list')
                        ->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_bookmark.png')
                        ->setLabel('title.subscriptions.show', array(), 'CCDNForumForumBundle')
                    ->end()
                ->end()
            ->end()
        ;
    }
}
