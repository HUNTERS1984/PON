#!/bin/bash

docker exec  pon-db bash -c "mysqldump  $1 $2 $3 $4 $5 $6 $7 $8 $9"