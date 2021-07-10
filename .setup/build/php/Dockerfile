# Image
FROM php:8.0-fpm-alpine3.13

ENV DOCUMENT_ROOT /var/www/html
ENV TZ=America/Sao_Paulo

# Workdir
WORKDIR $DOCUMENT_ROOT

# Install dependencies
RUN apk add --no-cache \
        $PHPIZE_DEPS \
        curl \
        openssl \
        libxml2-dev

RUN pecl install redis
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN docker-php-ext-enable opcache redis
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install tokenizer

# Set Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . $DOCUMENT_ROOT

COPY .setup/build/php/php.ini /usr/local/etc/php/conf.d/php.ini

# Copy app
COPY . ${DOCUMENT_ROOT}

COPY --from=composer:2.1 /usr/bin/composer /usr/local/bin/composer

# Ajust permissions
RUN chmod 775 -R storage
RUN chown -R www-data:www-data storage
RUN chmod 775 -R bootstrap
RUN chown -R www-data:www-data bootstrap

RUN  composer install --prefer-dist -n \
    && composer clear-cache

#Expose
# EXPOSE 8000:8000
