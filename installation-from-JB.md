
Install MySQL
--------------------
libmysql / MySQL: 5.5.38

Set up the root password:

    mysql -u root -p

Enter: "beo8hkii"

If the password is already set, run this command:

    sudo dpkg-reconfigure mysql-server-5.6

Install phpmyadmin
--------------------

    sudo apt-get install phpmyadmin
    
(choose the apache2 setup and use the same password from above)

version: > 3.5.8.2
Web server: apache 2.4.9 PHP/5.4.3 (but 2.4.7 is working for me)
libmysql / MySQL: 5.5.38
server charset: UTF-8 Unicode (utf8)

# Check that 
`http://localhost/phpmyadmin`
in a browser is giving you the web interface - you should be able to log in with you mysql password (root login)


modify /etc/php5/apache2/php.ini
-------------------------------------
post_max_size = 64M
upload_max_filesize = 64M


In phpmyadmin:
----------------

create database brainspell (go to database) 
choose Collation: `latin1_swedish_ci`

Click on the brainspell database in the sidebar.

go to SQL tab, then 
`USE brainspell`

go to import tab
Select brainspell.sql
set the char set to: `utf-8`


# Getting oriented

Generally, logic is handled by 404.php file. This redirects to other PHP functions.

## Clone the repo in VM home directory
For some reason, the brainspell in Shared folder doesn't work, so we decided to checkout it again in the home directory

```
$ cd
$ git clone https://github.com/BIDS-collaborative/brainspell.git
```

Now the brainspell folder should be at (`/home/oski/brainspell`)

## Current setup

Add to /etc/hosts (`$ subl /etc/hosts`):

```
127.0.0.1    brainspell.dev
```

### Set up apache config

This assumes you have checked out brainspell to your home directory (`/home/oski/brainspell`).

From `/etc/apache2/mods-enabled`:

```
$ sudo ln -s ../mods-available/headers.load .
```

From `/etc/apache2/sites-enabled`:

```
$ sudo ln -s /home/oski/brainspell/conf/apache-site-brainspell.conf .
```

From `/etc/apache2/conf-enabled`:

```
$ sudo ln -s /home/oski/brainspell/conf/base-apache-brainspell.conf .
```

To make the search work locally: change permission for Lucene

```
$ cd /home/oski/brainspell/site/php
```

```
$ sudo chmod -R a+rwx LuceneIndex
```

restart apache 

```
$ sudo service apache2 restart
```

At that stage, check that brainspell.dev is working in browser
---------------------------------------------------------------

Welcome to the brainspell wiki!

Hi Aman!
Hi Roberto!
