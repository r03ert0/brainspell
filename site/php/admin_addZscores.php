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

$result = mysqli_query($link, "SELECT * FROM ".$dbname.".Articles");
while($record=mysqli_fetch_assoc($result))
{
	$pmid=$record['PMID'];
	$doi=$record['DOI'];
	$exs=$record['Experiments'];
	$title=$record['Title'];

	//Add Zscores to each experiment
	$exs=json_decode(strip_cdata($exs));
	for($i=0;$i<count($exs);$i++)
	{
		$ex=$exs[$i];
		if ($ex->locations) {
			for ($j=0;$j<count($ex->locations);$j++) {
				$ex->locations[$j].=",0";
			}
			echo implode(" ", $ex->locations);
			$exs[$i] = $ex;
		}
	}


	//Update database
	$q="UPDATE ".$dbname.".Articles SET ";
	$q.="Experiments='".mysqli_real_escape_string($link, json_encode($exs))."' ";
	$q.= " WHERE PMID='".$pmid."'";
	$result2 = mysqli_query($link, $q);
	if($result2)
		echo "SUCCESS\n";
	else
	{
	//	echo "ERROR: Unable to process query: ".$q."\n";
	//	var_dump($record);
	}
	
}

mysqli_free_result($result);
?>
