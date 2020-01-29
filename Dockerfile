FROM amazonlinux:2.0.20190228

# 日本語の有効化
RUN yum install -y glibc-langpack-ja && yum clean all
ENV LANG="ja_JP.utf8" \
    LC_ALL="ja_JP.utf8"
RUN unlink /etc/localtime && \
    ln -s /usr/share/zoneinfo/Japan /etc/localtime

# supervisord
RUN curl -L https://bootstrap.pypa.io/ez_setup.py | python && \
    easy_install supervisor

# amazon-linux-extras
RUN amazon-linux-extras enable epel nginx1.12=1.12.2 php7.2=7.2.11

# nginx + etc
RUN yum install -y \
    tar \
    gzip \
    less \
    vim \
    curl \
    git \
    jq \
    nginx && \
    yum clean all

# MySQL
RUN yum install -y \
    https://downloads.mysql.com/archives/get/p/23/file/mysql-community-client-5.7.23-1.el7.x86_64.rpm \
    https://downloads.mysql.com/archives/get/p/23/file/mysql-community-common-5.7.23-1.el7.x86_64.rpm \
    https://downloads.mysql.com/archives/get/p/23/file/mysql-community-libs-5.7.23-1.el7.x86_64.rpm \
    https://downloads.mysql.com/archives/get/p/23/file/mysql-community-devel-5.7.23-1.el7.x86_64.rpm && \
    yum clean all

# Node.js
RUN curl -sL https://rpm.nodesource.com/setup_10.x | bash - && \
     yum install -y nodejs && \
     yum clean all

RUN curl -o- -L https://yarnpkg.com/install.sh | bash && \
     ~/.yarn/bin/yarn global add cross-env && \
    ln -s ~/.yarn/bin/yarn /usr/local/bin/yarn

# PHP
RUN yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum install -y --enablerepo=remi,remi-php72 \
    php \
    php-fpm \
    php-mbstring \
    php-xml \
    php-pecl-yaml \
    php-json \
    php-zip \
    php-mysqlnd \
    php-opcache \
    php-process \
    php-xdebug && \
    yum clean all

RUN curl -o /tmp/composer-setup.php -L https://getcomposer.org/installer && \
    php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer && \
    rm /tmp/composer-setup.php

COPY package.json yarn.lock /work-logger/

WORKDIR /work-logger

RUN ~/.yarn/bin/yarn install --pure-lock-file

COPY composer.json composer.lock /work-logger/

RUN composer install --no-autoloader

COPY . /work-logger/

RUN composer dump-autoload -o
RUN ~/.yarn/bin/yarn production

COPY docker/web/etc /etc
RUN rm /etc/php-fpm.d/www.conf

VOLUME /var/log

COPY .env.production.dist /work-logger/.env
COPY ./docker/web/entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh

CMD ["/usr/bin/supervisord", "-n"]
ENTRYPOINT ["/entrypoint.sh"]
