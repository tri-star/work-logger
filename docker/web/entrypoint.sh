#!/bin/bash

mkdir -p /var/log/nginx


APP_DIR=/work-logger
chmod -R go+w $APP_DIR/bootstrap/cache
mkdir -p $APP_DIR/storage/app/public
mkdir -p $APP_DIR/storage/framework/cache
mkdir -p $APP_DIR/storage/framework/sessions
mkdir -p $APP_DIR/storage/framework/views
mkdir -p $APP_DIR/storage/logs
chmod -R go+w $APP_DIR/storage

exec "$@"
