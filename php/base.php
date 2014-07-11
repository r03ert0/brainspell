<?php
session_start();
$dbhost = "localhost"; // this will ususally be 'localhost', but can sometimes differ
$dbname = "brainspell"; // the name of the database that you are going to use for this project
$dbuser = "root"; // the username that you created, or were given, to access your database
$dbpass = "beo8hkii"; // the password that you created, or were given, to access your database
mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error 1: " . mysql_error());
mysql_select_db($dbname) or die("MySQL Error 2: " . mysql_error());
?>