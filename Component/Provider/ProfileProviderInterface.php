<?php
namespace CCDNForum\ForumBundle\Component\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

interface ProfileProviderInterface
{
    public function transform(UserInterface $user = null);
}
