FROM php:8.3-fpm-alpine

# Устанавливаем зависимости с использованием apk
RUN apk update && apk add \
    bash \
    git \
    curl \
    nodejs \
    npm \
    zip \
    unzip \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    nano \
    vim \
    autoconf \
    gcc \
    make \
    g++ \
    libtool \
    re2c \
    linux-headers  # Добавляем пакет linux-headers для компиляции Xdebug

# Очищаем кеш apk
RUN rm -rf /var/cache/apk/*

# Копируем php.ini
COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Устанавливаем PHP расширения
RUN docker-php-ext-install pdo pdo_pgsql && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip

# Устанавливаем и включаем Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Копируем xdebug.ini
COPY ./_docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Копируем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка рабочего каталога
WORKDIR /var/www

# Запускаем PHP-FPM
CMD ["php-fpm"]
