# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi dan tools pendukung
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    curl \
    && docker-php-ext-install pdo pdo_pgsql intl mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file project ke container
COPY . /var/www/html

# Atur folder kerja
WORKDIR /var/www/html

# Ubah permission writable
RUN chown -R www-data:www-data /var/www/html/writable

# Aktifkan mod_rewrite (untuk routing CI4)
RUN a2enmod rewrite

# Atur dokument root
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Install dependency PHP dan JS jika ada
RUN composer install

# OPTIONAL: install Node.js dan Tailwind jika dibutuhkan
# Uncomment ini jika kamu pakai Tailwind secara lokal
# RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
#     && apt-get install -y nodejs \
#     && npm install

EXPOSE 80
