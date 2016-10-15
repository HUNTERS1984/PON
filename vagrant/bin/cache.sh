#!/bin/bash

docker exec -it pon-php-fpm php bin/console cache:clear --env=prod
docker exec -it pon-php-fpm chmod -R 777 var/cache
docker exec -it pon-php-fpm chmod -R 777 var/logs