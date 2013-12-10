<?php include "base.php"; ?>
<html>  
<head>
<style media="screen" type="text/css">
a {
	cursor:pointer;
	color:#d70;
	text-decoration:none
}
a:hover,a:focus {
	text-decoration:underline
}
div {
	width:400px;
	height:400px;
	border:1 solid white;
	padding:20px;
	font-family:sans-serif;
	position:fixed;
	left:50%;
	top:50%;
	margin-top:-200px;
	margin-left:-200px;
	box-shadow:2px 2px 20px rgba(0,0,0,.7)
}
</style>
</head>
  
<body>  
<?php
if(!empty($_POST['username']) && !empty($_POST['password']))
{
	$username = mysql_real_escape_string($_POST['username']);
    $password = md5(mysql_real_escape_string($_POST['password']));
    $email = mysql_real_escape_string($_POST['email']);
    
	 $checkusername = mysql_query("SELECT * FROM ".$dbname.".Users WHERE Username = '".$username."'");
     if(mysql_num_rows($checkusername) == 1)
     {
     	echo "<h1>Error</h1>";
        echo "<p>Sorry, that username is taken. Please go back and try again.</p>";
     }
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
			?>
				<meta http-equiv='refresh' content='0;url=/' />
			<?php
			}
        }
        else
        {
     		echo "<h1>Error</h1>";
        	echo "<p>Sorry, your registration failed. Please go back and try again.</p>";    
        }    	
     }
}
else
{
	?>
    
    <div>
    <form method="post" action="register.php" name="registerform" id="registerform">
    <h1>Register</h1>
    <table style="width:100%;">
    <tr><td><label for="username">Username</label></td><td><input type="text" name="username" id="username"  style="width:100%"/></td></tr>
    <tr><td><label for="password">Password</label></td><td><input type="password" name="password" id="password"  style="width:100%"/></td></tr>
    <tr><td><label for="email">E-mail</label></td><td><input type="email" name="email" id="email" required  style="width:100%"/></td></tr>
    </table>
    <p><input type="submit" name="register" id="register" value="Register" /></p>
    </form>
    </div>
    
   <?php
}
?>
</body>
</html>