#!/usr/bin/env sh

composer install --dev

php ./Tests/Functional/app/console --env=test doctrine:database:drop --force
php ./Tests/Functional/app/console --env=test doctrine:database:create
php ./Tests/Functional/app/console --env=test doctrine:schema:create
php ./Tests/Functional/app/console --env=test doctrine:schema:update --force

./vendor/behat/behat/bin/behat "@CCDNForumForumBundle"
