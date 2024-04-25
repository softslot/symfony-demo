init-project: docker-down-clear docker-pull docker-build docker-up composer-install

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

docker-up:
	docker compose up -d

composer-install:
	docker compose run --rm php-cli composer install

check: test lint analyze

test:
	docker compose run --rm php-cli ./bin/phpunit

lint:
	docker compose run --rm php-cli ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php src tests --diff

analyze:
	docker compose run --rm php-cli ./vendor/bin/psalm
