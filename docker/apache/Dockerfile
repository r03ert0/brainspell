FROM php:5.6-apache
# Testing if this is necessary...
# RUN apt-get update && apt-get install -y php5-mysql

# This may not be the best way, but it's a way
# It may be redundant with php5-mysql above, but probably that gets all the
# dependencies we need
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install mbstring

# Need to enable headers module
RUN ln -s /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled

# This was the only option not already set for /var/www in apache conf, but we
# don't need directory listings to my knowledge...
# Options Indexes

# These are already set for /var/www
# AllowOverride All
# Require all granted
