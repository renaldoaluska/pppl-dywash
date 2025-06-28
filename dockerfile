# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi dan tools pendukung
# PASTIKAN 'pgsql' dan 'pdo_pgsql' ADA DI SINI
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    curl \
    && docker-php-ext-install pdo pgsql pdo_pgsql intl mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file project ke container
COPY . /var/www/html

# Atur folder kerja
WORKDIR /var/www/html

# Ubah permission writable
RUN chown -R www-data:www-data /var/www/html/writable

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Salin dan aktifkan config vhost kustom kita
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install dependency PHP
RUN composer install

EXPOSE 80
