<?php
namespace CCDNForum\ForumBundle\Component\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class SimpleProfileProvider implements ProfileProviderInterface
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function transform(UserInterface $user = null)
    {
        $profile = new Profile();

        $asset = $this->container->get('templating.helper.asset');

        if (null !== $user) {
            $profile->setUsername($user->getUsername());
            $profile->setAvatar($asset->getUrl('ccdnforumforum/images/default_avatar/anonymous_avatar.gif'));
        } else {
            $profile->setUsername('Guest');
            $profile->setAvatar($asset->getUrl('ccdnforumforum/images/default_avatar/anonymous_avatar.gif'));
        }

        return $profile;
    }
}
