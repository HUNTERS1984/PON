#!/bin/bash

# run without sudo: usermod -aG docker pon

#sudo no password : pon ALL=(ALL) NOPASSWD:ALL



IFS=$'\n' read -rd '' -a runningContainers <<<"$(docker ps -a -q)"

if [ ! "$runningContainers" ]; then
        echo "No running containers!"
    else
    docker stop $(docker ps -a -q) && docker rm $(docker ps -a -q)
fi

mkdir -p $(pwd)/vagrant/data/elasticsearch $(pwd)/vagrant/data/mysql $(pwd)/vagrant/data/rabbitmq $(pwd)/application/node_modules $(pwd)/application/bower_components
sudo restorecon -Rv -n $(pwd)/vagrant/data/elasticsearch
sudo restorecon -Rv -n $(pwd)/vagrant/data/mysql
sudo restorecon -Rv -n $(pwd)/vagrant/data/rabbitmq
sudo restorecon -Rv -n $(pwd)/application/node_modules
sudo restorecon -Rv -n $(pwd)/application/bower_components

docker run -d --name app --restart=always -ti -v $(pwd)/application:/var/www/pon:rw,z -v $(pwd)/vagrant/data/mysql:/data/mariadb:rw,z --user 1000:50 -w $(pwd)/vagrant/data/elasticsearch -w $(pwd)/vagrant/data/mysql -w $(pwd)/vagrant/data/rabbitmq  debian bash

docker build -t pon/mysql $(pwd)/vagrant/docker/mysql

docker run -d --name mysql --restart=always -ti -p 3306:3306 --volumes-from='app' pon/mysql bash

docker run -d --name elasticsearch --restart=always -ti -p 9200:9200 -v $(pwd)/vagrant/data/elasticsearch:/usr/share/elasticsearch/data:rw,z  --user 1000:50 elasticsearch elasticsearch

docker run -d --name rabbitmq --restart=always -ti -p 15672:15672 -p 5672:5672 -v $(pwd)/vagrant/data/rabbitmq:/var/lib/rabbitmq:rw,z  --user 1000:50 rabbitmq:3-management rabbitmq-server

docker build -t pon/phpfpm $(pwd)/vagrant/docker/phpfpm

docker run -d --name phpfpm --restart=always -ti -p 9000:9000 -e SYMFONY__DATABASE__HOST='mysql' -e SYMFONY__ELASTICSEARCH__HOST='elasticsearch' -e SYMFONY__RABBITMQ__HOST='rabbitmq' --link mysql:mysql --link elasticsearch:elasticsearch --link rabbitmq:rabbitmq  --volumes-from='app'  pon/phpfpm bash

docker build -t pon/nginx $(pwd)/vagrant/docker/nginx

docker run -d --name nginx --restart=always -ti -p 80:80 --link mysql:mysql --link phpfpm:phpfpm --volumes-from='app' -e DOMAIN=pon.cm,www.pon.cm -e ENVPON=app  pon/nginx bash


##Symlink###
yes | sudo cp -rf $(pwd)/vagrant/bin/restart.sh /usr/bin/restart && sudo chmod a+x /usr/bin/restart
yes | sudo cp -rf $(pwd)/vagrant/bin/clean.sh /usr/bin/clean && sudo chmod a+x /usr/bin/clean
yes | sudo cp -rf $(pwd)/vagrant/bin/mysql.sh /usr/bin/mysql && sudo chmod a+x /usr/bin/mysql
yes | sudo  cp -rf $(pwd)/vagrant/bin/mysqldump.sh /usr/bin/mysqldump && sudo chmod a+x /usr/bin/mysqldump
yes | sudo  cp -rf $(pwd)/vagrant/bin/login.sh /usr/bin/login && sudo  chmod a+x /usr/bin/login
yes | sudo  cp -rf $(pwd)/vagrant/bin/composer.sh /usr/bin/composer && sudo  chmod a+x /usr/bin/composer
yes | sudo  cp -rf $(pwd)/vagrant/bin/console.sh /usr/bin/console && sudo  chmod a+x /usr/bin/console
yes | sudo  cp -rf $(pwd)/vagrant/bin/npm.sh /usr/bin/npm && sudo  chmod a+x /usr/bin/npm
yes | sudo  cp -rf $(pwd)/vagrant/bin/bower.sh /usr/bin/bower && sudo  chmod a+x /usr/bin/bower
yes | sudo  cp -rf $(pwd)/vagrant/bin/gulp.sh /usr/bin/gulp && sudo  chmod a+x /usr/bin/gulp
yes | sudo  cp -rf $(pwd)/vagrant/bin/cache.sh /usr/bin/cache && sudo chmod a+x /usr/bin/cache