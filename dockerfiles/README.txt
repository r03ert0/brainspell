- Depending on the setting, the following commands may need to be run with "sudo" 

- Build image (once only):

  docker build -t brainspell .

- Launch container:

  ./brainspell.sh

  Note: this is a BASH shell script and will not run on Windows.  It does not
  do much, though, so you can replace it with the following:

  docker run --name=brainspell -p 127.0.0.1:8888:80 \
             -v /path/to/brainspell/dockerfiles/tmp/mysql:/var/lib/mysql \
             -v /path/to/brainspell:/home/brainspell \
             -t brainspell

  You may need to create the tmp/mysql directory by hand.

- At that stage, if you go to localhost:8888 you should see that it is serving brainspell.

- Change the permissions of LuceneIndex (the search tool)
  from the dockerfile directory: 

  chmod -R a+rwx ../site/php/LuceneIndex

  Or the equivalent appropriate command on Windows

- At that stage, if you have pull from the BIDS-collaborative, brainspell should be up and
  running in localhost:8888  Congrats if that's the case !

- You can pause and resume the container as follows:

  docker stop brainspell
  docker start brainspell

- If you re-launch ./brainspell.sh, it will destroy the existing brainspell
  container and create a new one.  The database is stored on your machine, so
  you won't lose any data other than log files.

- To inspect log files on the brainspell container:

  docker exec -t -i brainspell /bin/bash

  (after running brainspell.sh)


