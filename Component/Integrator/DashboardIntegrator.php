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

namespace CCDNForum\ForumBundle\Component\Integrator;

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
     * @param  $builder
     */
    public function build($builder)
    {
        $builder
            ->addCategory('forum')
                ->setLabel('dashboard.categories.forum', array(), 'CCDNForumForumBundle')
                ->addPages()
                    ->addPage('community')
                        ->setLabel('dashboard.pages.community', array(), 'CCDNForumForumBundle')
                    ->end()
                    ->addPage('forum')
                        ->setLabel('dashboard.pages.forum', array(), 'CCDNForumForumBundle')
                    ->end()
                ->end()
                ->addLinks()
                    ->addLink('forum_index')
                        ->setRoute('ccdn_forum_user_category_index')
                        ->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_discussion.png')
                        ->setLabel('dashboard.links.forum', array(), 'CCDNForumForumBundle')
                    ->end()
                    ->addLink('forum_subscriptions')
                        ->setAuthRole('ROLE_USER')
                        ->setRoute('ccdn_forum_user_subscription_index', array('forumName' => '~'))
                        ->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_bookmark.png')
                        ->setLabel('dashboard.links.subscriptions', array(), 'CCDNForumForumBundle')
                    ->end()
                ->end()
            ->end()
        ;
    }
}
