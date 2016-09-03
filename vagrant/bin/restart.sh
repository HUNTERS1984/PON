#!/bin/bash

docker restart app
docker restart mysql
docker restart phpfpm
docker restart nginx


