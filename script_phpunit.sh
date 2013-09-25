#!/usr/bin/env sh

php ./Tests/Functional/app/console --env=test doctrine:database:drop --force
php ./Tests/Functional/app/console --env=test doctrine:database:create
php ./Tests/Functional/app/console --env=test doctrine:schema:update --force
php ./Tests/Functional/app/console --env=test assets:install web

phpunit -c ./ --testdox