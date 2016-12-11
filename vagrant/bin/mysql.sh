#!/bin/bash

if [ "$1" != "-it" ]; then
    docker exec  pon-db bash -c "mysql  $1 $2 $3 $4 $5 $6 $7 $8 $9"
else
    docker exec -it  pon-db bash -c "mysql  $2 $3 $4 $5 $6 $7 $8 $9 $10"
fi
