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
