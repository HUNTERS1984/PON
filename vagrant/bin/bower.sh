#!/bin/bash

if [ "$1" != "-it" ]; then
    docker exec pon-php-fpm bash -c "node_modules/.bin/bower  $1 $2 $3 $4"
else
    docker exec -it pon-php-fpm bash -c "node_modules/.bin/bower  $2 $3 $4 $5"
fi
