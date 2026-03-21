#!/bin/sh
set -e

echo "Limpando caches..."
php /var/www/html/artisan config:clear
php /var/www/html/artisan route:clear
php /var/www/html/artisan view:clear

echo "Gerando caches..."
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

echo "Storage link..."
php /var/www/html/artisan storage:link || true

echo "Migrações..."
php /var/www/html/artisan migrate --force

echo "Iniciando na porta $PORT..."
php /var/www/html/artisan serve --host 0.0.0.0 --port ${PORT:-80}