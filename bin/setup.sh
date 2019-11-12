#!/bin/bash

ROOT_DIR=$(dirname $(dirname $(realpath $0)))

pushd $ROOT_DIR

echo -e "\e[32;1mPrepare .env file.\e[m"
cp .env.example .env

echo -e "\e[32;1mInitialize directory permissions...\e[m"
chmod -R go+w storage bootstrap/cache

echo -e "\e[32;1mBuild Production container...\e[m"
docker-compose build web

echo -e "\e[32;1mRun composer install.\e[m"
docker-compose -f docker-compose-dev.yml run -w /work-logger web composer install

echo -e "\e[32;1mGenerate app key.\e[m"
docker-compose -f docker-compose-dev.yml run -w /work-logger web php artisan key:generate

echo -e "\e[32;1mStart containers...\e[m"
docker-compose -f docker-compose-dev.yml up -d
sleep 5

echo -e "\e[32;1mRun migrations...\e[m"
docker-compose -f docker-compose-dev.yml exec web bash -c 'cd /work-logger && php artisan migrate'

echo -e "\e[32;1mRun Seeders...\e[m"
docker-compose -f docker-compose-dev.yml exec web bash -c 'cd /work-logger && php artisan db:seed'

echo -e "\e[32;1mRun yarn...\e[m"
docker-compose -f docker-compose-dev.yml run -w /work-logger web /root/.yarn/bin/yarn install

echo -e "\e[32;1mBuild scripts...\e[m"
docker-compose -f docker-compose-dev.yml run -w /work-logger web /root/.yarn/bin/yarn dev

echo -e "\e[32;1mAll done!\e[m"
