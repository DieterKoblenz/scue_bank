<?php 
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//Page generation from here down.
print_htmlhead("Reset Password",0);

if ($_SESSION['authorized'])
{
 print("You are currently logged in as ".$_SESSION['username'].".<br /> This page is for if you cannot log-in<br />"); 
}
else
{
 //Connect to Server
 $server = bank_server_connect();

 if (isset($_POST['submitted']))
 {
  if (!find_account($_POST['username'], $server))
	{
	 print ("That account does not exist.<br />");
	 print_form();
	}
	else if (get_email($_POST['username'], $server) == '')
	{
	 print ("There is no email registered to that account.<br />Cannot reset password!<br />");
	}
	else
	{
	//change password and send email. 
	 $password = generate_password('8','4');
   set_password($_POST['username'],$password, $server);
   $message = "Your new password for ".$_POST['username']." is: ".$password."\n\n";
	 mail(get_email($_POST['username'], $server), "Password Reset", $message);
	 print ("Password reset complete. <a href='login.php'>Log in</a> to continue.<br />");
	}
 }
 else
 {
  print_form();
 }
 
 mysql_close($server); //disconnect
}

print_htmlfoot();

//functions

function print_form()
{
 print ('<form action="resetpw.php" method="post">');
 print ('Account Name ');
 print ('<input type="text" name="username" value="username" />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Reset Password" />');
 print ('</form>'); 
 print ('<i><br />*Username is case sensitive and may only include letters, numbers and spaces.<br />');
}