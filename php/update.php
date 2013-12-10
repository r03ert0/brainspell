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
if(empty($_SESSION['LoggedIn']) || empty($_SESSION['Username']))
{
    ?>
    <meta http-equiv="refresh" content="0;login.php">
    <?php
}
if(!empty($_POST['username']) && !empty($_POST['oldpassword']))
{
    $username = mysql_real_escape_string($_POST['username']);
    $email = mysql_real_escape_string($_POST['email']);
    $oldpassword = md5(mysql_real_escape_string($_POST['oldpassword']));
    $newpassword = md5(mysql_real_escape_string($_POST['newpassword']));
    
    $checkuser = mysql_query("SELECT * FROM ".$dbname.".Users WHERE Username = '".$username."' AND Password = '".$oldpassword."'");

    if(mysql_num_rows($checkuser) == 1)
    {
        $query="UPDATE ".$dbname.".Users SET Password = '".$newpassword."', EmailAddress = '".$email."' WHERE Username = '".$username."'";
        $registerquery=mysql_query($query);
        if($registerquery)
        {
            $message = "User name: ".$username."\rE-mail: ".$email;
            mail($email, 'Siphonophore account update', $message);
            echo "<h1>Data updated</h1>";
        }
        else
        {
            echo "<h1>Error</h1>";
            echo "<p>Sorry, your update failed. Please go back and try again.</p>";    
        }    	
    }
}
else
{
	?>
    
    <div>
    <form method="post" action="update.php" name="updateform" id="updateform">
    <h1>Update</h1>
    <table style="width:100%;">
    <tr><td><label for="username">Username</label></td><td><input type="text" name="username" id="username" value="<?php echo $_SESSION['Username']; ?>" readonly style="width:100%"/></td></tr>
    <tr><td><label for="email">E-mail</label></td><td><input type="email" name="email" id="email" required value="<?php echo $_SESSION['EmailAddress']; ?>" style="width:100%"/></td></tr>
    <tr><td><label for="newpassword">New Password</label></td><td><input type="password" name="newpassword"id="newpassword" style="width:100%"/></td></tr>
    <tr><td><label for="oldpassword">Old Password</label></td><td><input type="password" name="oldpassword" id="oldpassword" style="width:100%"/></td></tr>
    </table>
    <p><input type="submit" name="update" id="update" value="Update" /></p>
    </form>
    </div>
    
   <?php
}
?>
</body>
</html>