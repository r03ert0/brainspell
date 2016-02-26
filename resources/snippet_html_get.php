$getdata = http_build_query(array("query" => $q,'action' => 'search_lucene'));
$opts = array('http' =>array('method'=>'POST','header'=>'Content-type: application/x-www-form-urlencoded'));
$context  = stream_context_create($opts);
$result = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/php/brainspell.php?'.$getdata, false, $context);	
echo $result;	
