// combined query image: put all locations into a base64 blob
$data="";
foreach($hits as $hit):
	$q="SELECT Experiments FROM Articles WHERE PMID = ".$hit->PMID;
	$result=mysql_query($q);
	$record=mysql_fetch_assoc($result);
	$json=json_decode(simplexml_load_string("<ex>".$record["Experiments"]."</ex>",null,LIBXML_NOCDATA));
	for($i=0;$i<count($json);$i++)
	{
		for($j=0;$j<count($json[$i]->locations);$j++)
		{
			$c=sscanf($json[$i]->locations[$j]," %f , %f , %f ");
			$c[0]=floor($c[0]/4+22);
			$c[1]=floor($c[1]/4+31);
			$c[2]=floor($c[2]/4+17.5);
			if($c[0]>=0&&$c[0]<45&&$c[1]>=0&&$c[1]<54&&$c[2]>=0&&$c[2]<45)
				$data.=pack("C",$c[0]).pack("C",$c[1]).pack("C",$c[2]);
		}
	}
endforeach;
$str="<script>var locationData=\"".base64_encode($data)."\";</script>\n";
