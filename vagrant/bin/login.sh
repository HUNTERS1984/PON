#!/bin/bash
docker restart phpfpm
docker exec -it phpfpm bash