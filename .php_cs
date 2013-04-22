<?php

use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('LICENSE')
    ->notName('README.md')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('phpunit.xml*')
    ->notName('*.phar')
    ->exclude(
        array(
            'vendor',
            'Resources/meta',
            'Resources/doc',
            'Resources/public',
            'Tests',
        )
    )
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->fixers(FixerInterface::ALL_LEVEL)
    ->finder($finder)
;


