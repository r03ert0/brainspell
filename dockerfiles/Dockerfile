FROM phusion/baseimage
MAINTAINER Stefan van der Walt <stefanv@berkeley.edu>

RUN export DEBIAN_FRONTEND=noninteractive

# Password is now set in run_mysql.sh
#
#RUN { \
#    echo mysql-server-5.5 mysql-server/root_password password 'beo8hkii'; \
#    echo mysql-server-5.5 mysql-server/root_password_again password 'beo8hkii'; \
#    } | debconf-set-selections

RUN apt-get update
RUN apt-get -y upgrade
RUN apt-get -y install mysql-client mysql-server apache2 libapache2-mod-php5 \
                       python-setuptools vim-tiny php5-mysql supervisor \
                       debconf-utils

ADD ./foreground.sh /etc/apache2/foreground.sh
ADD ./supervisord.conf /etc/supervisord.conf
ADD ./run_mysql.sh /run_mysql.sh

RUN chmod 755 /etc/apache2/foreground.sh

VOLUME ["/var/lib/mysql"]
EXPOSE 80
CMD ["/usr/bin/python", "/usr/bin/supervisord", "-n"]
