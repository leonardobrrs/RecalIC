#!/bin/sh

# Define 'set -e' para falhar se algum comando falhar
set -e

# 1. Limpa os Caches (usando caminhos completos)
echo "Limpando caches..."
/usr/bin/php8.3 /var/www/html/artisan config:clear
/usr/bin/php8.3 /var/www/html/artisan route:clear
/usr/bin/php8.3 /var/www/html/artisan view:clear

/usr/bin/php8.3 /var/www/html/artisan config:cache
/usr/bin/php8.3 /var/www/html/artisan route:cache
/usr/bin/php8.3 /var/www/html/artisan view:cache

# 2. Cria o Link de Storage
echo "Criando link de storage..."
/usr/bin/php8.3 /var/www/html/artisan storage:link

# 3. Executa as Migrações (Liga-se ao Aiven)
echo "Executando migrações..."
/usr/bin/php8.3 /var/www/html/artisan migrate --force

# 4. Inicia o Servidor (Ouve na porta do Render)
echo "Iniciando o servidor..."
/usr/bin/php8.3 /var/www/html/artisan serve --host 0.0.0.0 --port $PORT
