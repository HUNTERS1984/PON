#!/bin/bash


if [ "$1" != "-it" ]; then
    docker exec  pon-php-fpm php bin/console $1 $2 $3 $4
else
    docker exec -it pon-php-fpm php bin/console $2 $3 $4 $5
fi