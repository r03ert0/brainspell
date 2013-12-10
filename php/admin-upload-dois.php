<?php

include $_SERVER['DOCUMENT_ROOT'].$rootdir."php/base.php";

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
