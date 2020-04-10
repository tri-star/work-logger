#!/bin/sh
cat /etc/nginx/conf.d/default.conf.tmpl | envsubst '$APP_HOST' > /etc/nginx/conf.d/default.conf

exec "$@"
