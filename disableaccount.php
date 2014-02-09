<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Disable Account",0);


//the main event.
if ($_SESSION['authorized']) //check for login
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (isset($_POST['submitted'])) //was a form submitted
 {
  if (($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National')) 
	{
	 print ('Admin and National accounts cannot disable themselves.<br />');
	}
	else
	{
	 //make the change and confirm it
	 set_status($_SESSION['username'], 'Inactive', $server);
	 $_SESSION['status'] = get_status($_SESSION['username'], $server); //update session
	 print ("Status for ".$_SESSION['username']." set to Inactive successfully.<br />");
	 $_SESSION['authorized'] = false; //log them out.
	}		
 }
 else //no form was submitted.
 {
 	print_form($server);
 } 
}
else
{
 print ('You must <a href="login.php">login</a>, in order to use this page');
}

print_htmlfoot();

function print_form($server)
//prints the second part of the form
{
 print ('<form action="disableaccount.php" method="post">');
 print ('<br />');
 print ('This will disable your account<br />');
 print ('Once you have disabled your account you cannot use it, nor reactivate it yourself.<br />');
 print ('If you later wish you account reactivated you must speak with an administrator.<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Disable Account" />');
 print ('</form>');
}
?>