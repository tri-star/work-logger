FROM php:7.2-fpm-buster

RUN apt update && \
    apt install -y locales \
        lsb-release wget gnupg \
        git unzip zlib1g-dev && \
    locale-gen ja_JP.UTF-8 && \
    echo "export LANG=ja_JP.UTF-8" >> ~/.bashrc && \
    apt clean

WORKDIR /tmp

ENV DEBIAN_FRONTEND=noninteractive
RUN curl -LO https://dev.mysql.com/get/mysql-apt-config_0.8.15-1_all.deb && \
    dpkg -i mysql-apt-config_0.8.15-1_all.deb && \
    apt update && \
    apt install -y mysql-client && \
    rm mysql-apt-config_0.8.15-1_all.deb && \
    apt clean

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql

RUN curl -o /tmp/composer-setup.php -L https://getcomposer.org/installer && \
    php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer && \
    rm /tmp/composer-setup.php

WORKDIR /work-logger

COPY composer.json composer.lock /work-logger/

RUN composer install --no-autoloader


COPY . /work-logger/

RUN composer dump-autoload -o

COPY .env.production.dist /work-logger/.env
COPY ./docker/app/entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh

CMD ["php-fpm", "-F"]
ENTRYPOINT ["/entrypoint.sh"]
