<?php

set_time_limit(0);

include $_SERVER['DOCUMENT_ROOT']."/php/base.php";
$connection=mysqli_connect($dbhost, $dbuser, $dbpass,$dbname) or die("MySQL Error 1: " . mysql_error());

$rootdir = $_SERVER['DOCUMENT_ROOT'];
$papers = simplexml_load_file('../data/brainspell.xml');
$addToMySQL=1;
$addToLucene=1;
$debug=0;

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
		echo "[deleting previous version]<br />";
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
		echo "Optimising index<br />";
		$index->optimize();
	}
	$index->commit();
	echo "The index contains ".$index->numDocs()." documents<br />";
}
function strip_cdata($str)
{
	return preg_replace('/<!\[CDATA\[(.*)\]\]>/', '$1', $str);
}


$uploaded=0;
$success=0;
//for($k=0;$k<1;$k++)
for($k=0;$k<count($papers);$k++)
//for($k=8065;$k<8066;$k++)
//for($k=1263;$k<1264;$k++)
//for($k=7282;$k<7283;$k++)
{
	$pmid=$papers->paper[$k]->pmid;
	echo "<b>Paper #".($k+1).", PMID:</b> ".$pmid."<br />";

	/*------------------------------/
	/		Get article data		/
	/------------------------------*/
	if($debug) echo "<u><b>DATA IN XML FILE</b></u><br />";
	
	// Article title
	$title=$papers->paper[$k]->title;
	if($debug) echo "<b>Title: </b>".$title."<br />";
	
	// Authors
	$au=(array)$papers->paper[$k]->authors->author;
	$authors="";
	for($i=0;$i<count($au)-1;$i++)
		$authors.=$au[$i].",";
	$authors.=$au[$i];
	if($debug) echo "<b>Authors: </b>".$authors."<br />";
	
	// Abstract
	$abstract=$papers->paper[$k]->PubMed->Abstract;
	if($debug) echo "<b>Abstract: </b>".$abstract."<br />";
	
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
	if($debug) echo "<b>Reference: </b>".$reference."<br />";

	// DOI
	$doi=$papers->paper[$k]->doi;
	if($debug) echo "<b>DOI: </b>".$doi."<br />";
	
	// NeuroSynthID
	$neurosynth="";
	if($debug) echo "<b>NeuroSynthID: </b>".$neurosynth."<br />";
	
	// Experiments
	$experiments=array();
	for($i=0;$i<count($papers->paper[$k]->experiment);$i++)
	{
		$ob=array();
		$ob["title"]="";
		$ob["caption"]="";
		$ob["id"]=(string)$papers->paper[$k]->experiment[$i]->id;
		$ob["locations"]=array();
		for($j=0;$j<count($papers->paper[$k]->experiment[$i]->location);$j++)
			$ob["locations"][]=(string)$papers->paper[$k]->experiment[$i]->location[$j];
		$experiments[]=$ob;
	}
	if($debug){echo "<b>Experiments: </b><br />";print_r($experiments); echo "<br />";}
	
	// Metadata (only mesh headings are added from the xml)
	$metadata=array();
	$metadata["meshHeadings"]=array();
	$mesh="";
	for($i=0;$i<count($papers->paper[$k]->PubMed->MeshCodes->tag);$i++)
	{
		$ob=array();
		$ob["name"]=(string)$papers->paper[$k]->PubMed->MeshCodes->tag[$i]->name;
		$ob["code"]=(string)$papers->paper[$k]->PubMed->MeshCodes->tag[$i]->code;
		$ob["majorTopic"]=(string)$papers->paper[$k]->PubMed->MeshCodes->tag[$i]->majorTopic;
		$metadata["meshHeadings"][]=$ob;
		$mesh.=$ob["name"]."\t";
	}
	if($debug){echo "<b>Metadata: </b><br />";print_r($metadata);echo "<br /><br />";}
	if(count($metadata["meshHeadings"])==0)
		echo "<b>WARNING</b>: MeSH descriptors not available<br />";


	/*------------------------------/
	/		Upload to database		/
	/------------------------------*/
	$addToLucene=0;
	if($addToMySQL)
	{
		$result = mysqli_query($connection,"SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$pmid."'");
		if(mysqli_num_rows($result)>=1)
		{			
			$row = mysqli_fetch_array($result);
			
			// Check for MeSH descriptors
			$m=json_decode(strip_cdata($row["Metadata"]));
			if(count($m->meshHeadings)==0)
			{
				echo "<b>The article with PMID [".$pmid."] does not have MeSH descriptors</b><br />";
				if(count($metadata["meshHeadings"])!=0)
				{
					$m->meshHeadings=$metadata["meshHeadings"];
					$q="UPDATE ".$dbname.".Articles SET Metadata = '".mysqli_real_escape_string($connection,json_encode($m))."' WHERE PMID = '".$pmid."'";
					$result2=mysqli_query($connection,$q);					
					$addToLucene=1;
					echo "<b>MeSH descriptors were added from brainspell.xml</b><br />";
				}
				else
					echo "<b>The are no MeSH descriptors for this article in brainspell.xml either</b><br />";
			}
			
			// Check for DOI
			if($row["DOI"]=="")
			{
				echo "<b>The article with PMID [".$pmid."] does not have a DOI</b><br />";
				if($doi!="")
				{
					$q="UPDATE ".$dbname.".Articles SET DOI = '".$doi."' WHERE PMID = '".$pmid."'";
					$result2=mysqli_query($connection,$q);
					$addToLucene=1;
					echo "<b>The DOI ".$doi." was added from brainspell.xml</b><br \>";
				}
				else
					echo "<b>There is no DOI for this article in brainspell.xml either</b><br />";				
			}

			// Check for Reference
			if($row["Reference"]<$reference)
			{
				echo "<b>The article with PMID [".$pmid."] does not have a Reference</b><br />";
				if($doi!="")
				{
					$q="UPDATE ".$dbname.".Articles SET Reference = '".mysqli_real_escape_string($connection,$reference)."' WHERE PMID = '".$pmid."'";
					$result2=mysqli_query($connection,$q);
					$addToLucene=1;
					echo "<b>The Reference ".mysqli_real_escape_string($connection,$reference)." was added from brainspell.xml</b><br \>";
				}
				else
					echo "<b>There is no Reference for this article in brainspell.xml either</b><br />";				
			}

			// Check for Title
			if($row["Title"]=="")
			{
				echo "<b>The article with PMID [".$pmid."] does not have a Title</b><br />";
				if($doi!="")
				{
					$q="UPDATE ".$dbname.".Articles SET Title = '".mysqli_real_escape_string($connection,$title)."' WHERE PMID = '".$pmid."'";
					$result2=mysqli_query($connection,$q);
					$addToLucene=1;
					echo "<b>The Title ".mysqli_real_escape_string($connection,$title)." was added from brainspell.xml</b><br \>";
				}
				else
					echo "<b>There is no Title for this article in brainspell.xml either</b><br />";				
			}
			
			// Check for Abstract
			if($row["Abstract"]=="")
			{
				echo "<b>The article with PMID [".$pmid."] does not have an Abstract</b><br />";
				if($doi!="")
				{
					$q="UPDATE ".$dbname.".Articles SET Abstract = '".mysqli_real_escape_string($connection,$abstract)."' WHERE PMID = '".$pmid."'";
					$result2=mysqli_query($connection,$q);
					$addToLucene=1;
					echo "<b>The Abstract ".mysqli_real_escape_string($connection,$abstract)." was added from brainspell.xml</b><br \>";
				}
				else
					echo "<b>There is no Abstract for this article in brainspell.xml either</b><br />";				
			}
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
		
			//echo $q."<br/>";
			// Add article to database
			$result2 = mysqli_query($connection,$q);
			if($result2)
			{
				echo "SUCCESS";
				$success++;
				$addToLucene=1;
			}
			else
				echo "ERROR: Unable to process query: ".$q;
			echo ".<br />";
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
echo "Total articles processes: ".count($papers)."<br />";
echo "Total uploaded: ".$uploaded."<br />";
echo "Total successfully added: ".$success."<br />";
?>
