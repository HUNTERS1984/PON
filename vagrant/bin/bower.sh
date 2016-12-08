#!/bin/bash

docker exec pon-php-fpm bash -c "node_modules/.bin/bower  $1 $2 $3 $4"