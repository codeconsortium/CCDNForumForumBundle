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

use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class FloodControl
{
	/**
	 *
	 * @access protected
	 * @var \Symfony\Component\HttpFoundation\Session\Session $session
	 */
	protected $session;
	
	/**
	 *
	 * @access protected
	 * @var int $postLimit
	 */
	protected $postLimit;

	/**
	 *
	 * @access protected
	 * @var int $blockTimeInMinutes
	 */	
	protected $blockTimeInMinutes;
	
	/**
	 *
	 * @access public
	 * @param \Symfony\Component\HttpFoundation\Session\Session $session
	 * @param int $postLimit
	 * @param int $blockTimeInMinutes
	 */
	public function __construct(Session $session, $postLimit, $blockTimeInMinutes)
	{
		$this->session = $session;
		
		if ( ! $this->session->has('flood_control_forum_post_count'))
		{
			$this->session->set('flood_control_forum_post_count', array());
		}
		
		$this->postLimit = $postLimit;
		$this->blockTimeInMinutes = $blockTimeInMinutes;
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
        $timeLimit = new \DateTime('-' . $this->blockTimeInMinutes . ' minutes');

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

            if (count($freshenedAttempts) > $this->postLimit) {
				return true;
			}
        }		

		return false;
	}
}