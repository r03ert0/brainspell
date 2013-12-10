<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$rootdir = "/";
$negpos=array(	0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0.0053,0.0105,0.0158,0.0211,0.0263,0.0316,0.0368,0.0421,0.0474,0.0526,0.0579,0.0632,0.0684,0.0737,0.0789,0.0842,0.0895,0.0947,0.1000,0.1053,0.1105,0.1158,0.1211,0.1263,0.1316,0.1368,0.1421,0.1474,0.1526,0.1579,0.1632,0.3368,0.3474,0.3579,0.3684,0.3789,0.3895,0.4000,0.4105,0.4211,0.4316,0.4421,0.4526,0.4632,0.4737,0.4842,0.4947,0.5053,0.5158,0.5263,0.5368,0.5474,0.5579,0.5684,0.5789,0.5895,0.6000,0.6105,0.6211,0.6316,0.6421,0.6526,0.6632,0.6737,0.6842,0.6947,0.7053,0.7158,0.7263,0.7368,0.7474,0.7579,0.7684,0.7789,0.7895,0.8000,0.8105,0.8211,0.8316,0.8421,0.8526,0.8632,0.8737,0.8842,0.8947,0.9053,0.9158,0.9263,0.9368,0.9474,0.9579,0.9684,0.9789,0.9895,1.0000,
			0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0.3316,0.3263,0.3211,0.3158,0.3105,0.3053,0.3000,0.2947,0.2895,0.2842,0.2789,0.2737,0.2684,0.2632,0.2579,0.2526,0.2474,0.2421,0.2368,0.2316,0.2263,0.2211,0.2158,0.2105,0.2053,0.2000,0.1947,0.1895,0.1842,0.1789,0.1737,0.1684,0.3263,0.3158,0.3053,0.2947,0.2842,0.2737,0.2632,0.2526,0.2421,0.2316,0.2211,0.2105,0.2000,0.1895,0.1789,0.1684,0.1579,0.1474,0.1368,0.1263,0.1158,0.1053,0.0947,0.0842,0.0737,0.0632,0.0526,0.0421,0.0316,0.0211,0.0105,0,0,0.0105,0.0211,0.0316,0.0421,0.0526,0.0632,0.0737,0.0842,0.0947,0.1053,0.1158,0.1263,0.1368,0.1474,0.1579,0.1684,0.1789,0.1895,0.2000,0.2105,0.2211,0.2316,0.2421,0.2526,0.2632,0.2737,0.2842,0.2947,0.3053,0.3158,0.3263,0.1684,0.1737,0.1789,0.1842,0.1895,0.1947,0.2000,0.2053,0.2105,0.2158,0.2211,0.2263,0.2316,0.2368,0.2421,0.2474,0.2526,0.2579,0.2632,0.2684,0.2737,0.2789,0.2842,0.2895,0.2947,0.3000,0.3053,0.3105,0.3158,0.3211,0.3263,0.3316,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
			1.0000,0.9895,0.9789,0.9684,0.9579,0.9474,0.9368,0.9263,0.9158,0.9053,0.8947,0.8842,0.8737,0.8632,0.8526,0.8421,0.8316,0.8211,0.8105,0.8000,0.7895,0.7789,0.7684,0.7579,0.7474,0.7368,0.7263,0.7158,0.7053,0.6947,0.6842,0.6737,0.6632,0.6526,0.6421,0.6316,0.6211,0.6105,0.6000,0.5895,0.5789,0.5684,0.5579,0.5474,0.5368,0.5263,0.5158,0.5053,0.4947,0.4842,0.4737,0.4632,0.4526,0.4421,0.4316,0.4211,0.4105,0.4000,0.3895,0.3789,0.3684,0.3579,0.3474,0.3368,0.1632,0.1579,0.1526,0.1474,0.1421,0.1368,0.1316,0.1263,0.1211,0.1158,0.1105,0.1053,0.1000,0.0947,0.0895,0.0842,0.0789,0.0737,0.0684,0.0632,0.0579,0.0526,0.0474,0.0421,0.0368,0.0316,0.0263,0.0211,0.0158,0.0105,0.0053,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

include $_SERVER['DOCUMENT_ROOT'].$rootdir."php/base.php";

if(isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case "article":
			article($_GET["PMID"]);
			break;
		case "search_lucene":
			search_lucene($_GET["query"]);
			break;
		case "index_lucene":
			index_lucene($_GET);
			break;
		case "add_article":
			add_article($_GET);
			break;
		case "get_article":
			get_article($_GET);
			break;
		case "get_concept":
			get_concept($_GET);
			break;
		case "add_log":
			add_log($_GET);
			break;
		case "get_log":
			get_log($_GET);
			break;
		case "admin_updateDOI":
			admin_updateDOI($_GET);
			break;
	}
}
function strip_cdata($str)
{
	return preg_replace('/<!\[CDATA\[([^\]]*)\]\]>/', '$1', $str);
}
function home()
{
	global $rootdir;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$home = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/home.html");
	$tmp=str_replace("<!--Core-->",$home,$html);
	$html=$tmp;
	
	$tmp=str_replace("<!--ROOTDIR-->",$rootdir,$html);
	$html=$tmp;

	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","",$html);
	$html=$tmp;

	print $html;
}
function about()
{
	global $rootdir;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$about = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/about.html");
	$tmp=str_replace("<!--Core-->",$about,$html);
	$html=$tmp;
	
	$tmp=str_replace("<!--ROOTDIR-->",$rootdir,$html);
	$html=$tmp;

	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","",$html);
	$html=$tmp;

	print $html;
}
function news()
{
	global $rootdir;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$news = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/news.html");
	$tmp=str_replace("<!--Core-->",$news,$html);
	$html=$tmp;
	
	$tmp=str_replace("<!--ROOTDIR-->",$rootdir,$html);
	$html=$tmp;

	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","",$html);
	$html=$tmp;

	print $html;
}
function download()
{
	global $rootdir;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$download = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/download.html");
	$tmp=str_replace("<!--Core-->",$download,$html);
	$html=$tmp;
	
	$tmp=str_replace("<!--ROOTDIR-->",$rootdir,$html);
	$html=$tmp;

	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","",$html);
	$html=$tmp;

	print $html;
}
function article($pmid)
{
	global $dbname;
	global $rootdir;
	
    $result=mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = ".$pmid);

    if(mysql_num_rows($result)==1)
    {
		$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
		$article = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/article.html");
		$tmp=str_replace("<!--Core-->",$article,$html);
		$html=$tmp;
		
		$tmp=str_replace("<!--ROOTDIR-->",$rootdir,$html);
		$html=$tmp;

        $record=mysql_fetch_assoc($result);
		$fields=array("Title","Reference","Abstract","PMID","DOI","NeuroSynthID");
		foreach ($fields as $i):
    		$tmp=str_replace("<!--".$i."-->",stripslashes(strip_cdata($record[$i])),$html);
	    	$html=$tmp;
		endforeach;
		
		/*
		// convert PMID to DOI for full text linking
		$ctx = stream_context_create(array('http'=>array('timeout' => 1)));
		$json = file_get_contents("http://www.pmid2doi.org/rest/json/doi/".$record["PMID"],0,$ctx);
		$data = json_decode($json, TRUE);
		$tmp=str_replace("<!--DOI-->",$data["doi"],$html);
		$html=$tmp;
		*/

		if(isset($_SESSION['Username']))
			$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
		else
			$tmp=str_replace("<!--Username-->","",$html);
		$html=$tmp;
	
		if(isset($_SESSION['LoggedIn']))
			$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
		else
			$tmp=str_replace("<!--LoggedIn-->","",$html);
		$html=$tmp;
    	
		print $html;
    }
    else
    {
		header('HTTP/1.1 404 Not Found');
        echo "ERROR: There is no record for article with PMID ".$pmid."\n";
    }
    mysql_free_result($result);
}
function search_lucene($query)
{
	global $rootdir;
	global $dbname;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$search = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/search.html");
	$tmp=str_replace("<!--Core-->",$search,$html);
	$html=$tmp;
	
	$tmp=str_replace("<!--ROOTDIR-->",$rootdir,$html);
	$html=$tmp;

	$index=getIndex_lucene();
	$hits=$index->find($query);
	if (!empty($hits))
	{
		/*
		// combined query image: put all locations into a base64 blob
		$data="";
		foreach($hits as $hit):
			$q="SELECT Experiments FROM ".$dbname.".Articles WHERE PMID = ".$hit->PMID;
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
		*/
		
		// list article references
		$str='<ul class="paper-list">';
		foreach($hits as $hit):
			$str.="<li><div class=\"paper-stuff\">\n";
			$str.="<h3><a href=\"".$rootdir."article/".$hit->PMID."\">";
			$str.=$hit->Title."</a></h3>\n";
			$str.="<p class=\"info\">".$hit->Reference." (".sprintf("%.0f",$hit->score*100)."%)</p>\n";
			$str.="</div></li>\n";
		endforeach;
		$str=$str."</ul>\n";
	}
	else
		$str="<p>The search <b>".$query."</b> did not match any articles.</p>";
	
	$tmp=str_replace("<!--%SearchResultsNumber%-->",count($hits),$html);
	$html=$tmp;
	$tmp=str_replace("<!--%SearchResultsMultiplicity%-->",(count($hits)>1)?"s":"",$html);
	$html=$tmp;
	
	$tmp=str_replace("<!--%SearchString%-->",htmlentities(stripslashes($query)),$html);
	$html=$tmp;
	$tmp=str_replace("<!--%SearchResults%-->",stripslashes($str),$html);
	$html=$tmp;
	
	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","",$html);
	$html=$tmp;
	
	print $html;
}
function getIndex_lucene()
{
	global $rootdir;

	require_once 'Zend/Loader/Autoloader.php';
	require_once 'StandardAnalyzer/Analyzer/Standard/English.php';
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->setFallbackAutoloader(true);
	$loader->suppressNotFoundWarnings(false);
	
	Zend_Search_Lucene_Analysis_Analyzer::setDefault(new StandardAnalyzer_Analyzer_Standard_English());
	
	$path=$_SERVER['DOCUMENT_ROOT'].$rootdir."php/LuceneIndex";
	try
	{
		$indx = Zend_Search_Lucene::open($path);
	}
	catch(Zend_Search_Lucene_Exception $e)
	{
		try
		{
			$indx = Zend_Search_Lucene::create($path);
		}
		catch(Zend_Search_Lucene_Exception $e)
		{
			echo "<p>Impossible to open an index at ".$path." ".$e->getMessage()."</p>";
			exit(1);
		}
	}

	return $indx;
}
function index_lucene($article)
{
	$index=getIndex_lucene();
	$term = new Zend_Search_Lucene_Index_Term($article["PMID"], 'PMID');
	// a pre-existing page cannot be updated, it has to be
	// deleted, and indexed again:
	$exactSearchQuery = new Zend_Search_Lucene_Search_Query_Term($term);
	$hits = $index->find($exactSearchQuery);
	if (count($hits) > 0) {
		foreach ($hits as $hit) {
			$index->delete($hit->id);
		}
	}

	$doc=new Zend_Search_Lucene_Document();
	$doc->addField(Zend_Search_Lucene_Field::Keyword('PMID',$article["PMID"]));
	$doc->addField(Zend_Search_Lucene_Field::Keyword('Year',$article["Year"]));
	$doc->addField(Zend_Search_Lucene_Field::Keyword('Journal',$article["Journal"]));
	$doc->addField(Zend_Search_Lucene_Field::Text('Title',$article["Title"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::Text('MeshHeadings',$article["MeshHeadings"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::Text('Authors',$article["Authors"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::Text('Reference',$article["Reference"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::UnStored('Abstract',$article["Abstract"],'utf-8'));

	$index->addDocument($doc);
	$index->optimize();
	$index->commit();
	echo "<p>The index contains ".$index->numDocs()." documents</p>";
}
function add_article($query)
{
	global $dbname;
		
	$cmd=$query['command'];
	
	switch($cmd)
	{
		case "new":
		{
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				echo "ERROR: Article is already in the database";
			}
			else
			{
				$q="INSERT INTO ".$dbname.".Articles (Title, Authors, Abstract, Reference, PMID, NeuroSynthID, Experiments, Metadata) VALUES(";
				$q.= "'<![CDATA[".mysql_real_escape_string($query['Title'])."]]>'";
				$q.=",'".mysql_real_escape_string($query['Authors'])."'";	//mysql_real_escape_string($article['Authors']);
				$q.=",'".mysql_real_escape_string($query['Abstract'])."'";	//mysql_real_escape_string($article['Abstract']);
				$q.=",'".mysql_real_escape_string($query['Reference'])."'";	//mysql_real_escape_string($article['Reference']);
				$q.=",'".$query['PMID']."'";	//mysql_real_escape_string($article['PMID']);
				$q.=",'".$query['NeuroSynthID']."'";	//mysql_real_escape_string($article['NeuroSynthID']);
				$q.=",'<![CDATA[".$query['Experiments']."]]>'";	//mysql_real_escape_string($article['Experiments']);
				$q.=",'".$query['Metadata']."')";	//mysql_real_escape_string($article['Metadata'])."')";
				$result2 = mysql_query($q);
				if($result2)
					echo "SUCCESS";
				else
					echo "ERROR: Unable to process query: ".$q;
		
				// index_lucene($article);
			}
			mysql_free_result($result);
			break;
		}
		case "experiments":
		{
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				$q="UPDATE ".$dbname.".Articles SET Experiments=";
				$q.= "'<![CDATA[".$query['Experiments']."]]>'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysql_query($q);
				if($result2)
					echo "SUCCESS ";
				else
					echo "ERROR: Unable to process query: ".$q;
	
				// index_lucene($article);
			}
			mysql_free_result($result);
			break;
		}
		case "metadata":
		{
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				$q="UPDATE ".$dbname.".Articles SET Metadata=";
				$q.= "'<![CDATA[".$query['Metadata']."]]>'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysql_query($q);
				if($result2)
					echo "SUCCESS ";
				else
					echo "ERROR: Unable to process query: ".$q;
	
				// index_lucene($article);
			}
			mysql_free_result($result);
			break;
		}
	}
}
function get_article($query)
{
	global $dbname;

    $cmd=$query['command'];
    $arg=$query['argument'];

    switch($cmd)
    {
		case "random":
		{
			$result=mysql_query("SELECT UniqueID FROM ".$dbname.".Articles");
			$nrows=mysql_num_rows($result);
			echo "<records>";
			for($i=0;$i<=$arg;$i++)
			{
				$irow=rand(1,$nrows);
				$err=mysql_data_seek($result,$irow);
				$uniqueID=mysql_fetch_row($result);
				$result2=mysql_query("SELECT Title,Reference,PMID FROM ".$dbname.".Articles WHERE UniqueID = ".$uniqueID[0]);
				$record=mysql_fetch_assoc($result2);
				echo "<record>";
				echo "<Title>".stripslashes($record["Title"])."</Title>";
				echo "<Reference>".stripslashes($record["Reference"])."</Reference>";
				echo "<PMID>".$record["PMID"]."</PMID>";
				echo "</record>";
			}
			echo "</records>";
			break;
		}
		case "experiments":
		{
			$result=mysql_query("SELECT Experiments FROM ".$dbname.".Articles WHERE PMID = ".$arg);
			$record=mysql_fetch_assoc($result);
			echo "<experiments>".$record["Experiments"]."</experiments>";
			break;
		}
		case "metadata":
		{
			$result=mysql_query("SELECT Metadata FROM ".$dbname.".Articles WHERE PMID = ".$arg);
			$record=mysql_fetch_assoc($result);
			echo "<metadata>".$record["Metadata"]."</metadata>";
			break;
		}
	}
}
function get_concept($query)
{
	global $dbname;

    $cmd=$query['command'];
    $arg=$query['argument'];

    switch($cmd)
    {
		case "concept":
		{
			$query="SELECT Definition FROM ".$dbname.".Concepts WHERE UPPER(Name) = UPPER('<![CDATA[".mysql_real_escape_string($arg)."]]>')";
			$result=mysql_query($query);
			$record=mysql_fetch_assoc($result);
			$description="<Definition>".$record["Definition"]."</Definition>";
			mysql_free_result($result);
			
			echo $description;
			break;
		}
	}
}
function add_log($query)
{
	global $dbname;
		
	$cmd=$query['command'];
	
	switch($cmd)
	{
		case "user-action":
		{
			$q="INSERT INTO ".$dbname.".Log (UserName,TagName,TagOntology,Experiment,TagVote,PMID) VALUES(";
			$q.="'".$query['UserName']."'";
			$q.=",'".$query['TagName']."'";
			$q.=",'".$query['TagOntology']."'";
			$q.=",".$query['Experiment'];
			$q.=",".$query['TagVote'];
			$q.=",'".$query['PMID']."')";
			$result = mysql_query($q);
			if($result)
				echo "SUCCESS";
			else
				echo "ERROR: Unable to process query: ".$q;
			break;
		}
	}
}
function get_log($query)
{
	global $dbname;
	
	$cmd=$query['command'];
	
	switch($cmd)
	{
		case "user-action":
		{
			$q = "SELECT * FROM ".$dbname.".Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND TagName = '".$query["TagName"]."'";
			$q.= " AND TagOntology = '".$query["TagOntology"]."'";
			$q.= " AND Experiment = ".$query["Experiment"]."";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= "ORDER BY TimeStamp DESC LIMIT 1";
			$result = mysql_query($q);
			if($result)
			{
				$record=mysql_fetch_assoc($result);
				$vote="<TagVote>".$record["TagVote"]."</TagVote>";
				mysql_free_result($result);

				echo $vote;
			}
			else
				echo "<TagVote></TagVote>";
			break;
		}
	}
}
function admin_updateDOI($query)
{
	$pmid=$query['pmid'];
	$doi=$query['doi'];
	
	$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");
	if(mysql_num_rows($result)>=1)
	{
		$q="UPDATE ".$dbname.".Articles SET DOI = '".$doi."' WHERE PMID = '".$pmid."'";			
		$r=mysql_query($q);
		mysql_query($q) or die ("ERROR: Unable to process query: ".$q."\n");
	}
	mysql_free_result($result);
}
/*
function query_image($hits)
{
	global $rootdir;

	$LR=45;
	$PA=54;
	$IS=45;
	$N=16395;

	// open brainspell.xml, which contains the correspondences between PMID codes and experiment ids
	$articles=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].$rootdir."data/coincidences/brainspell.xml");

	// open rois.zip, which contains all experiments for all articles
	$rois=$_SERVER['DOCUMENT_ROOT'].$rootdir.sprintf("data/coincidences/rois.zip");
	$za = new ZipArchive();
	$za->open($rois);
	
	// initialise the array that will contain the query image
	$sum=array();
	$sum=array_pad($sum,$LR*$PA*$IS,0);
	
	// add up all the experiments in the articles returned by the query
	$searching=0;
	$unziping=0;
	foreach($hits as $hit):
	{
		$time=microtime(1);
		$experiments=$articles->xpath('/papers/paper[Medline_number='.$hit->PMID.']/experiment');
		$searching+=microtime(1)-$time;
		
		for($i=0;$i<count($experiments);$i++)
		{
			$num=count($experiments[$i]->location);
			$id=$experiments[$i]->id;
			if($num>0)
			{
				$name=$id.".img";
				$time=microtime(1);
				$vol=$za->getFromName($name);
				for($j=0;$j<$LR*$PA*$IS;$j++)
					$sum[$j]=$sum[$j]+ord($vol{$j});
				$unziping+=microtime(1)-$time;
			}
		}
	}
	endforeach;
	echo "searching all experiments took ".$searching." sec<br>";
	echo "unziping all experiments took ".$unziping." sec<br>";

	// draw the query image
	$max=0;
	for($i=0;$i<$LR*$PA*$IS;$i++)
		if($sum[$i]>$max)
			$max=$sum[$i];
	$view=0;
	$ys=24;
	switch($view)
	{	case 0:	$W=$PA; $H=$IS; break; // sagital
		case 1:	$W=$LR; $H=$IS; break; // coronal
		case 2:	$W=$LR; $H=$PA; break; // axial
	}
	$cimage=imagecreatetruecolor($W,$H);
	for($i=0;$i<256;$i++)
		$grey[$i]=imagecolorallocate($cimage,$i,$i,$i);
	for($y=0;$y<$H;$y++)
	for($x=0;$x<$W;$x++)
	{
		switch($view)
		{	case 0:$i=$y*$PA*$LR+$x*$LR+$ys; break;
			case 1:$i=$y*$PA*$LR+$yc*$LR+$x; break;
			case 2:$i=$ya*$PA*$LR+$y*$LR+$x; break;
		}
		$val=floor(255*$sum[$i]/$max);
		imagesetpixel($cimage,$x,$H-$y-1,$grey[$val]);
	}

	imagepng($cimage,$_SERVER['DOCUMENT_ROOT'].$rootdir."data/queryimage.png");
}
*/
function colourmap($val,&$c)
{
	global $negpos;
	$n=191;
	$i=floor($val*($n-1));
	
	$c[0]=255*($negpos[     $i]+($val-$i/($n-1))*($negpos[     $i+1]-$negpos[     $i]));
	$c[1]=255*($negpos[  $n+$i]+($val-$i/($n-1))*($negpos[  $n+$i+1]-$negpos[  $n+$i]));
	$c[2]=255*($negpos[2*$n+$i]+($val-$i/($n-1))*($negpos[2*$n+$i+1]-$negpos[2*$n+$i]));
}
function tal2mni($tal)
{
	$ab=-0.05004170837554*$tal[1];

	if($tal[2]>$ab)
	{
		$mni[0]=1.01010101010101*$tal[0];
		$mni[1]=1.02963944370615*$tal[1]-0.05152491677390*$tal[2];
		$mni[2]=0.05432518398987*$tal[1]+1.08559810912496*$tal[2];
	}
	else
	{
		$mni[0]=1.01010101010101*$tal[0];
		$mni[1]=1.02963944370615*$tal[1]-0.05152491677390*$tal[2];
		$mni[2]=0.05949901103652*$tal[1]+1.18898840523210*$tal[2];
	}
	
	return $mni;
}
function query_image($hits)
{
	global $rootdir;

	$LR=45;
	$PA=54;
	$IS=45;

	// open brainspell.xml, which contains the correspondences between PMID codes and experiment ids
	$articles=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].$rootdir."data/coincidences/brainspell.xml");

	// initialise the array that will contain the query image
	$sum=array();
	$sum=array_pad($sum,$LR*$PA*$IS,0);
	
	// configure roi
	$R=3;
	$nroi=0;
	for($x=-$R;$x<=$R;$x++)
	for($y=-$R;$y<=$R;$y++)
	for($z=-$R;$z<=$R;$z++)
	if($x*$x+$y*$y+$z*$z<=$R*$R)
	{
		$roi[$nroi*3+0]=$x;
		$roi[$nroi*3+1]=$y;
		$roi[$nroi*3+2]=$z;
		$nroi++;
	}
	
	// add up all the experiments in the articles returned by the query
	$searching=0;
	$unziping=0;
	$iter=0;
	foreach($hits as $hit):
	{
		$time=microtime(1);
		
		$result=mysql_query("SELECT Experiments FROM ".$dbname.".Articles WHERE PMID = ".$hit->PMID);
		$record=mysql_fetch_assoc($result);
		print_r($record."\n");

		$locations=$articles->xpath('/papers/paper[Medline_number='.$hit->PMID.']/experiment/location');
		$searching+=microtime(1)-$time;

		$time=microtime(1);
		for($j=0;$j<count($locations);$j++)
		{
			$dst=sscanf($locations[$j]," %f , %f , %f ");
			//$dst=tal2mni($dst);
			$ind[0]=$dst[0]/4.0+22;
			$ind[1]=$dst[1]/4.0+31;
			$ind[2]=$dst[2]/4.0+17.5;
			for($k=0;$k<$nroi;$k++)
			{
				$x=floor($roi[$k*3+0]+$ind[0]);
				$y=floor($roi[$k*3+1]+$ind[1]);
				$z=floor($roi[$k*3+2]+$ind[2]);
				if($x>=0&&$x<$LR && $y>=0&&$y<$PA && $z>=0&&$z<$IS)
				{
					$sum[$z*$PA*$LR+$y*$LR+$x]+=1;
					// pack the coordinates
				}
			}
		}
		$unziping+=microtime(1)-$time;
	}
	endforeach;
	//echo "searching all experiments took ".$searching." sec<br>";
	//echo "unziping all experiments took ".$unziping." sec<br>";

	// draw the query image. Alternatives: (1) pack the image, (2) pack the coordinates and draw in js, (3) pack the volume and draw in js
	$max=0;
	for($i=0;$i<$LR*$PA*$IS;$i++)
		if($sum[$i]>$max)
			$max=$sum[$i];
	$view=0;
	$ys=24;
	switch($view)
	{	case 0:	$W=$PA; $H=$IS; break; // sagital
		case 1:	$W=$LR; $H=$IS; break; // coronal
		case 2:	$W=$LR; $H=$PA; break; // axial
	}
	$cimage=imagecreatetruecolor($W,$H);
	for($i=0;$i<=512;$i++)
	{
		colourmap(0.5+0.5*($i/512.0),$b);
		$color[$i]=imagecolorallocate($cimage,$b[0],$b[1],$b[2]);
	}
	for($y=0;$y<$H;$y++)
	for($x=0;$x<$W;$x++)
	{
		switch($view)
		{	case 0:$i=$y*$PA*$LR+$x*$LR+$ys; break;
			case 1:$i=$y*$PA*$LR+$yc*$LR+$x; break;
			case 2:$i=$ya*$PA*$LR+$y*$LR+$x; break;
		}
		$val=floor(512*($sum[$i]/$max));
		imagesetpixel($cimage,$x,$H-$y-1,$color[$val]);
	}

	imagepng($cimage,$_SERVER['DOCUMENT_ROOT'].$rootdir."data/queryimage.png");
}

?>
