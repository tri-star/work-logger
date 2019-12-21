#!/bin/bash

ROOT_DIR=$(dirname $(dirname $(realpath $0)))

pushd $ROOT_DIR

echo -e "\e[32;1mPrepare .env file.\e[m"
if [ !-f .env ]; then
    cp .env.example .env
fi

echo -e "\e[32;1mInitialize directory permissions...\e[m"
chmod -R go+w storage bootstrap/cache

echo -e "\e[32;1mBuild Production container...\e[m"
docker-compose build web

echo -e "\e[32;1mRun composer install.\e[m"
docker-compose run web composer install

echo -e "\e[32;1mGenerate app key.\e[m"
docker-compose run web php artisan key:generate

echo -e "\e[32;1mStart containers...\e[m"
docker-compose up -d
sleep 5

echo -e "\e[32;1mRun migrations...\e[m"
docker-compose exec web php artisan migrate

echo -e "\e[32;1mRun Seeders...\e[m"
docker-compose exec web php artisan db:seed

echo -e "\e[32;1mRun yarn...\e[m"
docker-compose run web yarn install

echo -e "\e[32;1mBuild scripts...\e[m"
docker-compose run web yarn dev

echo -e "\e[32;1mAll done!\e[m"
