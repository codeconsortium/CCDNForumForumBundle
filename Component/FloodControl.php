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

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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
class FloodControl
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    protected $securityContext;

    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpFoundation\Session\Session $session
     */
    protected $session;

    /**
     *
     * @access protected
     * @var string $kernelEnv
     */
    protected $kernelEnv;

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
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     * @param \Symfony\Component\HttpFoundation\Session\Session         $session
     * @param string                                                    $kernelEnv
     * @param int                                                       $postLimit
     * @param int                                                       $blockTimeInMinutes
     */
    public function __construct(SecurityContextInterface $securityContext, Session $session, $kernelEnv, $postLimit, $blockTimeInMinutes)
    {
        $this->securityContext = $securityContext;
        $this->session = $session;
        $this->kernelEnv = $kernelEnv;

        if (! $this->session->has('flood_control_forum_post_count')) {
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
        if (! $this->securityContext->isGranted('ROLE_MODERATOR') || $this->kernelEnv != 'prod') {
            $postCount = $this->session->get('flood_control_forum_post_count');

            $postCount[] = new \DateTime('now');

            $this->session->set('flood_control_forum_post_count', $postCount);
        }
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function isFlooded()
    {
        if ($this->postLimit < 1 || ! $this->securityContext->isGranted('ROLE_MODERATOR') || $this->kernelEnv != 'prod') {
            return false;
        }

        if ($this->session->has('flood_control_forum_post_count')) {
            $attempts = $this->session->get('flood_control_forum_post_count');

            // Iterate over attempts and only reveal attempts that fall within the $timeLimit.
            $freshenedAttempts = array();

            $timeLimit = new \DateTime('-' . $this->blockTimeInMinutes . ' minutes');
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
