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

$result = mysql_query("SELECT * FROM ".$dbname.".Log WHERE TYPE='UserAction'");
while($record=mysql_fetch_assoc($result))
{
	$pmid=$record['PMID'];
	$data=$record["Data"];
	$json=json_decode($data);
	if($json->action!="Edit")
		continue;
	$action=$json->action;
	$index=$json->experiment;
	
	unset($json->action);
	unset($json->experiment);
	
	$result2=mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");	
	$record2=mysql_fetch_assoc($result2);

	$exs=json_decode($record2["Experiments"]);

	if($index==-1)
		$id=-1;
	else
	if($index<count($exs))
		$id=$exs[$index]->id;
	else
		$id=$index;

	echo "UniqueID: '".$record['UniqueID']."', PMID: '".$pmid."', Exp '".$index."', ID: '".$id."', action: '".$action."', data: '".json_encode($json)."'\n";
	$q="UPDATE ".$dbname.".Log SET ";
	$q.="Experiment='".$id."', ";
	$q.="Type='".$action."', ";
	$q.="Data='".mysql_real_escape_string(json_encode($json))."' ";
	$q.="WHERE UniqueID='".$record['UniqueID']."'";
	$result3=mysql_query($q) or die ("ERROR: Unable to process query: ".$q."\n");
}
mysql_free_result($result);
?>
