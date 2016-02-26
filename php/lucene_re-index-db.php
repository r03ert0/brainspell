<?php

include "base.php";
$connection=mysqli_connect($dbhost, $dbuser, $dbpass,$dbname) or die("MySQL Error 1: " . mysql_error());

function getIndex_lucene()
{
	global $rootdir;

	$addr='Zend/Loader/Autoloader.php';
	require_once $addr;
	require_once 'StandardAnalyzer/Analyzer/Standard/English.php';
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->setFallbackAutoloader(true);
	$loader->suppressNotFoundWarnings(false);
	
	Zend_Search_Lucene_Analysis_Analyzer::setDefault(new StandardAnalyzer_Analyzer_Standard_English());
	
	$path="LuceneIndex";
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
function index_lucene($article,$optimise)
{
	$index=getIndex_lucene();
	$term = new Zend_Search_Lucene_Index_Term($article["PMID"], 'PMID');
	// a pre-existing page cannot be updated, it has to be
	// deleted, and indexed again:
	$exactSearchQuery = new Zend_Search_Lucene_Search_Query_Term($term);
	$hits = $index->find($exactSearchQuery);
	if (count($hits) > 0) {
		echo "[deleting previous version]\n";
		foreach ($hits as $hit) {
			$index->delete($hit->id);
		}
	}

	$doc=new Zend_Search_Lucene_Document();
	$doc->addField(Zend_Search_Lucene_Field::Keyword('PMID',$article["PMID"]));
	$doc->addField(Zend_Search_Lucene_Field::Keyword('Year',$article["Year"]));
	$doc->addField(Zend_Search_Lucene_Field::Keyword('Journal',$article["Journal"]));
	$doc->addField(Zend_Search_Lucene_Field::Text('Title',$article["Title"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::Text('Authors',$article["Authors"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::Text('Reference',$article["Reference"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::UnStored('Abstract',$article["Abstract"],'utf-8'));
	$doc->addField(Zend_Search_Lucene_Field::Text('MeshHeadings',$article["MeshHeadings"],'utf-8'));

	$index->addDocument($doc);
	if($optimise)
	{
		echo "Optimising index\n";
		$index->optimize();
	}
	$index->commit();
	echo "The index contains ".$index->numDocs()." documents\n";
}

$result=mysqli_query($connection,"SELECT * FROM ".$dbname.".Articles WHERE 1");

$nrows=mysqli_num_rows($result);
echo "There are ".$nrows." rows\n";
echo "But we'll only display the 1st 10\n";
for($i=0;$i<$nrows;$i++)
{
	$record=mysqli_fetch_assoc($result);
	
	//var_dump($record);

	$title=$record["Title"];

	$authors=$record["Authors"];

	$abstract=$record["Abstract"];

	preg_match("/(\d+)/",$record["Reference"],$match);
	$year=$match[1];

	preg_match("/[^(]*\(\d*\) ([^\d(]+) /",$record["Reference"],$match);
	$journal=$match[1];

	$pmid=$record["PMID"];

	$reference=$record["Reference"];

	$mesh="";
	$metadata=json_decode($record["Metadata"]);
	for($j=0;$j<count($metadata->meshHeadings);$j++)
		$mesh.=$metadata->meshHeadings[$j]->name."\t";

	/*
	echo "\n";
	echo "Title: ".$title."\n";
	echo "Reference: ".$reference."\n";
	echo "PMID: ".$pmid."\n";
	echo "Year: ".$year."\n";
	echo "Journal: '".$journal."'\n";

	if(strlen($journal)==0)
		var_dump($record);
	*/

	/*------------------------------/
	/		Upload to Lucene		/
	/------------------------------*/
	$article["Title"]=$title;
	$article["Authors"]=$authors;
	$article["Abstract"]=$abstract;
	$article["Year"]=$year;
	$article["Journal"]=$journal;
	$article["PMID"]=$pmid;
	$article["Reference"]=$reference;
	$article["MeshHeadings"]=$mesh;

	if($i==$nrows-1)
		$optimise=1;
	else
		$optimise=0;
	index_lucene($article,$optimise);
}
echo "Total articles processed: ".$nrows."\n";

?>
