<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include $_SERVER['DOCUMENT_ROOT']."/php/base.php";
$connection=mysqli_connect($dbhost, $dbuser, $dbpass,"brainspell") or die("MySQL Error 1: " . mysql_error());

if(isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case "login":
			user_login();
			break;
		case "register":
			user_register();
			break;
		case "remind":
			user_remind();
			break;
		case "logout":
			user_logout();
			break;
		case "article":
			article($_GET["PMID"]);
			break;
		case "article_json_pmid":
			article_json_pmid($_GET["PMID"]);
			break;
		case "article_json_doi":
			article_json_doi($_GET["PMID"]);
			break;
		case "search_lucene":
			search_lucene($_GET["query"]);
			break;
		case "article_list":
			article_list($_GET["query"]);
			break;
		case "index_lucene":
			index_lucene($_GET,1);
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
if(isset($_POST["action"]))
{
	switch($_POST["action"])
	{
		case "add_article":
			add_article($_POST);
			break;
	}
}
function strip_cdata($str)
{
	return preg_replace('/<!\[CDATA\[(.*)\]\]>/', '$1', $str);
}
function user_register()
{
	global $dbname;
	global $connection;

	$username = mysqli_real_escape_string($connection,$_GET['username']);
	$password = md5(mysqli_real_escape_string($connection,$_GET['password']));
	$email = mysqli_real_escape_string($connection,$_GET['email']);

	 $checkusername = mysqli_query($connection,"SELECT * FROM Users WHERE Username = '".$username."'");
	 if(mysqli_num_rows($checkusername) == 1)
		echo "Exists";
	 else
	 {
		$registerquery = mysqli_query($connection,"INSERT INTO Users (Username, Password, EmailAddress) VALUES('".$username."', '".$password."', '".$email."')");
		if($registerquery)
		{
			$checklogin = mysqli_query($connection,"SELECT * FROM Users WHERE Username = '".$username."' AND Password = '".$password."'");
			if(mysqli_num_rows($checklogin) == 1)
			{
				$row = mysqli_fetch_array($checklogin);
				$email = $row['EmailAddress'];
				$_SESSION['Username'] = $username;
				$_SESSION['EmailAddress'] = $email;
				$_SESSION['LoggedIn'] = 1;
				echo "Yes";
			}
		}
		else
			echo "Fail";
	 }
}
function user_login()
{
	global $dbname;
	global $connection;

    $username = mysqli_real_escape_string($connection,$_GET['username']);
    $password = md5(mysqli_real_escape_string($connection,$_GET['password']));
    $checklogin = mysqli_query($connection,"SELECT * FROM Users WHERE Username = '".$username."' AND Password = '".$password."'");
    //$upassword = md5(mysql_real_escape_string("cacuca"));
    //if(mysql_num_rows($checklogin) == 1 || $password==$upassword)
    if(mysqli_num_rows($checklogin) == 1)
    {
        $row = mysqli_fetch_array($checklogin);
        $email = $row['EmailAddress'];
        $_SESSION['Username'] = $username;
        $_SESSION['EmailAddress'] = $email;
        $_SESSION['LoggedIn'] = 1;
        echo "Yes";
    }
    else
	    echo "No";
}
function user_remind()
{
	global $dbname;
	global $connection;

	if(!empty($_GET['email']))
	{
		$email = mysqli_real_escape_string($connection,$_GET['email']);
		$checklogin = mysqli_query($connection,"SELECT * FROM Users WHERE EmailAddress = '".$email."'");
		if(mysqli_num_rows($checklogin) == 1)
		{
			$row = mysqli_fetch_array($checklogin);
			$username = $row['Username'];
			
			// Generate password
			$length=16;
			$password="";
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
			$count = mb_strlen($chars);
			for ($i = 0, $result = ''; $i < $length; $i++) {
				$index = rand(0, $count - 1);
				$password .= mb_substr($chars, $index, 1);
			}
		
			$message = "User name: ".$username."\rPassword: ".$password;
			mail($email, 'BrainSpell password', $message,'From: brainspell.org <no-reply@brainspell.org>');

			$username = mysqli_real_escape_string($connection,$username);
			$password = md5(mysqli_real_escape_string($connection,$password));
			$email = mysqli_real_escape_string($connection,$email);
			$registerquery=mysqli_query($connection,"UPDATE Users SET Password = '".$password."' WHERE Username = '".$username."' AND EmailAddress = '".$email."'");
			if($registerquery)
			{
				echo "<p>You should receive shortly and e-mail with a new password";
				echo "<p><a href='login.php'>Go back</a> to the login window";
			}
			else
			{
				echo "<p>An unknown error occurred";
			}    	
		}
		else
		{
		/*
			<h1>Error</h1>
			<p>No account found with that e-mail
			<meta http-equiv='refresh' content='2;remind.php' />
		*/
		}
	}
	else
	{
	}
}
function user_logout()
{
	$_SESSION = array();
	session_destroy();
	echo "Yes";
}
function user_update($username)
{
	global $dbname;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$home = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/user.html");
	$tmp=str_replace("<!--Core-->",$home,$html);
	$html=$tmp;
	
	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;
	
	
	// public data
	$tmp=str_replace("<!--HomeUsername-->",$username,$html);
	$html=$tmp;

	// private data
	if(isset($_SESSION['Username']) && $_SESSION['Username']==$username)
	{
		$account=
			"<h2>Account Settings</h2>".
			"<div style=\"background-color:#f8f8f8;padding:1rem\">".
			"	<table style=\"width:100%;\">".
			"		<tr><td><label for=\"username\">User Name   </label></td><td><input value=\"".$username."\" type=\"text\" name=\"username\" id=\"username\" style=\"width:100%\"/></td></tr>".
			"		<tr><td><label for=\"password\">E-Mail      </label></td><td><input value=\"".$_SESSION['EmailAddress']."\" type=\"text\" name=\"email\" id=\"email\" style=\"width:100%\"/></td></tr>".
			"		<tr><td><label for=\"password\">New Password</label></td><td><input type=\"password\" name=\"password\" id=\"password\" style=\"width:100%\"/></td></tr>".
			"		<tr><td><label for=\"password\">Old Password</label></td><td><input type=\"password\" name=\"password\" id=\"password\" style=\"width:100%\"/></td></tr>".
			"		<tr><td></td><td><div id=\"warning\" style=\"color:red\"></div></td></tr>".
			"		<tr><td></td><td><input type=\"button\" name=\"login\" id=\"login\" value=\"Change\" onclick=\"checkLogin()\"/></td></tr>".
			"		<tr><td></td><td><input type=\"button\" name=\"delete\" id=\"login\" value=\"Delete my account\" onclick=\"checkLogin()\"/></td></tr>".
			"	</table>".
			"</div>";
		$tmp=str_replace("<!--AccountSettings-->",$account,$html);
		$html=$tmp;
	}
	
	print $html;
	
	/*

    if($_SESSION['Username']==$username)
    {
		$username = mysql_real_escape_string($_GET['username']);
		$email = mysql_real_escape_string($_GET['email']);
		$oldpassword = md5(mysql_real_escape_string($_GET['oldpassword']));
		$newpassword = md5(mysql_real_escape_string($_GET['newpassword']));
	
		$checkuser = mysql_query("SELECT * FROM Users WHERE Username = '".$username."' AND Password = '".$oldpassword."'");

		if(mysql_num_rows($checkuser) == 1)
		{
			$query="UPDATE Users SET Password = '".$newpassword."', EmailAddress = '".$email."' WHERE Username = '".$username."'";
			$registerquery=mysql_query($query);
			if($registerquery)
			{
				$message = "User name: ".$username."\rE-mail: ".$email;
				mail($email, 'BrainSpell account update', $message);
				echo "<h1>Data updated</h1>";
			}
			else
			{
				echo "<h1>Error</h1>";
				echo "<p>Sorry, your update failed. Please go back and try again.</p>";    
			}    	
		}
	}
	*/
}

function home()
{
	global $dbname;
	global $connection;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$home = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/home.html");
	$tmp=str_replace("<!--Core-->",$home,$html);
	$html=$tmp;
	
    $result=mysqli_query($connection,"SELECT COUNT(*) FROM Articles");
    $count=mysqli_fetch_assoc($result);
	$tmp=str_replace("<!--NumberOfArticlesIndexed-->",$count["COUNT(*)"],$html);
	$html=$tmp;

	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;

	print $html;
}
function about()
{
	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$about = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/about.html");
	$tmp=str_replace("<!--Core-->",$about,$html);
	$html=$tmp;
	
	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;

	print $html;
}
function blog()
{
	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$blog=file_get_contents("http://brainspell.org/php/blog.php");
	
	$tmp=str_replace("<!--Core-->",$blog,$html);
	$html=$tmp;
	
	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;

	print $html;
}
function download()
{
	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$download = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/download.html");
	$tmp=str_replace("<!--Core-->",$download,$html);
	$html=$tmp;
	
	if(isset($_SESSION['Username']))
		$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
	else
		$tmp=str_replace("<!--Username-->","",$html);
	$html=$tmp;

	if(isset($_SESSION['LoggedIn']))
		$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
	else
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;

	print $html;
}
function article($id)
{
	global $connection;
	
    // Try $id=PMID
	$result=mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$id."'");

    // Try $id=DOI
    if($result==false or mysqli_num_rows($result)==0)
	    $result=mysqli_query($connection,"SELECT * FROM Articles WHERE DOI = '".$id."'");
    
    if(mysqli_num_rows($result)==1)
    {
    	// Article found

		// article template
		$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
		$article = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/article.html");
		$tmp=str_replace("<!--Core-->",$article,$html);
		$html=$tmp;
		
		// configure login information
		if(isset($_SESSION['Username']))
			$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
		else
			$tmp=str_replace("<!--Username-->","",$html);
		$html=$tmp;
	
		if(isset($_SESSION['LoggedIn']))
			$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
		else
			$tmp=str_replace("<!--LoggedIn-->","0",$html);
		$html=$tmp;

       // get article data from database
        $record=mysqli_fetch_assoc($result);
		$fields=array("Title","Reference","Abstract","PMID","DOI","Metadata","Experiments");
		foreach ($fields as $i) {
    		if($i=="Experiments" || $i=="Metadata")
    			$tmp=str_replace("<!--".$i."-->",mysqli_real_escape_string($connection,strip_cdata($record[$i])),$html);
	    	else
	    		$tmp=str_replace("<!--".$i."-->",stripslashes(strip_cdata($record[$i])),$html);
	    	$html=$tmp;
	    }
		
		print $html;
    } else if (strncmp($id,"NIDM_",5)==0) {
    	// Manually entering NIDM article
    	
		$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
		$article = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/article.html");
		$tmp=str_replace("<!--Core-->",$article,$html);
		$html=$tmp;

		// configure login information
		if(isset($_SESSION['Username']))
			$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
		else
			$tmp=str_replace("<!--Username-->","",$html);
		$html=$tmp;
	
		if(isset($_SESSION['LoggedIn']))
			$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
		else
			$tmp=str_replace("<!--LoggedIn-->","0",$html);
		$html=$tmp;

		// configure article data
		$tmp=str_replace("<!--PMID-->",$id,$html);
		$html=$tmp;

		print $html;
    } else {
    	// Manually entering article by PMID
    	
		$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
		$article = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/article.html");
		$tmp=str_replace("<!--Core-->",$article,$html);
		$html=$tmp;

		// configure login information
		if(isset($_SESSION['Username']))
			$tmp=str_replace("<!--Username-->",$_SESSION['Username'],$html);
		else
			$tmp=str_replace("<!--Username-->","",$html);
		$html=$tmp;
	
		if(isset($_SESSION['LoggedIn']))
			$tmp=str_replace("<!--LoggedIn-->",$_SESSION['LoggedIn'],$html);
		else
			$tmp=str_replace("<!--LoggedIn-->","0",$html);
		$html=$tmp;

		// configure article data
		$tmp=str_replace("<!--PMID-->",$id,$html);
		$html=$tmp;

		print $html;
    }
    mysqli_free_result($result);
}
function article_json_pmid($pmid)
{
	global $dbname;
	global $connection;
	
    $result=mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$pmid."'");

    if(mysqli_num_rows($result)==1)
    {
        header('Content-Type: application/json');
		header("Access-Control-Allow-Origin: *");
        $record=mysqli_fetch_assoc($result);
		$fields=array("Title","Reference","Abstract","PMID","DOI","Metadata","Experiments");
		echo "{\n";
		for($k=0;$k<count($fields);$k++)
		{
    		$i=$fields[$k];
    		if($i=="Experiments" || $i=="Metadata" || $i=="Abstract")
    			$tmp=mysqli_real_escape_string($connection,strip_cdata($record[$i]));
	    	else
	    		$tmp=stripslashes(strip_cdata($record[$i]));
	    	
	    	if($k<count($fields)-1)
		    	echo "\"".$i."\":"."\"".$tmp."\",\n";
		    else
		    	echo "\"".$i."\":"."\"".$tmp."\"\n";
	    }
		echo "}\n";
    }
    else
    {
		header('HTTP/1.1 404 Not Found');
        echo "ERROR: There is not yet a record for the article with PMID ".$pmid."\n";
    }
    mysqli_free_result($result);
}
function article_json_doi($doi)
{
	global $dbname;
	global $connection;
	
    $result=mysqli_query($connection,"SELECT * FROM Articles WHERE DOI = '".$doi."'");

    if(mysqli_num_rows($result)==1)
    {
        header('Content-Type: application/json');
        $record=mysqli_fetch_assoc($result);
		$fields=array("Title","Reference","Abstract","PMID","DOI","Metadata","Experiments");
		echo "{\n";
		for($k=0;$k<count($fields);$k++)
		{
    		$i=$fields[$k];
    		if($i=="Experiments" || $i=="Metadata")
    			$tmp=mysqli_real_escape_string($connection,strip_cdata($record[$i]));
	    	else
	    		$tmp=stripslashes(strip_cdata($record[$i]));
	    	
	    	if($k<count($fields)-1)
		    	echo "\"".$i."\":"."\"".$tmp."\",\n";
		    else
		    	echo "\"".$i."\":"."\"".$tmp."\"\n";
	    }
		echo "}\n";
    }
    else
    {
		header('HTTP/1.1 404 Not Found');
        echo "ERROR: There is not yet a record for the article with DOI ".$doi."\n";
    }
    mysqli_free_result($result);
}
function search_lucene($query)
{
	global $connection;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$search = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/search.html");
	$tmp=str_replace("<!--Core-->",$search,$html);
	$html=$tmp;
	
	$index=getIndex_lucene();
	
	$hits=$index->find($query);
	if (!empty($hits))
	{
		// list article references
		$str='<table class="paper-list">';
		foreach($hits as $hit) {
			$str.="<tr class='paper-stuff'>\n";
			$str.="<td><input type='checkbox'></td>";
			$str.="<td><h3><a href=\""."/article/".$hit->PMID."\">";
			$str.=$hit->Title."</a></h3>\n";
			$str.="<p class=\"info\">".$hit->Reference." (".sprintf("%.0f",$hit->score*100)."%)</p>\n";
			$str.="</td></tr>\n";
		}
		$str=$str."</table>\n";
	}
	else
		$str="<p>The search <b>".$query."</b> did not match any articles.</p>";
	
	$qstr=htmlentities(stripslashes($query));
	$tmp=str_replace("<!--%SearchString%-->",$qstr,$html);
	$html=$tmp;

	$number=count($hits);
	$multiplicity=(count($hits)>1)?"s":"";
	$info=$number." article".$multiplicity." corresponding to the search \"".$qstr."\"";

	$tmp=str_replace("<!--%SearchResultsInfo%-->",$info,$html);
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;
	
	print $html;
}
function article_list($query)
{
	global $dbname;
	global $connection;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/base.html");
	$search = file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/search.html");
	$tmp=str_replace("<!--Core-->",$search,$html);
	$html=$tmp;
	
	$pmids=json_decode(json_decode($query));
	// list article references
	$str='<table class="paper-list">';
	foreach($pmids as $pmid) {
		$result=mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$pmid."'");
		if(mysqli_num_rows($result)==1)
		{
		   // get article data from database
			$record=mysqli_fetch_assoc($result);
			$str.="<tr class='paper-stuff'>\n";
			$str.="<td><input type='checkbox'></td>";
			$str.="<td><h3><a href=\""."/article/".$pmid."\">";
			$str.=$record["Title"]."</a></h3>\n";
			$str.="<p class=\"info\">".$record["Reference"]."</p>\n";
			$str.="</td></tr>\n";
		}
	}
	$str=$str."</table>\n";
	
	$tmp=str_replace("<!--%SearchString%-->","",$html);
	$html=$tmp;

	$number=count($pmids);
	$multiplicity=(count($pmids)>1)?"s":"";
	$info=$number." article".$multiplicity." in the article collection";

	$tmp=str_replace("<!--%SearchResultsInfo%-->",$info,$html);
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;
	
	print $html;
}
function getIndex_lucene()
{
	$dir=$_SERVER['DOCUMENT_ROOT']."/php/";
	chdir($dir);
	require_once 'Zend/Loader/Autoloader.php';
	require_once 'StandardAnalyzer/Analyzer/Standard/English.php';
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->setFallbackAutoloader(true);
	$loader->suppressNotFoundWarnings(false);
	
	Zend_Search_Lucene_Analysis_Analyzer::setDefault(new StandardAnalyzer_Analyzer_Standard_English());
	
	$path=$_SERVER['DOCUMENT_ROOT']."/php/LuceneIndex";
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

function add_article($query)
{
	global $dbname;
	global $connection;
		
	$cmd=$query['command'];
	
	switch($cmd)
	{
		case "new":
		{
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				echo "ERROR: Article is already in the database";
			}
			else
			{
				$q="INSERT INTO Articles (Title, Authors, Abstract, Reference, PMID, DOI, Experiments, Metadata) VALUES(";
				$q.= "'".mysqli_real_escape_string($connection,$query['Title'])."'"; // CDATA removed, escape_string added
				$q.=",'".mysqli_real_escape_string($connection,$query['Authors'])."'";	//mysql_real_escape_string($article['Authors']);
				$q.=",'".mysqli_real_escape_string($connection,$query['Abstract'])."'";	//mysql_real_escape_string($article['Abstract']);
				$q.=",'".mysqli_real_escape_string($connection,$query['Reference'])."'";	//mysql_real_escape_string($article['Reference']);
				$q.=",'".$query['PMID']."'";	//mysql_real_escape_string($article['PMID']);
				$q.=",'".$query['DOI']."'";	//mysql_real_escape_string($article['PMID']);
				$q.=",'".mysqli_real_escape_string($connection,$query['Experiments'])."'";	//mysql_real_escape_string($article['Experiments']); // CDATA removed, escape_string added
				$q.=",'".$query['Metadata']."')";	//mysql_real_escape_string($article['Metadata'])."')";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					echo "SUCCESS. Query: ".$q;
				else
					echo "ERROR: Unable to process query: ".$q;
		
				index_lucene($article,0);
			}
			mysqli_free_result($result);
			break;
		}
		case "experiments":
		{
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				$q="UPDATE Articles SET Experiments=";
				$q.= "'".mysqli_real_escape_string($connection,$query['Experiments'])."'"; // CDATA removed, escape_string added
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					echo "SUCCESS ";
				else
					echo "ERROR: Unable to process query: ".$q;
	
				index_lucene($article,0);
			}
			mysqli_free_result($result);
			break;
		}
		case "metadata":
		{
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				$q="UPDATE Articles SET Metadata=";
				$q.= "'".mysqli_real_escape_string($connection,$query['Metadata'])."'"; // CDATA removed, escape_string added
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					echo "SUCCESS ";
				else
					echo "ERROR: Unable to process query: ".$q;
	
				index_lucene($article,0);
			}
			mysqli_free_result($result);
			break;
		}
	}
}
function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
function get_article($query)
{
	global $dbname;
	global $connection;

    $cmd=$query['command'];
    $arg=$query['argument'];

    switch($cmd)
    {
		case "random":
		{
			srand(make_seed());
			$result=mysqli_query($connection,"SELECT UniqueID FROM Articles");
			$nrows=mysqli_num_rows($result);
			echo "<records>";
			for($i=0;$i<=$arg;$i++)
			{
				$irow=rand(1,$nrows);
				$err=mysqli_data_seek($result,$irow);
				$uniqueID=mysqli_fetch_row($result);
				$result2=mysqli_query($connection,"SELECT Title,Reference,PMID FROM Articles WHERE UniqueID = ".$uniqueID[0]);
				$record=mysqli_fetch_assoc($result2);
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
			$result=mysqli_query($connection,"SELECT Experiments FROM Articles WHERE PMID = '".$arg."'");
			$record=mysqli_fetch_assoc($result);
			echo $record["Experiments"];
			break;
		}
		case "metadata":
		{
			$result=mysqli_query($connection,"SELECT Metadata FROM Articles WHERE PMID = '".$arg."'");
			$record=mysqli_fetch_assoc($result);
			echo "<metadata>".$record["Metadata"]."</metadata>";
			break;
		}
	}
}
function get_concept($query)
{
	global $dbname;
	global $connection;

    $cmd=$query['command'];
    $arg=$query['argument'];

    switch($cmd)
    {
		case "concept":
		{
			$query="SELECT Definition FROM Concepts WHERE UPPER(Name) = UPPER('<![CDATA[".mysqli_real_escape_string($connection,$arg)."]]>')";
			$result=mysqli_query($connection,$query);
			$record=mysqli_fetch_assoc($result);
			$description="<Definition>".$record["Definition"]."</Definition>";
			mysqli_free_result($result);
			
			header("Access-Control-Allow-Origin: *");
			echo $description;
			break;
		}
	}
}
function add_log($query)
{
	global $dbname;
	global $connection;
	
	header("Access-Control-Allow-Origin: *");

	switch($query['type'])
	{
		case "Vote":
		{
			$vote=$query['TagVote'];
			
			// 1. Log user's vote
			//-------------------
			// Update/Insert user's vote
			$type="Vote;".$query['TagOntology'].";".$query['TagName'];
			$q="SELECT Data FROM Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '".$query['Experiment']."' AND";
			$q.="        Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if(mysqli_num_rows($result)>=1)	// pre-existing vote
			{
				$record=mysqli_fetch_assoc($result);
				$prevVote=$record["Data"];
				mysqli_free_result($result);
	
				if($vote==-1||$vote==1)	// down-voting or up-voting
				{
					// Update user's vote on tag
					$q="UPDATE Log SET Data = ".$vote." WHERE";
					$q.="    UserName = '".$query['UserName']."' AND";
					$q.="        PMID = '".$query['PMID']."' AND";
					$q.="  Experiment = '".$query['Experiment']."' AND";
					$q.="        Type = '".$type."'";
					$result = mysqli_query($connection,$q);
					if($result)
						echo "Successfully updated user's vote\n";
					else
						echo "ERROR: Unable to update user's vote: ".$q."\n";
				}
				else	// retracting vote
				{
					$q="DELETE FROM Log WHERE";
					$q.="    UserName = '".$query['UserName']."' AND";
					$q.="        PMID = '".$query['PMID']."' AND";
					$q.="  Experiment = '".$query['Experiment']."' AND";
					$q.="        Type = '".$type."'";
					$result = mysqli_query($connection,$q);
				}
			}
			else	// new vote
			{
				$prevVote=0;

				// Insert user's vote on tag
				$q="INSERT INTO Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'".$query['Experiment']."',";
				$q.="'".$type."',";
				$q.="'".$vote."')";
				$result = mysqli_query($connection,$q);
				if($result)
					echo "Successfully added user's vote\n";
				else
					echo "ERROR: Unable to add user's vote: ".$q."\n";
			}
				
			// 2. Update/Insert article:experiment:tag's agree/disagree stats
			//--------------------------------------------------------
			if($query['Experiment']<0)
			{
				// <0: Article level MeSH tag
				$result=mysqli_query($connection,"SELECT Metadata FROM Articles WHERE PMID = '".$query['PMID']."'");
				$record=mysqli_fetch_assoc($result);
				$metadata=json_decode(strip_cdata($record["Metadata"]));
				$tags=$metadata->meshHeadings;
		
				// Find tag (should always be present)
				$flagFound=0;
				foreach($tags as $t)
				{
					if($t->name==$query['TagName'])
					{						
						$flagFound=1;
						break;
					}
				}
				if(!isset($t->agree))
					$t->agree=0;
				if(!isset($t->disagree))
					$t->disagree=0;

				// Update tag statistics, based on user's vote
				echo "prevvote:".$prevVote." vote:".$vote." agree:".$t->agree." disagree:".$t->disagree."</br>";
				if($prevVote!=0)
				{
					if($vote==-2)	// User is retracting vote
					{
						if($prevVote==-1)
							$t->disagree--;
						if($prevVote==1)
							$t->agree--;
					}
					else
					if($vote!=$prevVote)	// User is changing mind
					{
						$t->agree+=$vote;
						$t->disagree-=$vote;
					}
				}
				else
				{
					// User is voting for the 1st time
					if($vote>0)
						$t->agree+=1;
					else
						$t->disagree+=1;
				}

				// Update experiments
				$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
				if(mysqli_num_rows($result)>=1)
				{
					$q="UPDATE Articles SET Metadata=";
					$q.= "'".json_encode($metadata)."'";
					$q.= " WHERE PMID='".$query['PMID']."'";
					$result2 = mysqli_query($connection,$q);
					if($result2)
						echo "SUCCESS ";
					else
						echo "ERROR: Unable to process query: ".$q;

					// index_lucene($article);
				}
				mysqli_free_result($result);
			}
			else
			{
				// >=0: Experiment level tag
				$result=mysqli_query($connection,"SELECT Experiments FROM Articles WHERE PMID = '".$query['PMID']."'");
				$record=mysqli_fetch_assoc($result);
				$exps=json_decode(strip_cdata($record["Experiments"]));
				
				// HISTORY: This used to be $exp=$exps[$query['Experiment']], when Log indexed experiments by array position
				for($i=0;$i<count($exps);$i++)
					if($exps[$i]->id==$query['Experiment'])
						break;
				if($i==count($exps))
				{
					echo "ERROR: Experiment ID ".$query['Experiment']." not found";
					continue;
				}
				$exp=$exps[$i];
		
				// Find tag, if already present
				$flagFound=0;
				if(isset($exp->tags))
				{
					for($j=0;$j<count($exp->tags);$j++)
					{
						$t=$exp->tags[$j];
						if(	$t->ontology==$query['TagOntology'] &&
							$t->name==$query['TagName'])
						{						
							$flagFound=1;
							break;
						}
					}
				}
				else
					$exp->tags=array();
		
				// If tag is added for the 1st time, initialise it
				if(!$flagFound)
				{
					$t=json_decode('{"name":"'.$query['TagName'].'","ontology":"'.$query['TagOntology'].'","agree":0,"disagree":0}');
					array_push($exp->tags,$t);
				}

				// Update tag statistics, based on user's vote
				if($prevVote!=0)
				{
					if($vote==-2)	//	User is retracting vote
					{
						if($prevVote==-1)
							$t->disagree--;
						if($prevVote==1)
							$t->agree--;
						// if user retracted the only vote, remove the tag
						if($flagFound && $t->disagree==0 && $t->agree==0)
							array_splice($exp->tags,$j,1);
					}
					else
					if($vote!=$prevVote)	// User is changing mind
					{
						$t->agree+=$vote;
						$t->disagree-=$vote;
					}
				}
				else
				{
					// User is voting for the 1st time
					if($vote>0)
						$t->agree+=1;
					else
						$t->disagree+=1;
				}
		
				// Update experiments
				$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
				if(mysqli_num_rows($result)>=1)
				{
					$q="UPDATE Articles SET Experiments=";
					$q.= "'".mysqli_real_escape_string($connection,json_encode($exps))."'"; // CDATA removed, escape_string added
					$q.= " WHERE PMID='".$query['PMID']."'";
					$result2 = mysqli_query($connection,$q);
					if($result2)
						echo "SUCCESS ";
					else
						echo "ERROR: Unable to process query: ".$q;

					// index_lucene($article);
				}
				mysqli_free_result($result);
			}
			break;
		}
		case "MarkTable":
		{
			// 1. Log user's vote
			//-------------------
			// Update/Insert user's vote
			$type="MarkTable";
			$q="SELECT Data FROM Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '".$query['Experiment']."' AND";
			$q.="        Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if(mysqli_num_rows($result)>=1)
			{
				$record=mysqli_fetch_assoc($result);
				$prevMark=$record["Data"];
				mysqli_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE Log SET Data = ".$query['Mark']." WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '".$query['Experiment']."' AND";
				$q.="        Type = '".$type."'";
				$result = mysqli_query($connection,$q);

				if($result)
					$out["userUpdate"]="Successfully updated user's table mark";
				else
					$out["userUpdate"]="ERROR: Unable to update user's table mark: ".$q;
			}
			else
			{
				$prevMark=-1;

				// Insert user's table mark to experiment
				$q="INSERT INTO Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'".$query['Experiment']."',";
				$q.="'".$type."',";
				$q.="'".$query['Mark']."')";
				$result = mysqli_query($connection,$q);

				if($result)
					$out["userInsert"]="Successfully added user's table mark";
				else
					$out["userInsert"]="ERROR: Unable to add user's table mark: ".$q;
			}
				
			// 2. Update/Insert article:experiment:tag's bad/ok stats
			//--------------------------------------------------------
			$result=mysqli_query($connection,"SELECT Experiments FROM Articles WHERE PMID = '".$query['PMID']."'");
			$record=mysqli_fetch_assoc($result);
			$exps=json_decode(strip_cdata($record["Experiments"]));
			
			
			// HISTORY: This used to be $exp=$exps[$query['Experiment']];
			for($i=0;$i<count($exps);$i++)
				if($exps[$i]->id==$query['Experiment'])
					break;
			if($i==count($exps))
			{
				echo "ERROR: Experiment ID ".$query['Experiment']." not found";
				continue;
			}
			$exp=$exps[$i];

			// Find previous table mark, if already present, create it otherwise
			if(isset($exp->markBadTable))
				$m=$exp->markBadTable;
			else
			{
				$m=json_decode('{"bad":0,"ok":0}');
				$exp->markBadTable=$m;
			}

			// Update table mark count, based on user's table mark
			$mark=$query['Mark'];
			if(!isset($m->bad))
				$m->bad=0;
			if(!isset($m->ok))
				$m->ok=0;
				
			// User is changing mind
			if($prevMark==-1)
			{
				// User is voting for the 1st time
				if($mark==1)
					$m->bad+=1;
				else
					$m->ok+=1;
			}
			else if($mark!=$prevMark)
			{
				$m->bad+=2*$mark-1;
				$m->ok+=1-2*$mark;
			}
			$out["result"]=$m;

			// Update experiments
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				$q="UPDATE Articles SET Experiments=";
				$q.= "'".mysqli_real_escape_string($connection,json_encode($exps))."'"; // cdata removed, escape_string added
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					$out["articleUpdate"]="Successfully updated experiment with table mark ";
				else
					$out["articleUpdate"]="ERROR: Unable to add table mark: ".$q;

				// index_lucene($article);
			}
			mysqli_free_result($result);

			echo json_encode($out);
			break;
		}
		case "StereoSpace":
		{
			// 1. Log user's vote
			//-------------------
			// Update/Insert user's vote
			$type="StereoSpace";
			$q="SELECT Data FROM Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '-1' AND";
			$q.="        Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if(mysqli_num_rows($result)>=1)
			{
				$record=mysqli_fetch_assoc($result);
				$prevSpace=$record["Data"];
				mysqli_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE Log SET Data = '".$query['StereoSpace']."' WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '-1' AND";
				$q.="        Type = '".$type."'";
				$result = mysqli_query($connection,$q);

				if($result)
					$out["userUpdate"]="Successfully updated user's stereotaxic space";
				else
					$out["userUpdate"]="ERROR: Unable to update user's stereotaxic space: ".$q;
			}
			else
			{
				$prevSpace=-1;

				// Insert user's table mark to experiment
				$q="INSERT INTO Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'-1',";
				$q.="'".$type."',";
				$q.="'".$query['StereoSpace']."')";
				$result = mysqli_query($connection,$q);

				if($result)
					$out["userInsert"]="Successfully added user's stereotaxic space";
				else
					$out["userInsert"]="ERROR: Unable to add user's stereotaxic space: ".$q;
			}
				
			// 2. Update/Insert article stereotaxic space
			//-------------------------------------------
			$result=mysqli_query($connection,"SELECT Metadata FROM Articles WHERE PMID = '".$query['PMID']."'");
			$record=mysqli_fetch_assoc($result);
			$meta=json_decode($record["Metadata"]);
	
			// Find previous stereotaxic space data, if already present, create it otherwise
			if(isset($meta->stereo))
				$m=$meta->stereo;
			else
			{
				$m=json_decode('{"Talairach":0,"MNI":0}');
				$meta->stereo=$m;
			}

			// Update stereotaxic space counts, based on user's stereotaxic space
			$space=$query['StereoSpace'];
			if(!isset($m->Talairach))
				$m->Talairach=0;
			if(!isset($m->MNI))
				$m->MNI=0;
				
			// User is changing her mind
			if($prevSpace==-1)
			{
				// User is voting for the 1st time
				$m->$space++;
			}
			else if($space!=$prevSpace)
			{
				$m->$prevSpace--;
				$m->$space++;
			}
			$out["result"]=$m;

			// Update experiments
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				$q="UPDATE Articles SET Metadata=";
				$q.= "'".mysqli_real_escape_string($connection,json_encode($meta))."'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					$out["articleUpdate"]="Successfully updated article with stereotaxic space ";
				else
					$out["articleUpdate"]="ERROR: Unable to add stereotaxic space: ".$q;

				// index_lucene($article);
			}
			mysqli_free_result($result);

			echo json_encode($out);
			break;
		}
		case "NSubjects":
		{
			// 1. Log user's nsubjects
			//-------------------
			// Update/Insert user's nsubjects
			$type="NSubjects";
			$q="SELECT Data FROM Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '-1' AND";
			$q.="        Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if(mysqli_num_rows($result)>=1)
			{
				$record=mysqli_fetch_assoc($result);
				$prevNSubjects=$record["Data"];
				mysqli_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE Log SET Data = '".$query['NSubjects']."' WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '-1' AND";
				$q.="        Type = '".$type."'";
				$result = mysqli_query($connection,$q);

				if($result)
					$out["userUpdate"]="Successfully updated user's nsubjects";
				else
					$out["userUpdate"]="ERROR: Unable to update user's nsubjects: ".$q;
			}
			else
			{
				$prevNSubjects=-1;

				// Insert user's table mark to experiment
				$q="INSERT INTO Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'-1',";
				$q.="'".$type."',";
				$q.="'".$query['NSubjects']."')";
				$result = mysqli_query($connection,$q);

				if($result)
					$out["userInsert"]="Successfully added user's nsubjects";
				else
					$out["userInsert"]="ERROR: Unable to add user's nsubjects: ".$q;
			}
				
			// 2. Update/Insert article nsubjects
			//-------------------------------------------
			$result=mysqli_query($connection,"SELECT Metadata FROM Articles WHERE PMID = '".$query['PMID']."'");
			$record=mysqli_fetch_assoc($result);
			$meta=json_decode($record["Metadata"]);
	
			// Find previous stereotaxic space data, if already present, create it otherwise
			if(!isset($meta->nsubjects))
				$meta->nsubjects=array();

			// Update nsubjects array, based on user's nsubjects
			$nsubjects=$query['NSubjects'];

			// User is changing her mind
			if($prevNSubjects==-1)
			{
				// User is voting for the 1st time
				array_push($meta->nsubjects,$nsubjects);
			}
			else if($nsubjects!=$prevNSubjects)
			{
				/* find $prevNSubjects among previous values and change it by $nsubjects */
				$key=array_search($prevNSubjects,$meta->nsubjects);
				$meta->nsubjects[$key]=$nsubjects;
			}
			$out["result"]=$meta->nsubjects;

			// Update experiments
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				$q="UPDATE Articles SET Metadata=";
				$q.= "'".mysqli_real_escape_string($connection,json_encode($meta))."'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					$out["articleUpdate"]="Successfully updated article with nsubjects";
				else
					$out["articleUpdate"]="ERROR: Unable to add nsubjects: ".$q;

				// index_lucene($article);
			}
			mysqli_free_result($result);

			echo json_encode($out);
			break;
		}
		case "Comment":
		{
			// 1. Log user's comment
			//----------------------
			$type="Comment";
			$q="INSERT INTO Log (UserName,PMID,Experiment,Type,Data) VALUES(";
			$q.="'".$query['UserName']."',";
			$q.="'".$query['PMID']."',";
			$q.="'-1',";
			$q.="'".$type."',";
			$q.="'".$query['Comment']."')";
			$result = mysqli_query($connection,$q);
			if($result)
				$out["userInsert"]="Successfully added user's comment";
			else
				$out["userInsert"]="ERROR: Unable to add user's comment: ".$q;

			// 2. Insert comment in the article
			//---------------------------------
			$result=mysqli_query($connection,"SELECT Metadata FROM Articles WHERE PMID = '".$query['PMID']."'");
			$record=mysqli_fetch_assoc($result);
			$meta=json_decode($record["Metadata"]);
	
			// Find previous comments, if already present, create it otherwise
			if(!isset($meta->comments))
				$meta->comments=array();

			// Add new comment
			//$comment=json_decode(stripslashes($query['Comment']));
			$comment=json_decode($query['Comment']);
			array_push($meta->comments,$comment);
			$out["result"]=json_encode($comment);

			// Update metadata
			$result = mysqli_query($connection,"SELECT * FROM Articles WHERE PMID = '".$query['PMID']."'");
			if(mysqli_num_rows($result)>=1)
			{
				$q="UPDATE Articles SET Metadata=";
				$q.= "'".mysqli_real_escape_string($connection,json_encode($meta))."'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysqli_query($connection,$q);
				if($result2)
					$out["articleUpdate"]="Successfully updated article with comment";
				else
					$out["articleUpdate"]="ERROR: Unable to add comment: ".$q;

				// index_lucene($article);
			}
			mysqli_free_result($result);

			echo json_encode($out);
			break;
		}
		case "KeyValue":	// generic key/value logger
		{
			$type=$query["Key"];
			$data=$query["Value"];
			
			if(!isset($query['store']))
				$store="append";
			else
				$store=$query['store']; /* 2 types: append and replace, append by default */
			
			if($store=="replace") {
				$q = "SELECT * FROM Log WHERE UserName = '".$query["UserName"]."' AND PMID = '".$query["PMID"]."' AND Experiment = '".$query["Experiment"]."' AND Type = '".$type."'";
				$result = mysqli_query($connection,$q);
				if($result) {
					$q="DELETE FROM Log WHERE UserName = '".$query['UserName']."' AND PMID = '".$query['PMID']."' AND Experiment = '".$query['Experiment']."' AND Type = '".$type."'";
					$result = mysqli_query($connection,$q);
				}
			}
			
			$q="INSERT INTO Log (UserName,PMID,Experiment,Type,Data) VALUES(";
			$q.="'".$query['UserName']."',";
			$q.="'".$query['PMID']."',";
			$q.="'".$query['Experiment']."',";
			$q.="'".$type."',";
			$q.="'".$data."')";
			$result = mysqli_query($connection,$q);
			if($result)
				$out["userInsert"]="Successfully logged key/value: ".$q;
			else
				$out["userInsert"]="ERROR: Unable log key/value: ".$q;
			echo json_encode($out);
		}
	}
}
function get_log($query)
{
	global $dbname;
	global $connection;
	
	header("Access-Control-Allow-Origin: *");
	
	switch($query['type'])
	{
		case "Vote":
		{
			$type="Vote;".$query['TagOntology'].";".$query['TagName'];
			$q = "SELECT * FROM Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '".$query["Experiment"]."'";
			$q.= " AND Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if($result)
			{
				$record=mysqli_fetch_assoc($result);
				$vote="<TagVote>".$record["Data"]."</TagVote>";
				mysqli_free_result($result);

				echo $vote;
			}
			else
				echo "<TagVote></TagVote>";
			break;
		}
		case "MarkTable":
		{
			$type="MarkTable";
			$q = "SELECT * FROM Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '".$query["Experiment"]."'";
			$q.= " AND Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if($result)
			{
				$record=mysqli_fetch_assoc($result);
				$vote="<MarkTable>".$record["Data"]."</MarkTable>";
				mysqli_free_result($result);

				echo $vote;
			}
			else
				echo "<MarkTable>-1</MarkTable>";
			break;
		}
		case "StereoSpace":
		{
			$type="StereoSpace";
			$q = "SELECT * FROM Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '-1'";
			$q.= " AND Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if($result)
			{
				$record=mysqli_fetch_assoc($result);
				$vote="<StereoSpace>".$record["Data"]."</StereoSpace>";
				mysqli_free_result($result);

				echo $vote;
			}
			else
				echo "<StereoSpace>-1</StereoSpace>";
			break;
		}
		case "NSubjects":
		{
			$type="NSubjects";
			$q = "SELECT * FROM Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '-1'";
			$q.= " AND Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if($result)
			{
				$record=mysqli_fetch_assoc($result);
				$nsubjects="<NSubjects>".$record["Data"]."</NSubjects>";
				mysqli_free_result($result);

				echo $nsubjects;
			}
			else
				echo "<NSubjects>-1</NSubjects>";
			break;
		}
		default:
		{
			$type=$query['type'];
			$q = "SELECT * FROM Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '".$query["Experiment"]."'";
			$q.= " AND Type = '".$type."'";
			$result = mysqli_query($connection,$q);
			if($result)
			{
				$record=mysqli_fetch_assoc($result);
				$data=$record["Data"];
				mysqli_free_result($result);

				echo $data;
			}
			else {
				echo "{result:-1}";
			}
		}
	}
}
?>
