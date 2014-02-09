<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Create Funds",0);

//Connect to Server
$server = bank_server_connect();
	
//the main event.
if ($_SESSION['authorized'] && (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //check for login and make sure they are the admin
{
 if (!isset($_POST['submitted'])) //was the form submitted?
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	$funds = $_POST['funds']; //get funds from post
	if (($funds <= 0)||(!is_numeric($funds))) //make sure it's not a neg or 0 num, or has letters.
	{
	 print ('Funds to be created must be a positive, non-negative number.<br />');
	 print_form($server);
	}
	else //it's not negative or 0, and it's a number.
	{
	 add_funds(DB_ADMIN_NAME,$funds,$server); //add funds to the admin account
	 print ($funds." created successfully.<br />");
	}
 }
}
else
{
 print('You must <a href="login.php">login</a> as an admin, to use this page.<br />');
}

print_htmlfoot();

//functions
function print_form($server)
//prints the form
{
 print ("Current Funds: ".get_funds(DB_ADMIN_NAME,$server)."<br />");
 print ('<form action="createfunds.php" method="post">');
 print ('Funds to be created:<br />');
 print ('<input type="text" size="17" value="0.00" name="funds">(Please note: Amount will be rounded down to 2nd decimal place.)<br />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Create" />');
 print ('</form>');
}

?>