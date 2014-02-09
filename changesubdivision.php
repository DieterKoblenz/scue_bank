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
if ($_SESSION['authorized'] AND (($_SESSION['username'] ==  DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National'))) //check for login and make sure they are the more then a user or inactive or regional.
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (isset($_POST['submitted1'])) //was a form submitted
 {
 	if ($_POST['submitted1'] == "true") //part one is complete
	{
	 print_form2($server);
	}
	else if ($_POST['submitted2'] == "true") //part 2 is complete. 
	{
	 if (!isset($_POST['username'])) //make sure they selected a user. 
	 {
	  print ("You must select a user to change the subdivision of. <br />");
	 }
	 else
	 { 
	  //make the change and confirm it
	  set_subdivision($_POST['username'], $_POST['subdivision'], $server);
	  if ($_POST['username'] == $_SESSION['username'])
	   $_SESSION['subdivision'] = get_subdivision($_SESSION['username'], $server); //update session
	  print ("Subdivision for ".$_POST['username']." set to ".$_POST['subdivision']." successfully.<br />");		
	 }
	}
 }
 else //no form was submitted.
 {
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

 print ('<form action="changesubdivision.php" method="post">');
 print ('Select Account Name<br />');
 user_select_given('username', $list, $server);
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted1" value="true" />');
 print ('<input type="hidden" name="submitted2" value="false" />');
 print ('<input type="submit" value="Select User" />');
 print ('</form>');
}

function print_form2($server)
//prints the second part of the form
{
 print ('<form action="changesubdivision.php" method="post">');
 print ('<br />');
 print ('Select New Subdivision<br />');
 subdivision_select(get_country($_POST['username'],$server),$server);
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted1" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />');
 print ("<input type='hidden' name='username' value='".$_POST['username']."' />"); //remember the username
 print ('<input type="submit" value="Change Subdivision" />');
 print ('</form>');
}
?>