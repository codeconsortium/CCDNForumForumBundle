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

namespace CCDNForum\ForumBundle\Component;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class FloodControl extends ContainerAware
{

	/**
	 *
	 * @access protected
	 */
	protected $session;
	
	/**
	 *
	 * @access protected
	 */
	protected $container;
	
	/**
	 *
	 * @access public
	 * @param $session
	 */
	public function __construct($session, $container)
	{
		$this->session = $session;
		
		if ( ! $this->session->has('flood_control_forum_post_count'))
		{
			$this->session->set('flood_control_forum_post_count', array());
		}
		
		$this->container = $container;
	}
	
	/**
	 *
	 * @access public
	 */
	public function incrementCounter()
	{
		$postCount = $this->session->get('flood_control_forum_post_count');
		
		$postCount[] = new \DateTime('now');
		
		$this->session->set('flood_control_forum_post_count', $postCount);		
	}
	
	/**
	 *
	 * @access public
	 * @return bool
	 */
	public function isFlooded()
	{	
        $blockInMinutes = $this->container->getParameter('ccdn_forum_forum.topic.flood_control.block_for_minutes');

        $timeLimit = new \DateTime('-' . $blockInMinutes . ' minutes');

        // Only load from the db if the session is not found.
        if ($this->session->has('flood_control_forum_post_count')) {
            $attempts = $this->session->get('flood_control_forum_post_count');

            // Iterate over attempts and only reveal attempts that fall within the $timeLimit.
            $freshenedAttempts = array();

            $limit = $timeLimit->getTimestamp();

            foreach ($attempts as $attempt) {
                $date = $attempt->getTimestamp();

                if ($date > $limit) {
                    $freshenedAttempts[] = $attempt;
                }
            }

            if (count($freshenedAttempts) > $this->container->getParameter('ccdn_forum_forum.topic.flood_control.post_limit'))
			{
				return true;
			}
        }		

		return false;
	}
	
}
