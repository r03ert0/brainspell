- Build image (once only):

  docker build -t brainspell .

- Launch container:

  ./brainspell.sh

- You can pause and resume the container as follows:

  docker stop brainspell
  docker start brainspell

- If you re-launch ./brainspell.sh, it will destroy the existing brainspell
  container and create a new one.  The database is stored on your machine, so
  you won't lose any data other than log files.

- To inspect log files on the brainspell container:

  docker exec -t -i brainspell /bin/bash

  (after running brainspell.sh)

