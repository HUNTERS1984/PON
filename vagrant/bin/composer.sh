#!/bin/bash


if [ "$1" != "-it" ]; then
    docker exec  pon-php-fpm composer $1 $2 $3 $4
else
    docker exec -it  pon-php-fpm composer $2 $3 $4 $5
fi
