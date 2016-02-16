#!/bin/bash
exec docker run --rm --name=brainspell -p 127.0.0.1:8888:80 -t brainspell
