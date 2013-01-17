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

        $asset = $this->container->get('templating.helper.assets');

        $profile->setAvatar($asset->getUrl('bundles/ccdnforumforum/images/default_avatar/anonymous_avatar.gif'));

        if (null !== $user) {
            $profile->setUsername($user->getUsername());
        } else {
            $profile->setUsername('Guest');
        }

        return $profile;
    }
}
