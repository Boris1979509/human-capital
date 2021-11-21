ROOT_DIR := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
OS := $(shell uname)

default: start

deploy:
	php ./deployer.phar deploy develop

ash:
	docker exec -it hcap-php ash

web:
	docker exec -it hcap-nginx ash

db:
	docker exec -it hcap-postgres bash

dump:
	@ echo "Have you meaning 'make db-dump' or 'make autoload'?"

db-dump:
	docker exec -it hcap-postgres pg_dump --no-owner --no-acl -Fc --host=127.0.0.1 --port=5432 --username=root ag_prod --no-owner --file=/var/pgsql-schema.dump --schema-only
	docker cp hcap-postgres:/var/pgsql-schema.dump ./database/schema/

postgres:
	docker exec -it hcap-postgres psql hcap_app

build:
	docker-compose build postgresql

push:
	docker-compose push postgresql

start:
	@(echo "-> Starting application docker (local)")
	make restart
	@(./do.sh "composer install")
	@(echo "-> Done")

stop:
	@(echo "-> Stopping application docker (sync on mac)...")
	docker-compose stop
	@(echo "-> Done")

down:
	@(echo "-> Stopping & removing the application...")
	docker-compose down --remove-orphans
	@(echo "-> Done")

restart:
	docker-compose pull
	@ make reload

reload:
	docker-compose up -d --force-recreate

fresh: refresh
refresh:
	@(echo "-> Refresh the application")
	@(./do.sh "php artisan migrate:fresh")
	@(./do.sh "php artisan db:seed")
	@(echo "-> Done")

migrate:
	@(echo "-> Running migrations...")
	@(./do.sh "php artisan migrate")
	@(echo "-> Done")

install:
	composer --ignore-platform-reqs --no-scripts install

update:
	composer --ignore-platform-reqs --no-scripts --no-dev update

update-dev:
	composer --ignore-platform-reqs --no-scripts update

composer_install:
	@(echo "-> Installing composer dependencies...")
	@(./do.sh "composer install")
	@(echo "-> Done")

composer_update:
	@(echo "-> Updating composer dependencies...")
	@(./do.sh "composer update")
	@(echo "-> Done")

composer_dump:
	@(echo "-> Dump composer autoload...")
	@(./do.sh "composer dump-autoload")
	@(echo "-> Done")

autoload:
	@ printf " \r-> Dumping composer autoload @ host... [`date`]\n"
	php -d memory_limit=-1 composer.phar dump-autoload -v --no-scripts
	@ printf " -> Done @ `date`\n"

require:
	@ printf " \r-> Require composer package @ host... [`date`]\n"
	php -d memory_limit=-1 composer.phar --ignore-platform-reqs --no-scripts -v require $(filter-out $@,$(MAKECMDGOALS))
	@ printf " -> Done @ `date`\n"

require-dev:
	@ printf " \r-> Require composer package (DEV) @ host... [`date`]\n"
	php -d memory_limit=-1 composer.phar --ignore-platform-reqs --no-scripts --dev -v require $(filter-out $@,$(MAKECMDGOALS))
	@ printf " -> Done @ `date`\n"

install_composer:
	@ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	@ php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	@ php composer-setup.php
	@ php -r "unlink('composer-setup.php');"

arg:
	@ echo "<<<"
	@ echo $(filter-out $@,$(MAKECMDGOALS))
	@ echo ">>>"

lint:
	@(echo "-> Running php lint...")
	@(./vendor/bin/phpcs --standard=ruleset.xml app -p)
	@(echo "-> Done")

test:
	@(echo "-> Running tests (dockered)...")
	@(./do.sh ./vendor/bin/phpunit)
	@(echo "-> Done")

clean:
	@(echo "-> Refresh caches (dockered)...")
	@(./do.sh "rm -rf bootstrap/cache/*.php")
	@(./do.sh "php artisan optimize")
	@(./do.sh "php artisan view:clear")
	@(./do.sh "php artisan cache:clear")
	@(./do.sh "php artisan debugbar:clear")
	@(echo "-> Done")

ide:
	@(echo "-> IDE helper: make `_ide_helper.php`, write models properties...")
	@(./do.sh "php artisan ide-helper:models -W -R")
	@(echo "-> Done")

ide_full:
	@(echo "-> IDE helper: generating all smelly things...")
	@(./do.sh "php artisan ide-helper:generate")
	@(./do.sh "php artisan ide-helper:meta")
	@(./do.sh "php artisan ide-helper:models -W -R")
	@(echo "-> Done")

npm:
	npm update
	npm audit fix
	npm run prod
