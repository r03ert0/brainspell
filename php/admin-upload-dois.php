<?php

$dbhost = "localhost"; // this will ususally be 'localhost', but can sometimes differ
$dbuser = "root"; // the username that you created, or were given, to access your database
$dbpass = "beo8hkii"; // the password that you created, or were given, to access your database
$dbname = "brainspell"; // the name of the database that you are going to use for this project
$connection=mysqli_connect($dbhost, $dbuser, $dbpass,$dbname) or die("MySQL Error 1: " . mysql_error());

$file="admin-pmid2doi.json";
echo "Started\n";
if(($handle=fopen($file, "r")) !== FALSE)
{
	$data=json_decode(file_get_contents($file), true);
	for($i=0;$i<count($data);$i++)
	{
		$pmid=$data[$i]["pmid"];
		$doi=$data[$i]["doi"];
		$result = mysqli_query($connection,"SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");
		if(mysqli_num_rows($result)>=1)
		{
			$q="UPDATE ".$dbname.".Articles SET DOI = '".$doi."' WHERE PMID = '".$pmid."'";			
			$r=mysqli_query($connection,$q);
			mysqli_query($connection,$q) or die ("ERROR: Unable to process query: ".$q."\n");
		}
		mysqli_free_result($result);
		echo $i."\n";
    }
    fclose($handle);
	echo "Finished\n";
}
?>
