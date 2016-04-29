<?php
	include $_SERVER['DOCUMENT_ROOT']."/php/brainspell.php";

	header($_SERVER['SERVER_PROTOCOL'] . " 200 OK");

	$uri=$_SERVER['REQUEST_URI'];
	$parts=preg_split("/[\/?]/",$uri);

	if($parts[1]=="article")
	{
		$id="";
		for($i=2;$i<count($parts);$i++)
		{
			$id.=$parts[$i];
			if($i<count($parts)-1)
				$id.="/";
		}
		article($id);
	}
	else
	if($parts[1]=="json")
	{
		if($parts[2]=="pmid")
			article_json_pmid($parts[3]);
		else
		if($parts[2]=="doi")
		{
			$doi="";
			for($i=3;$i<count($parts);$i++)
			{
				$doi.=$parts[$i];
				if($i<count($parts)-1)
					$doi.="/";
			}
			article_json_doi($doi);
		}
	}
	else
	if($parts[1]=="search")
	{
		$parts=explode("=",$parts[2]);
		$q=urldecode($parts[1]);
		search_lucene($q);
	}
	else
	if($parts[1]=="list")
	{
		$parts=explode("=",$parts[2]);
		$q=json_encode($parts[1]);
		article_list($q);
		/*
		$getdata = http_build_query(array("query" => $q,'action' => 'article_list'));
		$opts = array('http' =>array('method'=>'POST','header'=>'Content-type: application/x-www-form-urlencoded'));
		$context  = stream_context_create($opts);
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD:site/404.php
		$result = file_get_contents("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/php/brainspell.php?".$getdata, false, $context);
		echo $result;
=======
		$result = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/php/brainspell.php?'.$getdata, false, $context);
		echo $result;
		*/
		$result = file_get_contents("http://".$_SERVER['SERVER_NAME']."/php/brainspell.php?".$getdata, false, $context);
 		echo $result;
// =======
// 		$result = file_get_contents('http://localhost/php/brainspell.php?'.$getdata, false, $context);
// 		echo $result;
// 		// $result = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/php/brainspell.php?'.$getdata, false, $context);
		// $result = file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/php/brainspell.php?'.$getdata, false, $context);
	}
	else
	if($parts[1]=="about")
	{
		about();
	}
	else
	if($parts[1]=="blog")
	{
		blog();
	}
	else
	if($parts[1]=="download")
	{
		download();
	}
	else
	if($parts[1]=="user")
	{
		user_update($parts[2]);
	}
	else
	{
		home();
	}
?>
