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

use Doctrine\Common\Annotations\AnnotationRegistry;

if (!file_exists($file = __DIR__.'/../../vendor/autoload.php')) {
    throw new \RuntimeException('Install the dependencies to run the test suite.');
}

$loader = require $file;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

//if (!is_file($autoloadFile = __DIR__.'/app/autoload.php')) {
//    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
//}
//
//require $autoloadFile;




//if (!@include __DIR__ . '/../../../../../../app/autoload.php') {
//    die(<<<'EOT'
//You must set up the project dependencies, run the following commands:
//wget http://getcomposer.org/composer.phar
//php composer.phar install
//EOT
//    );
//}