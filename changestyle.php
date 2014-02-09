<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//the main event.
if ($_SESSION['authorized']) //check for login
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (isset($_POST['submitted1'])) //was a form submitted
 {
	//make the change and confirm it
	set_style($_SESSION['username'], $_POST['style'], $server);
	$_SESSION['style'] = get_style($_SESSION['username'], $server); //update session
	//begin page generation
  print_htmlhead("Change User CSS Style",0);
	
	print ("Style for ".$_SESSION['username']." set to ".$_POST['style']." successfully.<br />");	
 }
 else //no form was submitted.
 {
  //begin page generation
  print_htmlhead("Change User CSS Style",0);

 	print_form($server);
 } 
}
else
{
 print ('You must <a href="login.php">login</a> as an admin, in order to use this page');
}

print_htmlfoot();

function print_form($server)
//prints the form.
{
 print ('<form action="changestyle.php" method="post">');
 print ('Select Style<br />');
 style_select();
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted1" value="true" />');
 print ('<input type="submit" value="Change Style" />');
 print ('</form>');
}