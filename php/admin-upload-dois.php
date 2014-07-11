<?php

$dbhost = "localhost"; // this will ususally be 'localhost', but can sometimes differ
$dbname = "brainspell"; // the name of the database that you are going to use for this project
$dbuser = "root"; // the username that you created, or were given, to access your database
$dbpass = "beo8hkii"; // the password that you created, or were given, to access your database

mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error 1: " . mysql_error());
mysql_select_db($dbname) or die("MySQL Error 2: " . mysql_error());

$file="admin-pmid2doi.json";
echo "Started\n";
if(($handle=fopen($file, "r")) !== FALSE)
{
	$data=json_decode(file_get_contents($file), true);
	for($i=0;$i<count($data);$i++)
	{
		$pmid=$data[$i]["pmid"];
		$doi=$data[$i]["doi"];
		$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");
		if(mysql_num_rows($result)>=1)
		{
			$q="UPDATE ".$dbname.".Articles SET DOI = '".$doi."' WHERE PMID = '".$pmid."'";			
			$r=mysql_query($q);
			mysql_query($q) or die ("ERROR: Unable to process query: ".$q."\n");
		}
		mysql_free_result($result);
		echo $i."\n";
    }
    fclose($handle);
	echo "Finished\n";
}
?>
