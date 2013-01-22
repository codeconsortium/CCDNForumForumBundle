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

namespace CCDNForum\ForumBundle\Component\Provider\Profile;

use Symfony\Component\Security\Core\User\UserInterface;

interface ProfileInterface
{
    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface $user
     */
    public function getUser();

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return Profile $this
     */
    public function setUser(UserInterface $user = null);

    /**
     * @return string
     */
    public function getProfilePath();

    /**
     * @param $profilePath
     * @return Profile $this
     */
    public function setProfilePath($profilePath);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     * @return Profile $this
     */
    public function setUsername($username);

    /**
     * @param int $scaleX
     * @param int $scaleY
     * @param int $roundedPX
     * @param int $borderPX
     * @param int $paddingPX
     * @return string
     */
    public function renderAvatar($scaleX = 100, $scaleY = 100, $roundedPX = 0, $borderPX = 0, $paddingPX = 0);

    /**
     * @return string
     */
    public function getAvatarUrl();

    /**
     * @return string
     */
    public function getAvatar();

    /**
     * @param string $avatar
     * @return Profile $this
     */
    public function setAvatar($avatar);

    /**
     * @return string
     */
    public function getAvatarFallback();

    /**
     * @param string $avatar
     * @return Profile $this
     */
    public function setAvatarFallback($avatar);

    /**
     * @return string
     */
    public function getSignature();

    /**
     * @param string $signature
     * @return Profile $this
     */
    public function setSignature($signature);

    /**
     * @return array
     */
    public function getRoleBadges();

    /**
     * @param array $badges
     * @return Profile $this
     */
    public function setRoleBadges(array $badges = null);

    /**
     * @param array $badges
     * @return Profile $this
     */
    public function addRoleBadges(array $badges);

    /**
     * @return Profile $this
     */
    public function autoSetRoleBadge();

    /**
     * @return string
     */
    public function renderRoleBadges();

}
