#!/bin/bash

if [ "$1" != "-it" ]; then
    docker exec  pon-php-fpm php bin/console cache:clear --env=prod
    docker exec  pon-php-fpm chmod -R 777 var/cache
    docker exec  pon-php-fpm chmod -R 777 var/logs
else
    docker exec -it pon-php-fpm php bin/console cache:clear --env=prod
    docker exec -it  pon-php-fpm chmod -R 777 var/cache
    docker exec -it  pon-php-fpm chmod -R 777 var/logs
fi
