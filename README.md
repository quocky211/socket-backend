To start backend by docker 
	docker compose up -d --build

	docker compose exec web bash

	cp .env.example .env

	php artisan key:generate

	php artisan migrate:fresh --seed

	exit
