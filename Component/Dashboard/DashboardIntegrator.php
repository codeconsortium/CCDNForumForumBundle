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

use CCDNComponent\DashboardBundle\Component\Integrator\BaseIntegrator;
use CCDNComponent\DashboardBundle\Component\Integrator\IntegratorInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class DashboardIntegrator extends BaseIntegrator implements IntegratorInterface
{

    /**
     *
     *
     * Structure of $resources
     * 	[DASHBOARD_PAGE String]
     * 		[CATEGORY_NAME String]
     *			[ROUTE_FOR_LINK String]
     *				[AUTH String] (optional)
     *				[URL_LINK String]
     *				[URL_NAME String]
	 * 
	 * @access public
	 * @return Array()
     */
    public function getResources()
    {
        $resources = array(
            'user' => array(
                'Community' => array(
                    'cc_forum_index' => array('name' => 'Forum', 'icon' => $this->basePath . '/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_discussion.png'),
                    'cc_forum_drafts_list' => array('auth' => 'ROLE_USER', 'name' => 'Forum Drafts', 'icon' => $this->basePath . '/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_pen.png'),
                    'cc_forum_subscriptions' => array('auth' => 'ROLE_USER', 'name' => 'Topic Subscriptions', 'icon' => $this->basePath . '/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_bookmark.png'),
                ),
            ),

        );

        return $resources;
    }

}
