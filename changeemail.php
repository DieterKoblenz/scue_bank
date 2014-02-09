<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

print_htmlhead('Change Email',0);

if ($_SESSION['authorized']) //yes yes, they are logged in.
{
 //Connect to Server
 $server = bank_server_connect();
 if (!isset($_POST['submitted'])) //was a form submitted
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //a form was submitted
 {
 $password = clean_input($_POST['password']); //clean up input
	
	//Spin that shit!
	if (check_password($_SESSION['username'], $password, $server)) //do they know the password?
	{
	 if (email_valid($_POST['email'])) //email format check
	 {
	 	//a valid email format
		set_email($_SESSION['username'], $_POST['email'], $server); //make a change.
		print ("Your email has been changed.<br />");
		print ("Your current email is: ".get_email($_SESSION['username'], $server)."<br />");
	 }
	 else
	 {
	 	print ("That was not a valid email format!<br />");
	 }
	}
	else //lies! They know it not!
	{
	 print ("That is the incorrect password for this account.<br />");
	 print_form();
	}
 }
}
else
{
 print ("You must <a href='login.php'>login</a> to use this page.<br />");
}

print_htmlfoot();

function print_form($server)
//prints the form for password changes
{
 print ("Your current email is: ".get_email($_SESSION['username'], $server)."<br />");
 print ('<br />');
 print ('<form action="changeemail.php" method="post">');
 print ('<br />Confirm Password ');
 print ('<input type="password" name="password" value="" />');
 print ('<br />New Email ');
 print ('<input type="text" name="email" value="user@place.com" />');
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Change email" />');
 print ('</form>');
 print ('<br />');
}
?>