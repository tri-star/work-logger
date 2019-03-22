#! /bin/bash

set -ex

# MySQLのデータディレクトリを初期化, rootの仮パスワードを解除
rm -rf /var/lib/mysql
mysqld --initialize-insecure --user=mysql

# nginxは"/vagrant"をドキュメントルートとして扱うので、symlinkを張る
ln -s ${CODEBUILD_SRC_DIR} /vagrant

# yarnのパス設定
. /etc/profile.d/yarn.sh

# .envの準備
cp .env.testing .env

# supervisordの起動
supervisord

# MySQLの起動を待つ(暫定の方法)
sleep 5

cat > initial.sql <<EOT
grant all privileges on *.* to work_logger@'127.0.0.1' identified by 'secret';
grant all privileges on *.* to work_logger@'%' identified by 'secret';
create database work_logger_test default charset utf8mb4;
EOT
mysql -u root < initial.sql


cd /vagrant
chmod -R go+w bootstrap/cache storage
composer install
yarn install --frozen-lockfile --ignore-optional

# DBの初期化
php artisan migrate --env=testing
php artisan db:seed --env=testing

# 外部で指定したコマンドの実行
$@

# 各サービスを停止してから終了する。

supervisorctl stop nginx
supervisorctl stop php-fpm
supervisorctl stop mysqld

supervisorctl shutdown
