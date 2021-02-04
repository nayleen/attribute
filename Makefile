csdiff:
	php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

csfix:
	php vendor/bin/php-cs-fixer fix

psalm:
	php vendor/bin/psalm

tests:
	php vendor/bin/phpunit

vendor: composer.json
	composer update
