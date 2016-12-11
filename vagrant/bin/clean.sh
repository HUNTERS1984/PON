#!/bin/bash
IFS=$'\n' read -rd '' -a runningContainers <<<"$(docker ps -a -q)"

if [ ! "$runningContainers" ]; then
        echo "No running containers!"
    else
    docker stop $(docker ps -a -q) && docker rm $(docker ps -a -q)
fi
