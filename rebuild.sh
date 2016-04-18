#!/usr/bin/env bash

composer dump-autoload
composer update
php artisan vendor:publish
php artisan migrate

echo "manual seed: 'php artisan db:seed'"
