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

namespace CCDNForum\ForumBundle\Component\TwigExtension;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CreateProfileExtension extends \Twig_Extension
{
    /**
     *
     * @access protected
     */
    protected $provider;

    /**
     *
     * @access public
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     *
     * @access public
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'create_profile' => new \Twig_Function_Method($this, 'createProfile'),
        );
    }

    /**
     * Return a profile object with necessary elements for showing user details on forum related stuff.
     *
     * @access public
     * @return Profile
     */
    public function createProfile($user)
    {
        return $this->provider->transform($user);
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'createProfile';
    }
}
