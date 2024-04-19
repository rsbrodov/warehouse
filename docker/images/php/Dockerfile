FROM php:7.4.27-fpm-alpine as base

ENV APP_DIR /www/app
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

WORKDIR $APP_DIR

COPY . .

ENV PHP_EXTENTIONS mysqli pdo pdo_mysql

RUN apk add --update --no-cache nodejs npm

# PHP
RUN docker-php-ext-install ${PHP_EXTENTIONS}

RUN docker-php-ext-enable pdo_mysql

COPY --from=composer:2.2.5 /usr/bin/composer /usr/local/bin/composer

RUN chown -R $USER:www-data storage
RUN chown -R $USER:www-data bootstrap/cache
RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap/cache
RUN php artisan storage:link

# Установка зависимостей
COPY package*.json ./

FROM base AS development

RUN npm install
RUN composer install

FROM base AS production

RUN npm install --production
RUN composer install --optimize-autoloader --no-dev

RUN php artisan config:clear
#RUN php artisan cache:clear
#RUN php artisan route:clear
#RUN php artisan view:clear
#RUN php artisan optimize:clear
#RUN php artisan optimize
#RUN php artisan config:cache

CMD ["npm", "start"]
