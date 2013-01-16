<?php
namespace CCDNForum\ForumBundle\Component\Provider;

class Profile
{
    protected $plainName;
    protected $avatar;
    protected $signature;

    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function getPlainName()
    {
        return $this->plainName;
    }

    public function setPlainName($plainName)
    {
        $this->plainName = $plainName;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
