FROM php:8.2-fpm

# -----------------------------------------------------------------------------
# System dependencies required by Laravel, Composer, and common PHP extensions
# -----------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    sudo \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libssl-dev \
    pkg-config \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------------------------------
# PHP extensions required for Laravel + typical production workloads
# -----------------------------------------------------------------------------
RUN docker-php-ext-install pdo_mysql zip intl

# GD (for image processing)
RUN docker-php-ext-configure gd \
    --with-jpeg \
    --with-freetype \
    && docker-php-ext-install gd

# -----------------------------------------------------------------------------
# Opcache (major performance boost for Laravel production)
# -----------------------------------------------------------------------------
RUN docker-php-ext-install opcache

# -----------------------------------------------------------------------------
# Redis extension (commonly used for cache & queues)
# -----------------------------------------------------------------------------
RUN pecl install redis \
    && docker-php-ext-enable redis

# -----------------------------------------------------------------------------
# Node.js (for building Laravel Mix/Vite assets)
# -----------------------------------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# -----------------------------------------------------------------------------
# Install Composer globally
# -----------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------------------------
# Supervisor config folder for queue workers, horizon, etc.
# -----------------------------------------------------------------------------
RUN mkdir -p /etc/supervisor/conf.d

# Optional: copy your queue worker config
# COPY ./supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

WORKDIR /var/www
