#!/bin/bash
php artisan migrate --force
php artisan db:seed --class=LanguageSeeder --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
apache2-foreground