FROM phusion/baseimage
MAINTAINER Stefan van der Walt <stefanv@berkeley.edu>

RUN echo mysql-server mysql-server/root_password password beo8hkii | sudo debconf-set-selections
RUN echo mysql-server mysql-server/root_password_again password beo8hkii | sudo debconf-set-selections

RUN apt-get update
RUN apt-get -y upgrade
RUN apt-get -y install mysql-client mysql-server apache2 libapache2-mod-php5 \
                       python-setuptools vim-tiny php5-mysql supervisor

ADD ./foreground.sh /etc/apache2/foreground.sh
ADD ./supervisord.conf /etc/supervisord.conf

# RUN chmod 755 /etc/apache2/foreground.sh

EXPOSE 80
CMD ["/bin/bash", "supervisord", "-n"]
