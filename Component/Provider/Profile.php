<?php
namespace CCDNForum\ForumBundle\Component\Provider;

class Profile
{
    protected $profilePath;
    protected $username;
    protected $avatar;
    protected $signature;


    public function getProfilePath()
    {
        return $this->username;
    }

    public function setProfilePath($profilePath)
    {
        $this->profilePath = $profilePath;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function renderAvatar($scaleX = 100, $scaleY = 100, $roundedPX = 0, $borderPX = 0, $paddingPX = 0)
    {
        $scaleX     = ($scaleX) ? $scaleX : 100;
        $scaleY     = ($scaleY) ? $scaleY : 100;
        $roundedPX  = ($roundedPX) ? 'border-radius:' . $roundedPX . 'px;' : '';
        $borderPX   = ($borderPX) ? 'border:' . $borderPX . 'px solid #ddd;': '';
        $paddingPX  = ($paddingPX) ? 'padding:' . $paddingPX . 'px;': '';

        return '<img style="' . $borderPX . $roundedPX . $paddingPX . '" class="avatar" width="' . $scaleX . '" height="' . $scaleY . '" src="' . $this->getAvatarUrl() . '" alt="avatar" />';
    }

    public function getAvatarUrl()
    {
        return $this->avatar;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }
}
