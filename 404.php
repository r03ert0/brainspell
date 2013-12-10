<?php
	include $_SERVER['DOCUMENT_ROOT']."/php/brainspell.php";
	
	header("Status: 200 OK");
	
	$uri=$_SERVER['REQUEST_URI'];
	$parts=preg_split("/[\/?]/",$uri);
	
	if($parts[1]=="article")
	{
		article($parts[2]);
	}
	else
	if($parts[1]=="search")
	{
			$parts=explode("=",$parts[2]);
			$q=urldecode($parts[1]);
			$getdata = http_build_query(array("query" => $q,'action' => 'search_lucene'));
			$opts = array('http' =>array('method'=>'POST','header'=>'Content-type: application/x-www-form-urlencoded'));
			$context  = stream_context_create($opts);
			$result = file_get_contents('http://brainspell.org/php/brainspell.php?'.$getdata, false, $context);	
			echo $result;	
	}
	else
	if($parts[1]=="about")
	{
		about();
	}
	else
	if($parts[1]=="news")
	{
		news();
	}
	else
	if($parts[1]=="download")
	{
		download();
	}
	else
	{
		home();
	}
?>

