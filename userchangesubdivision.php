<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Change User Subdivision",0);


//the main event.
if ($_SESSION['authorized']) //check for login
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (isset($_POST['submitted'])) //was a form submitted
 {
	//make the change and confirm it
	set_subdivision($_SESSION['username'], $_POST['subdivision'], $server);
	$_SESSION['subdivision'] = get_subdivision($_SESSION['username'], $server); //update session
	print ("Subdivision for ".$_SESSION['username']." set to ".$_POST['subdivision']." successfully.<br />");		
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
 print ('<form action="userchangesubdivision.php" method="post">');
 print ('<br />');
 print ('Select New Subdivision<br />');
 subdivision_select(get_country($_SESSION['username'],$server),$server);
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Change Subdivision" />');
 print ('</form>');
}
?>