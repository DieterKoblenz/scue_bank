<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

print_htmlhead('Change Password',0);

if ($_SESSION['authorized']) //yes yes, they are logged in.
{
 if (!isset($_POST['submitted'])) //was a form submitted
 {
 	print_form();
 }
 else if (isset($_POST['submitted'])) //a form was submitted
 {
 //Connect to Server
 $server = bank_server_connect();
 $oldpass = clean_input($_POST['oldpass']); //clean up input
 $newpass = clean_input($_POST['newpass']); //clean up input. 
 $newpass2 = clean_input($_POST['newpass2']); //more cleaning. 
	
	//Spin that shit!
	if (check_password($_SESSION['username'], $oldpass, $server)) //do they know the old pass?
	{
	 if ($newpass == $newpass2) //Do the passwords match?
	 {
	 	set_password($_SESSION['username'], $newpass, $server);
		print ("New password has been set.<br />");
	 }
	 else //They do not match.
	 {
	 	print ("The new password did not match, try again.<br />");
		print_form();
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

function print_form()
//prints the form for password changes
{
 print ('<br />');
 print ('<form action="changepw.php" method="post">');
 print ('<br />Old Password ');
 print ('<input type="password" name="oldpass" value="" />');
 print ('<br />New Password ');
 print ('<input type="password" name="newpass" value="" />');
 print ('<br />Confirm Password ');
 print ('<input type="password" name="newpass2" value="" />');
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Change Password" />');
 print ('</form>');
 print ('<br />');
}
?>