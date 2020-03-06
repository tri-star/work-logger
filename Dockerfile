FROM node:13.8-alpine3.10 AS node

WORKDIR /work-logger

COPY package.json yarn.lock /work-logger/
RUN yarn install --production --pure-lock-file
RUN mkdir -p public/images public/css public/js
COPY . /work-logger/
RUN yarn production


FROM nginx:1.17.9-alpine AS web

RUN apk --no-cache add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata

COPY --from=node /work-logger/public/js /work-logger/public/js
COPY --from=node /work-logger/public/css /work-logger/public/css
COPY --from=node /work-logger/public/images /work-logger/public/images
COPY --from=node /work-logger/public/mix-manifest.json /work-logger/public/

COPY ./docker/web/etc /etc


FROM php:7.2-fpm-buster AS app

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
COPY --from=node /work-logger/public/mix-manifest.json /work-logger/public/

RUN composer install --no-autoloader


COPY . /work-logger/
RUN rm public/index.php

RUN composer dump-autoload -o

COPY .env.production.dist /work-logger/.env
COPY ./docker/app/entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh

CMD ["php-fpm", "-F"]
ENTRYPOINT ["/entrypoint.sh"]
