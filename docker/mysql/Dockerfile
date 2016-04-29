FROM mysql:5.7
# Image takes the following vars, see https://hub.docker.com/_/mysql/
# MYSQL_ROOT_PASSWORD=beo8hkii
# MYSQL_DATABASE=brainspell
# (these will be unused)
# MYSQL_USER, MYSQL_PASSWORD
# MYSQL_ALLOW_EMPTY_PASSWORD

# it will execute files with extensions .sh and .sql that are found in
# /docker-entrypoint-initdb.d. You can easily populate your mysql services by
# mounting a SQL dump into that directory

# We'll run this like so:
# docker run --name some-mysql -v /my/own/datadir:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=my-secret-pw -d mysql:tag
