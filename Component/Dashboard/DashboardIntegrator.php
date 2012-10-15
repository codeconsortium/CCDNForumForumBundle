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
     * Structure of $resources
     * 	[DASHBOARD_PAGE <string>]
     * 		[CATEGORY_NAME <string>]
     *			[ROUTE_FOR_LINK <string>]
     *				[AUTH <string>] (optional)
     *				[URL_LINK <string>]
     *				[URL_NAME <string>]
	 * 
	 * @access public
	 * @return array $resources
     */
    public function getResources()
    {
        $resources = array(
            'user' => array(
                'Community' => array(
                    'ccdn_forum_forum_index' => array('name' => 'Forum', 'icon' => $this->basePath . '/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_discussion.png'),
                    'ccdn_forum_forum_draft_list' => array('auth' => 'ROLE_USER', 'name' => 'Forum Drafts', 'icon' => $this->basePath . '/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_pen.png'),
                    'ccdn_forum_forum_subscription_list' => array('auth' => 'ROLE_USER', 'name' => 'Topic Subscriptions', 'icon' => $this->basePath . '/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_bookmark.png'),
                ),
            ),

        );

        return $resources;
    }

}
