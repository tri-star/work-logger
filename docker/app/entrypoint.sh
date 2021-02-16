#!/bin/bash

APP_DIR=/work-logger
chmod -R go+w $APP_DIR/bootstrap/cache
mkdir -p $APP_DIR/storage/app/public
mkdir -p $APP_DIR/storage/framework/cache
mkdir -p $APP_DIR/storage/framework/sessions
mkdir -p $APP_DIR/storage/framework/views
mkdir -p $APP_DIR/storage/logs
chmod -R go+w $APP_DIR/storage

if [ "${USE_TEST_DB}" ]; then
    mysql -u root -psecret -h db -e "create database if not exists work_logger_test"
    mysql -u root -psecret -h db -e "grant all privileges on work_logger_test.* to work_logger@'%' identified by 'secret'"
fi

if [ "${USE_DEBUG}" ]; then
    mv /usr/local/php/xdebug.ini /usr/local/php/conf.d/
fi

exec "$@"
