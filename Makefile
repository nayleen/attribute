ci: csdiff static phpunit cleanup

cleanup:
	@docker-compose down -v 2>/dev/null

composer:
	@docker-compose run --rm php composer validate 2>/dev/null
	@docker-compose run --rm php composer install --quiet --no-cache 2>/dev/null

coverage: composer
	@docker-compose run --rm -eXDEBUG_MODE=coverage php php vendor/bin/phpunit --coverage-html=coverage/ 2>/dev/null

csdiff: composer
	@docker-compose run --rm php php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose 2>/dev/null

csfix: composer
	@docker-compose run --rm php php vendor/bin/php-cs-fixer fix 2>/dev/null

normalize:
	@docker-compose run --rm php composer normalize --quiet 2>/dev/null
	@docker-compose run --rm php php vendor/bin/php-cs-fixer fix 2>/dev/null

phpunit: composer
	@docker-compose run --rm php php vendor/bin/phpunit 2>/dev/null

static: composer
	@docker-compose run --rm php php vendor/bin/phpstan 2>/dev/null
