#!/bin/bash

# run without sudo: usermod -aG docker pon

IFS=$'\n' read -rd '' -a runningContainers <<<"$(docker ps -a -q)"

if [ ! "$runningContainers" ]; then
        echo "No running containers!"
    else
    docker stop $(docker ps -a -q) && docker rm $(docker ps -a -q)
fi

chmod -R 777 $(pwd)/vagrant/data

docker run -d --name app --restart=always -ti -v $(pwd)/application:/var/www/pon -v $(pwd)/vagrant/data:/data/mariadb -w $(pwd)/vagrant/data  debian bash

docker build -t pon/mysql $(pwd)/vagrant/docker/mysql

docker run -d --name mysql --restart=always -ti -p 3306:3306 --volumes-from='app' pon/mysql bash

docker build -t pon/phpfpm $(pwd)/vagrant/docker/phpfpm

docker run -d --name phpfpm --restart=always -ti -p 9000:9000 -e SYMFONY__DATABASE__HOST='mysql' --link mysql:mysql --volumes-from='app'  pon/phpfpm bash

docker build -t pon/nginx $(pwd)/vagrant/docker/nginx

docker run -d --name nginx --restart=always -ti -p 80:80 --link mysql:mysql --link phpfpm:phpfpm --volumes-from='app'  pon/nginx bash
