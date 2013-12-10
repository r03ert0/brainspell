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
function generatePassword($length = 16) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}

if(!empty($_POST['email']))
{
    $email = mysql_real_escape_string($_POST['email']);
    $checklogin = mysql_query("SELECT * FROM ".$dbname.".Users WHERE EmailAddress = '".$email."'");
    if(mysql_num_rows($checklogin) == 1)
    {
        $row = mysql_fetch_array($checklogin);
        $username = $row['Username'];
        $password=generatePassword();
        
        $message = "User name: ".$username."\rPassword: ".$password;
        mail($email, 'Siphonophore password', $message);

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
    ?>
        <h1>Error</h1>
        <p>No account found with that e-mail
        <meta http-equiv='refresh' content='2;remind.php' />
    <?php
    }
}
else
{
?>

    <div>
    <form method="post" action="remind.php" name="remindform" id="remindform">
    <h1>Forgotten password</h1>
    <table style="width:100%;">
    <tr><td><label for="email">E-mail</label></td><td><input type="email" name="email" id="email" required  style="width:100%"/></td></tr>
    </table>
    <p><input type="submit" name="remind" id="remind" value="Send me a new password" />
    </form>
    </div>

<?php
}
?>
</body>
</html>

