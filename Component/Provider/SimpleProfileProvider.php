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

        if (null !== $user) {
            $profile->setUsername($user->getUsername());
        } else {
            $profile->setUsername('Guest');
        }

        $asset = $this->container->get('templating.helper.assets');
        $profile->setAvatarFallback($asset->getUrl('bundles/ccdnforumforum/images/default_avatar/anonymous_avatar.gif'));

        if (null !== $user) {
            $roles = $user->getRoles();

            foreach ($roles as $role) {
                switch ($role) {
                    case 'ROLE_SUPER_ADMIN':
                    case 'ROLE_ADMIN':
                    case 'ROLE_MODERATOR':
                        $badges = array('red', 'Staff');
                        break;
                    case 'ROLE_USER':
                        $badges = array('blue', 'Member');
                        break;
                    default:
                        $badges = array('grey', 'Anonymous');
                        break;
                }
            }

            $profile->setRoleBadges(array($badges));
        }

        return $profile;
    }
}
