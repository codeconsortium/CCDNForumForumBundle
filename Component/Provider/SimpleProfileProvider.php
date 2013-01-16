<?php
namespace CCDNForum\ForumBundle\Component\Provider;

class SimpleProfileProvider implements ProfileProviderInterface
{
    public function transform($user)
    {
        $profile = new Profile();

        $profile->setAvatar('');
        $profile->setPlainName($user->getUsername());
        $profile->setSignature('');

        return $profile;
    }
}
