#!/bin/bash

docker exec  pon-php-fpm bash -c "node_modules/.bin/gulp  $1 $2 $3 $4"