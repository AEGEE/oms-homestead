#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

cd /home/vagrant/oms-project/oms-neo-core

cp .env.example .env

#TODO: modify .env
sed -i s/3306/5432/ .env 
sed -i s/mysql/pgsql/ .env
echo "#REMEMBER to run 'php artisan config:cache' every time you update this file" >> .env

composer install
php artisan config:cache
php artisan migrate
php artisan key:generate
php artisan db:seed
php artisan config:cache
