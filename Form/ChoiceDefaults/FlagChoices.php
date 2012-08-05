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

namespace CCDNForum\ForumBundle\Form\ChoiceDefaults;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class FlagChoices //extends ContainerAware
{

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     * This is used for various forms / views needing these choices for the reason field.
     *
     * @access protected
     */
    protected $reasons = array(
        0 => 'flag.reasons.spam',
        1 => 'flag.reasons.age_inappropriate_content',
        2 => 'flag.reasons.aggressive_attitude',
        3 => 'flag.reasons.hate_speech',
        4 => 'flag.reasons.condoning_provoking_violence',
        5 => 'flag.reasons.offensive_content',
    );

    /**
     *
     * This is used for various forms / views needing these choices for the reason field.
     *
     * @access protected
     */
    protected $statuses = array(
        0 => 'flag.statuses.open',
        1 => 'flag.statuses.being_investigated',
        2 => 'flag.statuses.closed_resolved',
        3 => 'flag.statuses.closed_unresolvable',
        4 => 'flag.statuses.closed_no_issue',
        5 => 'flag.statuses.closed_pending_review',
    );

    /**
     *
     * @access public
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @access public
     * @return Array()
     */
    public function getReasonCodes()
    {
        return $this->translateCodes($this->reasons);
    }

    /**
     *
     * @access public
     * @return Array()
     */
    public function getStatusCodes()
    {
        return $this->translateCodes($this->statuses);
    }

    /**
     *
     * @access protected
     * @param  Array() $codes
     * @return Array() $codes
     */
    protected function translateCodes($codes)
    {
        $translator = $this->container->get('translator');

        foreach ($codes as $index => $code) {
            $codes[$index] = $translator->trans($codes[$index], array(), 'CCDNForumForumBundle');
        }

        return $codes;
    }

}
