# brainspell

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/BIDS-collaborative/brainspell?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

The BIDS collaborative fork of the brainspell project. Useful innovations will
be merged upstream into @r03ert0's repo (see the "forked from" link at the top
of our GitHub page).

The application has been completely dockerized. If you have a properly
configured docker-compose installation, you should be able to execute
`docker-compose up` from the `docker` directory, and then visit the application
on port 8000 in your web browser (note that Dav has experienced some trouble
with Chrome, so probably use another browser for now).

If you are using Docker Machine, don't forget that you'll need to initialize
your docker host with the following commands (or the equivalent) prior to
running `docker-compose up`:

    docker-machine start
    eval $(docker-machine env)

You will also be able to get the ip address of your docker host with
`docker-machine ip`.

After `docker-compose up` (or `sudo docker-compose` if you are not running in a
super user shell) the shell does not complete and messages will be showing in
the terminal. Alternatively you can run `docker-compose up -d` to have it run in
the background. CTR-C or run `docker-compose stop` to stop the containters. 

On a linux install, the brainspell search did not work until you the 
LuceneIndex directory was made writable by all, in the brainspell repository run:
`chmod a+w LuceneIndex/*` or `sudo chmod a+w LuceneIndex/*`



