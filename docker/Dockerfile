FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader

COPY . .

EXPOSE 80

CMD ["bash", "-c", "cd public && php -S 0.0.0.0:80"]

