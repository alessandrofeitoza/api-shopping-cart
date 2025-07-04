bash:
	docker compose exec -u user php bash

init: start
	cp .env.example .env
	docker compose exec php bash -c "composer install"
	docker compose exec php bash -c "php artisan key:generate"
	docker compose exec php bash -c "php artisan migrate"
	docker compose exec php bash -c "php artisan migrate:"
start:
	docker compose up -d

restart:
	docker compose down && docker compose up -d

stop:
	docker compose stop

down:
	docker compose down

mysql:
	docker compose exec mysql mysql --user=root --password=root

style:
	docker compose exec -T -e PHP_CS_FIXER_IGNORE_ENV=1 php bash -c "php vendor/bin/php-cs-fixer fix --dry-run --diff -vvv"
