FROM ubuntu:24.04

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV PORT=80

#Instações
RUN apt-get update && apt-get install -y gnupg curl ca-certificates zip unzip git \
    php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-zip php8.3-curl php8.3-gd php8.3-intl \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Criar usuário sail
RUN groupadd --force sail && useradd -ms /bin/bash --no-user-group -g sail -u 1337 sail || true

# Copiar o código
COPY . /var/www/html

# Instalar dependências (Pasta Vendor)
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permissões de pastas do Laravel
RUN chown -R sail:sail /var/www/html \
    && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache || true

# Preparar script de start
COPY render-start.sh /usr/local/bin/render-start.sh
RUN chmod +x /usr/local/bin/render-start.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/render-start.sh"]