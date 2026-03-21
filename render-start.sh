#!/bin/sh

# Falha o script se algum comando der erro
set -e

echo "Limpando e gerando caches..."
php /var/www/html/artisan config:clear
php /var/www/html/artisan route:clear
php /var/www/html/artisan view:clear

php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

echo "Criando link de storage..."
# O '|| true' evita que o script pare se o link já existir
php /var/www/html/artisan storage:link || true

echo "Executando migrações..."
php /var/www/html/artisan migrate --force

echo "Iniciando o servidor..."
# Usamos a variável $PORT que o Render fornece automaticamente
php /var/www/html/artisan serve --host 0.0.0.0 --port ${PORT:-80}