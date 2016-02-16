#!/bin/bash

LOG="/var/log/mysql/error.log"

if [[ ! -f /var/lib/mysql/INSTALLED ]]; then
    /usr/bin/mysql_install_db
    touch /var/lib/mysql/INSTALLED
fi

/usr/bin/mysqld_safe &

sleep 5
/usr/bin/mysqladmin -u root password 'beo8hkii'

/usr/bin/mysql --host=localhost --user=root --password=beo8hkii << END
CREATE DATABASE IF NOT EXISTS brainspell CHARACTER SET utf8;
GRANT ALL ON brainspell.* TO root@localhost;
END

tail -f $LOG
