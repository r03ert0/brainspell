<?php

include $_SERVER['DOCUMENT_ROOT']."/php/base.php";
$connection=mysqli_connect($dbhost, $dbuser, $dbpass,$dbname) or die("MySQL Error 1: " . mysql_error());

$rootdir = $_SERVER['DOCUMENT_ROOT'];
$papers = simplexml_load_file('../data/full_database_revised_updated-20Mar2014.xml');
$addToMySQL=1;
$addToLucene=1;

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

$uploaded=0;
$success=0;
//for($k=0;$k<1;$k++)
for($k=0;$k<count($papers);$k++)
{
	$pmid=$papers->paper[$k]->pmid;
	echo "Paper #".($k+1).". ".$pmid.": ";

	/*------------------------------/
	/		Get article data		/
	/------------------------------*/
	// Article title
	$title=$papers->paper[$k]->title;
	//echo $title."\n";
	
	// Authors
	$au=(array)$papers->paper[$k]->authors->author;
	$authors="";
	for($i=0;$i<count($au)-1;$i++)
		$authors.=$au[$i].",";
	$authors.=$au[$i];
	//echo $authors."\n";
	
	// Abstract
	$abstract=$papers->paper[$k]->PubMed->Abstract;
	//echo $abstract."\n";
	
	// Year
	$year=(string)$papers->paper[$k]->year;

	// Journal
	$journal=(string)$papers->paper[$k]->PubMed->Source;

	// Reference
	$reference="";
	for($i=0;$i<count($au);$i++)
	{
		if($i==5)
		{
			$reference.=" et al.";
			break;
		}
		$reference.=$au[$i];
		if($i<min(4,count($au)-1))
			$reference.=", ";
	}
	$reference.=" (".$papers->paper[$k]->year.") ";
	$reference.=$papers->paper[$k]->PubMed->Source;
	//echo $reference."\n";

	// DOI
	$doi=$papers->paper[$k]->doi;
	//echo $doi."\n";
	
	// NeuroSynthID
	$neurosynth="";
	//echo $neurosynth."\n";
	
	// Experiments
	$experiments=[];
	for($i=0;$i<count($papers->paper[$k]->experiment);$i++)
	{
		$ob=[];
		$ob["title"]="";
		$ob["caption"]="";
		$ob["id"]=(string)$papers->paper[$k]->experiment[$i]->id;
		$ob["locations"]=[];
		for($j=0;$j<count($papers->paper[$k]->experiment[$i]->location);$j++)
			$ob["locations"][]=(string)$papers->paper[$k]->experiment[$i]->location[$j];
		$experiments[]=$ob;
	}
	//print_r($experiments)."\n";
	
	// Metadata (only mesh headings are added from the xml)
	$metadata=[];
	$metadata["meshHeadings"]=[];
	$mesh="";
	for($i=0;$i<count($papers->paper[$k]->PubMed->MeshCodes->tag);$i++)
	{
		$ob=[];
		$ob["name"]=(string)$papers->paper[$k]->PubMed->MeshCodes->tag[$i]->name;
		$ob["code"]=(string)$papers->paper[$k]->PubMed->MeshCodes->tag[$i]->code;
		$ob["majorTopic"]=(string)$papers->paper[$k]->PubMed->MeshCodes->tag[$i]->majorTopic;
		$metadata["meshHeadings"][]=$ob;
		$mesh.=$ob["name"]."\t";
	}
	//print_r($metadata)."\n";


	/*------------------------------/
	/		Upload to database		/
	/------------------------------*/
	if($addToMySQL)
	{
		$result = mysqli_query($connection,"SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");
		if(mysqli_num_rows($result)>=1)
		{
			echo "The article with PMID [".$pmid."] is already in the database\n";
		}
		else
		{
			$uploaded++;
		
			// Build query
			$q="INSERT INTO ".$dbname.".Articles (Title, Authors, Abstract, Reference, PMID, DOI, NeuroSynthID, Experiments, Metadata) VALUES(";
			$q.= "'".mysqli_real_escape_string($connection,$title)."'";
			$q.=",'".mysqli_real_escape_string($connection,$authors)."'";	//mysql_real_escape_string($article['Authors']);
			$q.=",'".mysqli_real_escape_string($connection,$abstract)."'";	//mysql_real_escape_string($article['Abstract']);
			$q.=",'".mysqli_real_escape_string($connection,$reference)."'";	//mysql_real_escape_string($article['Reference']);
			$q.=",'".$pmid."'";	//mysql_real_escape_string($article['PMID']);
			$q.=",'".$doi."'";	//mysql_real_escape_string($article['PMID']);
			$q.=",'".$neurosynthid."'";	//mysql_real_escape_string($article['PMID']);
			$q.=",'".mysqli_real_escape_string($connection,json_encode($experiments))."'";	//mysql_real_escape_string($article['Experiments']);
			$q.=",'".mysqli_real_escape_string($connection,json_encode($metadata))."')";	//mysql_real_escape_string($article['Metadata'])."')";
		
			// Add article to database
			$result2 = mysqli_query($connection,$q);
			if($result2)
			{
				echo "SUCCESS";
				$success++;
			}
			else
				echo "ERROR: Unable to process query: ".$q;
			echo ".\n";
		}
		mysqli_free_result($result);
	}
	

	/*------------------------------/
	/		Upload to Lucene		/
	/------------------------------*/
	if($addToLucene)
	{
		$article["Title"]=$title;
		$article["Authors"]=$authors;
		$article["Abstract"]=$abstract;
		$article["Year"]=$year;
		$article["Journal"]=$journal;
		$article["PMID"]=$pmid;
		$article["Reference"]=$reference;
		$article["MeshHeadings"]=$mesh;

		if($k==count($papers)-1)
			$optimise=1;
		else
			$optimise=0;
		index_lucene($article,$optimise);
	}
}
echo "Total articles processes: ".count($papers)."\n";
echo "Total uploaded: ".$uploaded."\n";
echo "Total successfully added: ".$success."\n";
?>
