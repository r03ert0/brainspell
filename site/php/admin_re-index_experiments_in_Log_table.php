<?php

/*
	This script re-indexes experiments in the Log table:
	from the old array-position index, to the new id-based
	index.
*/

$dbhost = "localhost"; // this will ususally be 'localhost', but can sometimes differ
$dbname = "brainspell"; // the name of the database that you are going to use for this project
$dbuser = "root"; // the username that you created, or were given, to access your database
$dbpass = "beo8hkii"; // the password that you created, or were given, to access your database

mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error 1: " . mysql_error());
mysql_select_db($dbname) or die("MySQL Error 2: " . mysql_error());

function strip_cdata($str)
{
	return preg_replace('/<!\[CDATA\[(.*)\]\]>/', '$1', $str);
}

$result = mysql_query("SELECT * FROM ".$dbname.".Log");
while($record=mysql_fetch_assoc($result))
{
	$pmid=$record['PMID'];
	
	$result2=mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");	
	$record2=mysql_fetch_assoc($result2);

	$index=intval($record['Experiment']);
	$exs=json_decode(strip_cdata($record2["Experiments"]));

	echo $record['PMID']." '".$record['Type']."'\n";
	if($index==-1)
		$id=-1;
	else
	if($index<count($exs))
		$id=$exs[$index]->id;
	else
		$id=$index;

	$q="UPDATE ".$dbname.".Log SET Experiment='".$id."' ";
	$q.="WHERE UniqueID='".$record['UniqueID']."'";
	$result3=mysql_query($q);
	mysql_query($q) or die ("ERROR: Unable to process query: ".$q."\n");

	mysql_free_result($result2);
}
mysql_free_result($result);
?>
