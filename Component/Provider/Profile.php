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

namespace CCDNForum\ForumBundle\Component\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class Profile implements ProfileInterface
{
    /** @var \Symfony\Component\Security\Core\User\UserInterface $user */
    protected $user;

    /** @var string $profilePath */
    protected $profilePath;

    /** @var string $username */
    protected $username;

    /** @var string $avatar */
    protected $avatar;

    /** @var string $avatarFallback */
    protected $avatarFallback;

    /** @var string $signature */
    protected $signature;

    /** @var array $roleBadges */
    protected $roleBadges;

    static $badgeColours = array(
        'grey' => 'label',
        'green' => 'label-success',
        'orange' => 'label-warning',
        'red' => 'label-important',
        'blue' => 'label-info',
        'black' => 'label-inverse',
    );


    public function __construct()
    {
        $this->roleBadges = array();
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return Profile $this
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getProfilePath()
    {
        return $this->username;
    }

    /**
     * @param $profilePath
     * @return Profile $this
     */
    public function setProfilePath($profilePath)
    {
        $this->profilePath = $profilePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Profile $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param int $scaleX
     * @param int $scaleY
     * @param int $roundedPX
     * @param int $borderPX
     * @param int $paddingPX
     * @return string
     */
    public function renderAvatar($scaleX = 100, $scaleY = 100, $roundedPX = 0, $borderPX = 0, $paddingPX = 0)
    {
        $scaleX     = ($scaleX) ? $scaleX : 100;
        $scaleY     = ($scaleY) ? $scaleY : 100;
        $roundedPX  = ($roundedPX) ? 'border-radius:' . $roundedPX . 'px;' : '';
        $borderPX   = ($borderPX) ? 'border:' . $borderPX . 'px solid #ddd;': '';
        $paddingPX  = ($paddingPX) ? 'padding:' . $paddingPX . 'px;': '';

        return '<img style="' . $borderPX . $roundedPX . $paddingPX . '" class="avatar" width="' . $scaleX . '" height="' . $scaleY . '" src="' . $this->getAvatarUrl() . '" alt="avatar" />';
    }

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatar ?: $this->avatarFallback;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return Profile $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarFallback()
    {
        return $this->avatarFallback;
    }

    /**
     * @param string $avatar
     * @return Profile $this
     */
    public function setAvatarFallback($avatar)
    {
        $this->avatarFallback = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     * @return Profile $this
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoleBadges()
    {
        return $this->roleBadges;
    }

    /**
     * @param array $badges
     * @return Profile $this
     */
    public function setRoleBadges(array $badges = null)
    {
        $this->roleBadges = $badges;

        return $this;
    }

    /**
     * @param array $badges
     * @return Profile $this
     */
    public function addRoleBadges(array $badges)
    {
        foreach ($badges as $badge) {
            $this->roleBadges[] = $badge;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function renderRoleBadges()
    {
        $html = '';

        if ( ! is_array($this->roleBadges) && count($this->roleBadges) < 1) {
            return '';
        }

        foreach ($this->roleBadges as $badge) {
            if (! is_array($badge)) {
                continue;
            }

            $colour = (array_key_exists($badge[0], self::$badgeColours) ? self::$badgeColours[$badge[0]] : 'label');
            $message = $badge[1];

            $html .= '<span class="label ' . $colour . '">' . $message . '</span>';
        }

        return $html;
    }
}
