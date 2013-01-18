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

interface ProfileInterface
{
    public function getProfilePath();

    public function setProfilePath($profilePath);

    public function getSignature();

    public function setSignature($signature);

    public function getUsername();

    public function setUsername($username);

    public function renderAvatar($scaleX = 100, $scaleY = 100, $roundedPX = 0, $borderPX = 0, $paddingPX = 0);

    public function getAvatarUrl();

    public function getAvatar();

    public function setAvatar($avatar);

}
