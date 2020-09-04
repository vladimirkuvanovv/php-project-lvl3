start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp .env.example .env
	cp .env .env.testing
	touch database/database.sqlite || true
	php artisan migrate

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku master

lint:
	composer phpcs

lint-fix:
	composer phpcbf
