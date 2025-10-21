build:
	docker-compose build --no-cache

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down
	docker-compose up -d

shell:
	docker-compose exec app bash

composer:
	docker-compose exec app composer install

artisan:
	docker-compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

test:
	docker-compose exec app php artisan test

logs:
	docker-compose logs -f app

clean:
	docker-compose down -v
	docker system prune -f

setup:
	docker-compose up -d
	sleep 10
	docker-compose exec app composer install
	docker-compose exec app cp .env.docker .env
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate:fresh --seed
	docker-compose exec app php artisan config:cache
	@echo "\nSetup complete! Access at http://localhost:8000"

prime:
	docker-compose exec app php artisan primes:analyze $(LIMIT)

%:
	@:
