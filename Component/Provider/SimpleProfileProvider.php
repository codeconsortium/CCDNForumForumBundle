<?php
namespace CCDNForum\ForumBundle\Component\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class SimpleProfileProvider implements ProfileProviderInterface
{
    public function transform(UserInterface $user = null)
    {
        $profile = new Profile();

        if (null !== $user) {
            $profile->setAvatar('');
            $profile->setUsername($user->getUsername());
            $profile->setSignature('');
        } else {
            $profile->setAvatar('');
            $profile->setUsername('Guest');
            $profile->setSignature('');
        }

        return $profile;
    }
}
