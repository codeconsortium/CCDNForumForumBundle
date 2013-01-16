<?php
namespace CCDNForum\ForumBundle\Component\Provider;

class ProfileProvider implements ProfileProviderInterface
{
    public function transform($user)
    {
        $profile = new Profile();

        $profile->setAvatar('foo');
        $profile->setPlainName('foo');
        $profile->setSignature('foo');

        return $profile;
    }
}
