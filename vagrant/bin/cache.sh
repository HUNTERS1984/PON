#!/bin/bash

docker exec -it phpfpm php bin/console cache:clear --env=prod
docker exec -it phpfpm chmod -R 777 var/cache
docker exec -it phpfpm chmod -R 777 var/logs