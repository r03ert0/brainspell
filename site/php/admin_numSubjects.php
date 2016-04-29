<?php
/*
	This script:
	1. Adds a z value of 0 for all the locations in each experiment
*/
//$dbhost = "localhost"; // this will ususally be 'localhost', but can sometimes differ
$dbhost = "192.168.99.100";
$dbname = "brainspell"; // the name of the database that you are going to use for this project
$dbuser = "root"; // the username that you created, or were given, to access your database
$dbpass = "beo8hkii"; // the password that you created, or were given, to access your database

$link = mysqli_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error 1: " . mysqli_error($link));
mysqli_select_db($link, $dbname) or die("MySQL Error 2: " . mysqli_error($link));

function strip_cdata($str)
{
	return preg_replace('/<!\[CDATA\[(.*)\]\]>/', '$1', $str);
}

	//Update database
	$q="ALTER TABLE ".$dbname.".Articles ADD ";
	$q.="NumSubjects int";
	$result2 = mysqli_query($link, $q);
	if($result2)
		echo "SUCCESS\n";
	else
	{
	//	echo "ERROR: Unable to process query: ".$q."\n";
	//	var_dump($record);
	}

	$q="UPDATE ".$dbname.".Articles SET ";
	$q.="NumSubjects=0";
	$result3 = mysqli_query($link, $q);
	if($result3)
		echo "SUCCESS\n";
	else
	{
	//	echo "ERROR: Unable to process query: ".$q."\n";
	//	var_dump($record);
	}

?>
