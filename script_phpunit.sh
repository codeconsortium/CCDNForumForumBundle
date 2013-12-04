#!/usr/bin/env sh

rm -rRf ./Tests/Functional/app/cache/*
rm -rRf ./Tests/Functional/app/logs/*

composer install --dev

php ./Tests/Functional/app/console --env=test doctrine:database:drop --force
php ./Tests/Functional/app/console --env=test doctrine:database:create
php ./Tests/Functional/app/console --env=test doctrine:schema:create
php ./Tests/Functional/app/console --env=test doctrine:schema:update --force
	
phpunit -c ./ --testdox
