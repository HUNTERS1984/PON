#!/bin/bash

if [ "$1" != "-it" ]; then
    docker exec  pon-php-fpm bash -c "service supervisor restart"
else
   docker exec -it  pon-php-fpm bash -c "service supervisor restart"
fi



