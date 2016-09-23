#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.


#INSTALL SOME MISSING PARTS
sudo apt-get install -y mongodb jq tmux


#CORE SETUP
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


#EVENTS SETUP
cd ../oms-events

npm install

mv nginx.conf.example nginx.conf
sudo ln -s /home/vagrant/oms-project/oms-events/nginx.conf /etc/nginx/sites-enabled/omsevents
sudo systemctl reload nginx.service

#get api key
xtoken=$(curl --data "username=flaviu@glitch.ro&password=1234" localhost/api/login | jq -r ".message")

apikey=$(curl --header "X-Auth-Token: "${xtoken}"" localhost/api/getSharedSecret | jq -r ".key")

cp lib/config/configFile.json.example lib/config/configFile.json
sed -i "s|CHANGEME|"${apikey}"|" lib/config/configFile.json

## New detached tmux session called ``Workstation''
tmux new-session -s Workstation -d
tmux neww -nNode node lib/server.js 
sleep 10
curl localhost:8083/api/registerMicroservice
