#!/bin/bash


if [ "$1" != "-it" ]; then
    docker exec  pon-php-fpm npm $1 $2
else
    docker exec -it  pon-php-fpm npm $2 $3
fi

