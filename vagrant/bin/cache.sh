#!/bin/bash

docker exec  pon-php-fpm php bin/console cache:clear --env=prod
docker exec  pon-php-fpm chmod -R 777 var/cache
docker exec  pon-php-fpm chmod -R 777 var/logs