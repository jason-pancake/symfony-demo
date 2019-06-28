FROM php:7.2-fpm

ARG ENV

# Update repos
RUN apt-get update

# Upgrade
RUN apt-get upgrade -y

# Install libraries
RUN apt-get update \
    && apt-get install -y \
        apt-utils \
        libxml2-dev \
        git \
        vim \
        zip \
        unzip \
        gnupg \
        zlib1g-dev \
        libpq-dev \
        libicu-dev \
        acl \
        apt-transport-https \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) intl

# XDebug
RUN if [ ${ENV} = "dev" ]; then \
    pecl install xdebug \
    && docker-php-ext-enable xdebug \
;fi

# Opcache
RUN if [ ${ENV} = "prod" ]; then \
    docker-php-ext-install -j$(nproc) opcache \
    && docker-php-ext-enable opcache \
;fi

# PHP
COPY conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY conf.d/www.ini /usr/local/etc/php/conf.d/www.ini

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN ln -s /usr/local/bin/composer /usr/bin/composer

# Aliases
RUN echo "alias c='/app/bin/console'" >> /root/.bashrc

# Add start script
ADD start.sh /start.sh

# Expose port
EXPOSE 9000

# Start
CMD ["/start.sh"]
