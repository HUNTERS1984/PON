#!/bin/bash

docker exec -it phpfpm bash -c "node_modules/.bin/bower  $1 $2 $3 $4"