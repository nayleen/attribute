csdiff: .php_cs.cache
	php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

csfix: .php_cs.cache
	php vendor/bin/php-cs-fixer fix

psalm:
	php vendor/bin/psalm

tests: .phpunit.result.cache
	php vendor/bin/phpunit

vendor: composer.json
	composer update
