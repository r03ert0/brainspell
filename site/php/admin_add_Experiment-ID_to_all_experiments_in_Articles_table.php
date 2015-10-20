<?php

/*
	This script:
	1. re-indexes experiments in the Artile table: from the old array-position index, to
	   the new id-based index. New indices start at 90000, to try to distinguish from
	   those in neurosynth that have numerica values <20000
	2. Makes experiment data json_encoded, eliminating the old CDATA tags
	3. Makes title data json_encoded, eliminating the old CDATA tags
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

$result = mysql_query("SELECT * FROM ".$dbname.".Articles");
while($record=mysql_fetch_assoc($result))
{
	$pmid=$record['PMID'];
	$doi=$record['DOI'];
	$exs=$record['Experiments'];
	$title=$record['Title'];
	echo "PMID: ".$pmid."\n";
	if($pmid=="")
	{
		var_dump($record);
		continue;
	}
	
	// Decode experiments cdata and add experiment ids if required
	$exs=json_decode(strip_cdata($exs));
	for($i=0;$i<count($exs);$i++)
	{
		$ex=$exs[$i];
		if($ex->id=="")
			$ex->id=90000+$i;
	}
	
	// Decode title cdata
	$title=strip_cdata($title);
	
	$q="UPDATE ".$dbname.".Articles SET ";
	$q.="Title='".mysql_real_escape_string($title)."', ";
	$q.="Experiments='".mysql_real_escape_string(json_encode($exs))."' ";
	$q.= " WHERE PMID='".$pmid."'";
	$result2 = mysql_query($q);
	if($result2)
		echo "SUCCESS\n";
	else
	{
		echo "ERROR: Unable to process query: ".$q."\n";
		var_dump($record);
	}
}
mysql_free_result($result);
?>
