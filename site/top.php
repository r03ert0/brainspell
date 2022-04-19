<?php

include $_SERVER['DOCUMENT_ROOT']."/php/base.php";

$result = mysql_query("SELECT Username, PMID FROM ".$dbname.".Log");
$nrows=mysql_num_rows($result);
$array1=array();
$array2=array();
$array3=array();
while($row = mysql_fetch_array($result))
{
	$array3[$row["Username"]]++;
	if($array1[$row["Username"].";".$row["PMID"]]==0)
	{
		$array1[$row["Username"].";".$row["PMID"]]=1;
		$array2[$row["Username"]]+=1;
	}
}

arsort($array2);

$totalpapers=0;
$totaltags=0;
foreach ($array2 as $name => $npapers)
{
	$totalpapers+=$npapers;
	$totaltags+=$array3[$name];
}

?>

<h1> BrainSpell tag scores </h1>
<b><?php echo $totalpapers; ?> papers tagged</b><br/>
<b><?php echo $totaltags; ?> tags</b><br/>
<br/>
<table style="border:1px solid lightGrey">
<tr><td><b>Nickname</b></td><td><b>#papers</b></td><td><b>#tags</b></td></tr>
<?php
foreach ($array2 as $name => $npapers)
	echo "<tr><td>".$name."</td><td>".$npapers."</td><td>".$array3[$name]."</td></tr>";
?>
</table>
