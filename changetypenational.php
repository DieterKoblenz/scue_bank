<?php
require_once 'require/util.php'; //utility functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
require_once 'require/htmllib.php'; //html functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Change User Type",0);


//the main event.
if ($_SESSION['authorized'] && (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National'))) //check for login and make sure they are the admin or a national account
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (isset($_POST['submitted'])) //was a form submitted
 {
	//make the change and confirm it
	if (!isset($_POST['username'])) //make sure the selected a user. 
	{
	 print ("You must select a user to change the type of. <br />");
	 print_form($server);
	}
	else if ($_POST['username'] == DB_ADMIN_NAME) //No screwing with the primary admin account.
	{
	 print ("You may not change the type on account '".DB_ADMIN_NAME."' because it is the main administrative account.<br />");
	}
	else
	{
	 set_type($_POST['username'], $_POST['type'], $server);
	 if ($_POST['username'] == $_SESSION['username'])
	 	$_SESSION['type'] = get_type($_SESSION['username'], $server); //update session in case they changed their own.
	 print ("Type for ".$_POST['username']." set to ".$_POST['type']." successfully.<br />");		
	}
 }
 else //no form was submitted.
 {
 	print_form($server);
 } 
}
else
{
 print ('You must <a href="login.php">login</a> as an admin, in order to use this page.<br />');
}

print_htmlfoot();


function print_form($server)
//prints the second part of the form
{
 //Generate orders. 
 if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))
 {
  $orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT); 
	$list = userlist_given($orders, $server);
 }
 else if ($_SESSION['status'] == 'National')
 //Generate list of users for just their nation
 {
 	$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE country='%s'",mysql_real_escape_string($_SESSION['country']));    
 	$list = userlist_given($orders, $server);
 }
 print ('<form action="changetypenational.php" method="post">');
 print ('Select Account Name<br />');
 user_select_given('username', $list, $server);
 print ('<br /><br />');
 print ('<br />');
 print ('Select New Type<br />');
 type_select($server);
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Change Type" />');
 print ('</form>');
}
?>