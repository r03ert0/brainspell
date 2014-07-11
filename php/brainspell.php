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
	return preg_replace('/<!\[CDATA\[(.*)\]\]>/', '$1', $str);
}
function user_register()
{
	global $dbname;

	$username = mysql_real_escape_string($_GET['username']);
	$password = md5(mysql_real_escape_string($_GET['password']));
	$email = mysql_real_escape_string($_GET['email']);

	 $checkusername = mysql_query("SELECT * FROM ".$dbname.".Users WHERE Username = '".$username."'");
	 if(mysql_num_rows($checkusername) == 1)
		echo "Exists";
	 else
	 {
		$registerquery = mysql_query("INSERT INTO ".$dbname.".Users (Username, Password, EmailAddress) VALUES('".$username."', '".$password."', '".$email."')");
		if($registerquery)
		{
			$checklogin = mysql_query("SELECT * FROM ".$dbname.".Users WHERE Username = '".$username."' AND Password = '".$password."'");
			if(mysql_num_rows($checklogin) == 1)
			{
				$row = mysql_fetch_array($checklogin);
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

    $username = mysql_real_escape_string($_GET['username']);
    $password = md5(mysql_real_escape_string($_GET['password']));
    
    $checklogin = mysql_query("SELECT * FROM ".$dbname.".Users WHERE Username = '".$username."' AND Password = '".$password."'");
    if(mysql_num_rows($checklogin) == 1)
    {
        $row = mysql_fetch_array($checklogin);
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

	if(!empty($_GET['email']))
	{
		$email = mysql_real_escape_string($_GET['email']);
		$checklogin = mysql_query("SELECT * FROM ".$dbname.".Users WHERE EmailAddress = '".$email."'");
		if(mysql_num_rows($checklogin) == 1)
		{
			$row = mysql_fetch_array($checklogin);
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
			mail($email, 'BrainSpell password', $message);

			$username = mysql_real_escape_string($username);
			$password = md5(mysql_real_escape_string($password));
			$email = mysql_real_escape_string($email);
			$registerquery=mysql_query("UPDATE ".$dbname.".Users SET Password = '".$password."' WHERE Username = '".$username."' AND EmailAddress = '".$email."'");
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
	global $rootdir;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$home = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/user.html");
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
	
		$checkuser = mysql_query("SELECT * FROM ".$dbname.".Users WHERE Username = '".$username."' AND Password = '".$oldpassword."'");

		if(mysql_num_rows($checkuser) == 1)
		{
			$query="UPDATE ".$dbname.".Users SET Password = '".$newpassword."', EmailAddress = '".$email."' WHERE Username = '".$username."'";
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
	$html=$tmp;

	print $html;
}
function blog()
{
	global $rootdir;

	$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].$rootdir."templates/base.html");
	$blog=file_get_contents("http://brainspell.org/php/blog.php");
	
	$tmp=str_replace("<!--Core-->",$blog,$html);
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
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
		$fields=array("Title","Reference","Abstract","PMID","DOI","NeuroSynthID","Metadata","Experiments");
		foreach ($fields as $i):
    		if($i=="Experiments" || $i=="Metadata")
    			$tmp=str_replace("<!--".$i."-->",mysql_real_escape_string(strip_cdata($record[$i])),$html);
	    	else
	    		$tmp=str_replace("<!--".$i."-->",stripslashes(strip_cdata($record[$i])),$html);
	    	$html=$tmp;
		endforeach;
		
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
    else
    {
		header('HTTP/1.1 404 Not Found');
        echo "ERROR: There is not yet a record for the article with PMID ".$pmid."\n";
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
		$tmp=str_replace("<!--LoggedIn-->","0",$html);
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
	
	switch($query['type'])
	{
		case "Vote":
		{
			// 1. Log user's vote
			//-------------------
			// Update/Insert user's vote
			$type="Vote;".$query['TagOntology'].";".$query['TagName'];
			$q="SELECT Data FROM ".$dbname.".Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '".$query['Experiment']."' AND";
			$q.="        Type = '".$type."'";
			$result = mysql_query($q);
			if(mysql_num_rows($result)>=1)
			{
				$record=mysql_fetch_assoc($result);
				$prevVote=$record["Data"];
				mysql_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE ".$dbname.".Log SET Data = ".$query['TagVote']." WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '".$query['Experiment']."' AND";
				$q.="        Type = '".$type."'";
				$result = mysql_query($q);
				if($result)
					echo "Successfully updated user's vote\n";
				else
					echo "ERROR: Unable to update user's vote: ".$q."\n";
			}
			else
			{
				$prevVote=0;

				// Insert user's vote on tag
				$q="INSERT INTO ".$dbname.".Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'".$query['Experiment']."',";
				$q.="'".$type."',";
				$q.="'".$query['TagVote']."')";
				$result = mysql_query($q);
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
				$result=mysql_query("SELECT Metadata FROM ".$dbname.".Articles WHERE PMID = ".$query['PMID']);
				$record=mysql_fetch_assoc($result);
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
				$vote=$query['TagVote'];
				if($prevVote!=0)
				{
					// User is changing mind
					if($vote!=$prevVote)
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
				$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
				if(mysql_num_rows($result)>=1)
				{
					$q="UPDATE ".$dbname.".Articles SET Metadata=";
					$q.= "'".json_encode($metadata)."'";
					$q.= " WHERE PMID='".$query['PMID']."'";
					$result2 = mysql_query($q);
					if($result2)
						echo "SUCCESS ";
					else
						echo "ERROR: Unable to process query: ".$q;

					// index_lucene($article);
				}
				mysql_free_result($result);
			}
			else
			{
				// >=0: Experiment level tag
				$result=mysql_query("SELECT Experiments FROM ".$dbname.".Articles WHERE PMID = ".$query['PMID']);
				$record=mysql_fetch_assoc($result);
				$exps=json_decode(strip_cdata($record["Experiments"]));
				$exp=$exps[$query['Experiment']];
		
				// Find tag, if already present
				$flagFound=0;
				if(isset($exp->tags))
				{
					foreach($exp->tags as $t)
					{
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
				$vote=$query['TagVote'];
				if($prevVote!=0)
				{
					// User is changing mind
					if($vote!=$prevVote)
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
				$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
				if(mysql_num_rows($result)>=1)
				{
					$q="UPDATE ".$dbname.".Articles SET Experiments=";
					$q.= "'<![CDATA[".json_encode($exps)."]]>'";
					$q.= " WHERE PMID='".$query['PMID']."'";
					$result2 = mysql_query($q);
					if($result2)
						echo "SUCCESS ";
					else
						echo "ERROR: Unable to process query: ".$q;

					// index_lucene($article);
				}
				mysql_free_result($result);
			}
			break;
		}
		case "MarkTable":
		{
			// 1. Log user's vote
			//-------------------
			// Update/Insert user's vote
			$type="MarkTable";
			$q="SELECT Data FROM ".$dbname.".Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '".$query['Experiment']."' AND";
			$q.="        Type = '".$type."'";
			$result = mysql_query($q);
			if(mysql_num_rows($result)>=1)
			{
				$record=mysql_fetch_assoc($result);
				$prevMark=$record["Data"];
				mysql_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE ".$dbname.".Log SET Data = ".$query['Mark']." WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '".$query['Experiment']."' AND";
				$q.="        Type = '".$type."'";
				$result = mysql_query($q);

				if($result)
					$out["userUpdate"]="Successfully updated user's table mark";
				else
					$out["userUpdate"]="ERROR: Unable to update user's table mark: ".$q;
			}
			else
			{
				$prevMark=-1;

				// Insert user's table mark to experiment
				$q="INSERT INTO ".$dbname.".Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'".$query['Experiment']."',";
				$q.="'".$type."',";
				$q.="'".$query['Mark']."')";
				$result = mysql_query($q);

				if($result)
					$out["userInsert"]="Successfully added user's table mark";
				else
					$out["userInsert"]="ERROR: Unable to add user's table mark: ".$q;
			}
				
			// 2. Update/Insert article:experiment:tag's bad/ok stats
			//--------------------------------------------------------
			$result=mysql_query("SELECT Experiments FROM ".$dbname.".Articles WHERE PMID = ".$query['PMID']);
			$record=mysql_fetch_assoc($result);
			$exps=json_decode(strip_cdata($record["Experiments"]));
			$exp=$exps[$query['Experiment']];
	
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
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				$q="UPDATE ".$dbname.".Articles SET Experiments=";
				$q.= "'<![CDATA[".json_encode($exps)."]]>'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysql_query($q);
				if($result2)
					$out["articleUpdate"]="Successfully updated experiment with table mark ";
				else
					$out["articleUpdate"]="ERROR: Unable to add table mark: ".$q;

				// index_lucene($article);
			}
			mysql_free_result($result);

			echo json_encode($out);
			break;
		}
		case "StereoSpace":
		{
			// 1. Log user's vote
			//-------------------
			// Update/Insert user's vote
			$type="StereoSpace";
			$q="SELECT Data FROM ".$dbname.".Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '-1' AND";
			$q.="        Type = '".$type."'";
			$result = mysql_query($q);
			if(mysql_num_rows($result)>=1)
			{
				$record=mysql_fetch_assoc($result);
				$prevSpace=$record["Data"];
				mysql_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE ".$dbname.".Log SET Data = '".$query['StereoSpace']."' WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '-1' AND";
				$q.="        Type = '".$type."'";
				$result = mysql_query($q);

				if($result)
					$out["userUpdate"]="Successfully updated user's stereotaxic space";
				else
					$out["userUpdate"]="ERROR: Unable to update user's stereotaxic space: ".$q;
			}
			else
			{
				$prevSpace=-1;

				// Insert user's table mark to experiment
				$q="INSERT INTO ".$dbname.".Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'-1',";
				$q.="'".$type."',";
				$q.="'".$query['StereoSpace']."')";
				$result = mysql_query($q);

				if($result)
					$out["userInsert"]="Successfully added user's stereotaxic space";
				else
					$out["userInsert"]="ERROR: Unable to add user's stereotaxic space: ".$q;
			}
				
			// 2. Update/Insert article stereotaxic space
			//-------------------------------------------
			$result=mysql_query("SELECT Metadata FROM ".$dbname.".Articles WHERE PMID = ".$query['PMID']);
			$record=mysql_fetch_assoc($result);
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
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				$q="UPDATE ".$dbname.".Articles SET Metadata=";
				$q.= "'".mysql_real_escape_string(json_encode($meta))."'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysql_query($q);
				if($result2)
					$out["articleUpdate"]="Successfully updated article with stereotaxic space ";
				else
					$out["articleUpdate"]="ERROR: Unable to add stereotaxic space: ".$q;

				// index_lucene($article);
			}
			mysql_free_result($result);

			echo json_encode($out);
			break;
		}
		case "NSubjects":
		{
			// 1. Log user's nsubjects
			//-------------------
			// Update/Insert user's nsubjects
			$type="NSubjects";
			$q="SELECT Data FROM ".$dbname.".Log WHERE";
			$q.="    UserName = '".$query['UserName']."' AND";
			$q.="        PMID = '".$query['PMID']."' AND";
			$q.="  Experiment = '-1' AND";
			$q.="        Type = '".$type."'";
			$result = mysql_query($q);
			if(mysql_num_rows($result)>=1)
			{
				$record=mysql_fetch_assoc($result);
				$prevNSubjects=$record["Data"];
				mysql_free_result($result);
		
				// Update user's vote on tag
				$q="UPDATE ".$dbname.".Log SET Data = '".$query['NSubjects']."' WHERE";
				$q.="    UserName = '".$query['UserName']."' AND";
				$q.="        PMID = '".$query['PMID']."' AND";
				$q.="  Experiment = '-1' AND";
				$q.="        Type = '".$type."'";
				$result = mysql_query($q);

				if($result)
					$out["userUpdate"]="Successfully updated user's nsubjects";
				else
					$out["userUpdate"]="ERROR: Unable to update user's nsubjects: ".$q;
			}
			else
			{
				$prevNSubjects=-1;

				// Insert user's table mark to experiment
				$q="INSERT INTO ".$dbname.".Log (UserName,PMID,Experiment,Type,Data) VALUES(";
				$q.="'".$query['UserName']."',";
				$q.="'".$query['PMID']."',";
				$q.="'-1',";
				$q.="'".$type."',";
				$q.="'".$query['NSubjects']."')";
				$result = mysql_query($q);

				if($result)
					$out["userInsert"]="Successfully added user's nsubjects";
				else
					$out["userInsert"]="ERROR: Unable to add user's nsubjects: ".$q;
			}
				
			// 2. Update/Insert article nsubjects
			//-------------------------------------------
			$result=mysql_query("SELECT Metadata FROM ".$dbname.".Articles WHERE PMID = ".$query['PMID']);
			$record=mysql_fetch_assoc($result);
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
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				$q="UPDATE ".$dbname.".Articles SET Metadata=";
				$q.= "'".mysql_real_escape_string(json_encode($meta))."'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysql_query($q);
				if($result2)
					$out["articleUpdate"]="Successfully updated article with nsubjects";
				else
					$out["articleUpdate"]="ERROR: Unable to add nsubjects: ".$q;

				// index_lucene($article);
			}
			mysql_free_result($result);

			echo json_encode($out);
			break;
		}
		case "Comment":
		{
			// 1. Log user's comment
			//----------------------
			$type="Comment";
			$q="INSERT INTO ".$dbname.".Log (UserName,PMID,Experiment,Type,Data) VALUES(";
			$q.="'".$query['UserName']."',";
			$q.="'".$query['PMID']."',";
			$q.="'-1',";
			$q.="'".$type."',";
			$q.="'".$query['Comment']."')";
			$result = mysql_query($q);
			if($result)
				$out["userInsert"]="Successfully added user's comment";
			else
				$out["userInsert"]="ERROR: Unable to add user's comment: ".$q;

			// 2. Insert comment in the article
			//---------------------------------
			$result=mysql_query("SELECT Metadata FROM ".$dbname.".Articles WHERE PMID = ".$query['PMID']);
			$record=mysql_fetch_assoc($result);
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
			$result = mysql_query("SELECT * FROM ".$dbname.".Articles WHERE PMID = '".$query['PMID']."'");
			if(mysql_num_rows($result)>=1)
			{
				$q="UPDATE ".$dbname.".Articles SET Metadata=";
				$q.= "'".mysql_real_escape_string(json_encode($meta))."'";
				$q.= " WHERE PMID='".$query['PMID']."'";
				$result2 = mysql_query($q);
				if($result2)
					$out["articleUpdate"]="Successfully updated article with comment";
				else
					$out["articleUpdate"]="ERROR: Unable to add comment: ".$q;

				// index_lucene($article);
			}
			mysql_free_result($result);

			echo json_encode($out);
			break;
		}
	}
}
function get_log($query)
{
	global $dbname;
	
	switch($query['type'])
	{
		case "Vote":
		{
			$type="Vote;".$query['TagOntology'].";".$query['TagName'];
			$q = "SELECT * FROM ".$dbname.".Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '".$query["Experiment"]."'";
			$q.= " AND Type = '".$type."'";
			$result = mysql_query($q);
			if($result)
			{
				$record=mysql_fetch_assoc($result);
				$vote="<TagVote>".$record["Data"]."</TagVote>";
				mysql_free_result($result);

				echo $vote;
			}
			else
				echo "<TagVote></TagVote>";
			break;
		}
		case "MarkTable":
		{
			$type="MarkTable";
			$q = "SELECT * FROM ".$dbname.".Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '".$query["Experiment"]."'";
			$q.= " AND Type = '".$type."'";
			$result = mysql_query($q);
			if($result)
			{
				$record=mysql_fetch_assoc($result);
				$vote="<MarkTable>".$record["Data"]."</MarkTable>";
				mysql_free_result($result);

				echo $vote;
			}
			else
				echo "<MarkTable>-1</MarkTable>";
			break;
		}
		case "StereoSpace":
		{
			$type="StereoSpace";
			$q = "SELECT * FROM ".$dbname.".Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '-1'";
			$q.= " AND Type = '".$type."'";
			$result = mysql_query($q);
			if($result)
			{
				$record=mysql_fetch_assoc($result);
				$vote="<StereoSpace>".$record["Data"]."</StereoSpace>";
				mysql_free_result($result);

				echo $vote;
			}
			else
				echo "<StereoSpace>-1</StereoSpace>";
			break;
		}
		case "NSubjects":
		{
			$type="NSubjects";
			$q = "SELECT * FROM ".$dbname.".Log ";
			$q.= "WHERE UserName = '".$query["UserName"]."'";
			$q.= " AND PMID = '".$query["PMID"]."'";
			$q.= " AND Experiment = '-1'";
			$q.= " AND Type = '".$type."'";
			$result = mysql_query($q);
			if($result)
			{
				$record=mysql_fetch_assoc($result);
				$nsubjects="<NSubjects>".$record["Data"]."</NSubjects>";
				mysql_free_result($result);

				echo $nsubjects;
			}
			else
				echo "<NSubjects>-1</NSubjects>";
			break;
		}
	}
}
?>
