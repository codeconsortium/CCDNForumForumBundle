<?php

namespace CCDNForum\ForumBundle\Model;

interface ForumUserInterface
{
    public function getId();

    public function getEmail();

    public function setEmail($email);
}
