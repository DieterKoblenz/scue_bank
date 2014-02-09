<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/loglib.php'; //logging functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();
//Begin HTML generation
print_htmlhead('Delete User',0);

if ($_SESSION['authorized'] && (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //yes yes, they are logged in and are the admin
{
 //Connect to Server
 $server = bank_server_connect();

 print ('Deleting accounts can effect how some functionality of the bank works.<br />');
 print ('Please consider setting the account\'s Status to Inactive instead.<br />');
	
 if (!isset($_POST['submitted'])) //was a form submitted
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //a form was submitted
 {
 	if ($_POST['confirmation'] == "true") //did they confirm it?
	{
	 if (!isset($_POST['userdel']))
	 {
		print ('There was an error, and no username was submitted. This may be caused if you hit the back button. Please try again.<br />');
	 }
	 else if ($_POST['userdel'] == DB_ADMIN_NAME) //are they deleting themself?
	 {
		print ('You may not delete the admin account!<br />');
	 }
	 else if ($_POST['userdel'] == $_SESSION['username'])
	 {
		print ('You may not delete yourself!<br />');
	 }
	 else //this self deletion we cannot allow...
	 {
		$funds = get_funds($_POST['userdel'], $server);
	 	if ($funds > 0) //if they had money to remove
		{
		 add_funds(DB_ADMIN_NAME, $funds, $server); //move their money to the admin
		 log_transfer($_SESSION['username'], $_POST['userdel'], DB_ADMIN_NAME, $funds, 'Account Deletion', $server); //log the move
		}
		delete_account($_POST['userdel'], $server); //KILL! ANNIHILATE! DESTROY! or just delete... whatever.
		print ("Account ".$_POST['userdel']." has been deleted. ".$funds." funds have been recovered.<br />");
	 }
	}
	else //they did not confirm. 
	{
	 print ('You must confirm the deletion if you desire it to happen.<br />');
	 print_form($server);
	}
 }
}
else //not loged in
{
 print ('You must <a href="login.php">login</a> as an admin, in order to use this page<br />');
 print_form($server);
}

print_htmlfoot();

function print_form($server)
//print the form.
{
 print ('<form action="deleteaccount.php" method="post">');
 print ('Select User Account<br />');
 user_select('userdel', $server);
 print ('<br />');
 print ('<br>Please Confirm the Delection<br />');
 print ('<input type="radio" value="true" name="confirmation">I Confirm Deletion.<br />');
 print ('<input type="radio" value="false" name="confirmation" checked="1">I do NOT Confirm Deletion.<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Delete Account" />');
 print ('</form><br />');
}
?>
