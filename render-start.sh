#!/bin/sh

# Define 'set -e' para falhar se algum comando falhar
set -e

echo "Reiniciando a fila..."
/usr/bin/php8.3 /var/www/html/artisan queue:restart

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

# 5. Inicia o Servidor de Produção (PHP-FPM) e o Queue Worker
echo "Iniciando o supervisor (servidor web + workers)..."
# Este é o comando que o seu Dockerfile está preparado para executar.
# Ele inicia o 'php-fpm' (o servidor web) E o 'queue:work' (para os e-mails).
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf