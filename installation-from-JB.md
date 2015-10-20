
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


At that stage, check that brainspell.dev is working in browser
---------------------------------------------------------------

To make the search work locally: change permission for Lucene
-----------------------------------------------------------------

$ cd <project location>/brainspell/site/php
$ sudo chmod -R a+rwx LuceneIndex

