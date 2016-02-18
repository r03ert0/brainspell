#!/bin/bash
docker stop brainspell && docker rm brainspell

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
mkdir -p $SCRIPTPATH/tmp/mysql
exec docker run --name=brainspell -p 80:80 \
                -v $SCRIPTPATH/tmp/mysql:/var/lib/mysql \
                -v $SCRIPTPATH/..:/home/brainspell \
                -t brainspell
