# STEP 1: Base Image (Gunakan PHP-FPM, disarankan Alpine karena lebih kecil)
# Sesuaikan versi PHP (misal php:8.2-fpm-alpine) dengan kebutuhan CodeIgniter 4 Anda (8.1+)
FROM php:8.2-fpm-alpine

# STEP 2: Instal Dependensi Sistem dan Ekstensi PHP
# postgresql-dev dan libpq diperlukan untuk koneksi PostgreSQL
# icu-dev (untuk intl), libzip-dev (untuk zip), mbstring, opcache adalah ekstensi umum CI4
RUN apk add --no-cache \
    postgresql-dev \
    libpq \
    icu-dev \
    libzip-dev \
    # Tambahkan dependensi sistem lain jika diperlukan oleh aplikasi Anda (contoh: git)
    # git \
    && docker-php-ext-install pdo_pgsql pgsql intl zip mbstring opcache \
    # Bersihkan cache APK setelah instalasi
    && rm -rf /var/cache/apk/*

# STEP 3: Atur Working Directory di dalam container
WORKDIR /app

# STEP 4: Salin composer.json dan composer.lock terlebih dahulu (memanfaatkan Docker Layer Caching)
COPY composer.json composer.lock ./

# STEP 5: Instal Composer
# Unduh Composer installer, jalankan, dan hapus installer-nya.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# STEP 6: Jalankan composer install
# --no-dev: Tidak menginstal dependensi development
# --optimize-autoloader: Mengoptimalkan autoloader Composer
# --no-interaction: Tidak meminta input pengguna
# --prefer-dist: Mengunduh dari release archive (biasanya lebih cepat)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# STEP 7: Salin seluruh kode aplikasi Anda
# Pastikan ini dilakukan SETELAH composer install
COPY . .

# STEP 8: Atur Permissions untuk folder writable (penting untuk CodeIgniter 4)
# Folder writable perlu izin tulis agar CI bisa membuat cache, log, session, dll.
RUN chmod -R 777 writable/

# STEP 9: Exposed Port (Opsional, Vercel akan otomatis menyuntikkan $PORT)
# EXPOSE 9000
# PHP-FPM biasanya berjalan di port 9000 secara default

# STEP 10: Command untuk Menjalankan PHP-FPM
# Ini adalah perintah yang akan dijalankan saat container dimulai
# Vercel akan mengarahkan traffic HTTP ke PHP-FPM ini.
CMD ["php-fpm"]
