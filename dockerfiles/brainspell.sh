#!/bin/bash
docker stop brainspell && docker rm brainspell

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
mkdir -p $SCRIPTPATH/tmp/mysql
exec docker run --name=brainspell -p 127.0.0.1:8888:80 \
                -v $SCRIPTPATH/tmp/mysql:/var/lib/mysql \
                -v $SCRIPTPATH/..:/home/brainspell \
                -t brainspell
