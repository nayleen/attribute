.PHONY: ci csdiff csfix tests

ci: csdiff psalm tests

csdiff: vendor
	php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

csfix: vendor
	php vendor/bin/php-cs-fixer fix

psalm: vendor
	php vendor/bin/psalm

tests: vendor
	php vendor/bin/phpunit --testdox

vendor: composer.json
	composer validate
	composer install --quiet
