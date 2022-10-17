ci: cs-diff psalm phpunit cleanup

cleanup:
	@docker-compose down -v 2>/dev/null

composer:
	@docker-compose run --rm composer validate 2>/dev/null
	@docker-compose run --rm composer install --quiet --no-cache --ignore-platform-reqs 2>/dev/null

cs-diff: composer
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose 2>/dev/null

cs-fix: composer
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix 2>/dev/null

normalize:
	@docker-compose run --rm composer normalize --quiet 2>/dev/null
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix 2>/dev/null

psalm: composer
	@docker-compose run --rm php vendor/bin/psalm --show-info=true 2>/dev/null

phpunit: composer
	@docker-compose run --rm php -dxdebug.mode=coverage vendor/bin/phpunit 2>/dev/null
