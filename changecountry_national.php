<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Change User Country",0);


//the main event.
if ($_SESSION['authorized'] && $_SESSION['status'] == 'National') //yes yes, they are logged in and are a national account
{
  //Connect to Server
 $server = bank_server_connect();
	
 if (isset($_POST['submitted'])) //was the form submitted
 {
  if ($_POST['submitted'] == "true") //part one is complete
	{
	 if (!isset($_POST['username'])) //make sure the selected a user. 
	 {
	  print ("You must select a user to change the country of. <br />");
		print_form($server);
	 }
	 else
	 {
	  print_form2($server);
	 }
	}
	else if ($_POST['submitted2'] == "true") //part 2 is complete. 
	{ 
   //make the change and confirm it
	 set_country($_POST['username'], $_POST['country'], $server);
	 if ($_POST['username'] == $_SESSION['username'])
		$_SESSION['country'] = get_country($_SESSION['username'], $server); //update the session for the change
	 print ("Country for ".$_POST['username']." set to ".$_POST['country']." successfully.<br />");
	 
	 set_subdivision($_POST['username'], $_POST['subdivision'], $server);
	 if ($_POST['username'] == $_SESSION['username'])
	  $_SESSION['subdivision'] = get_subdivision($_SESSION['username'], $server); //update session
	 print ("Subdivision for ".$_POST['username']." set to ".$_POST['subdivision']." successfully.<br />");		
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
 // Make it only accounts from this country
 $orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE country='%s'",mysql_real_escape_string($_SESSION['country']));    
 $list = userlist_given($orders, $server);
 print ('<form action="changecountry_national.php" method="post">');
 print ('Select Account Name<br />');
 user_select_given('username', $list, $server);
 print ('<br /><br />');
 print ('Select Country<br />');
 country_select($server);
 print ('<br><br>');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="hidden" name="submitted2" value="false" />');
 print ('<input type="submit" value="Change Country" />');
 print ('</form>');
}

function print_form2($server)
//prints the second part of the form
{
 print ('<form action="changecountry_national.php" method="post">');
 print ('<br />');
 print ("Preparing to change country for ".$_POST['username']." to ".$_POST['country'].".<br />");
 print ('Select New Subdivision<br />');
 subdivision_select($_POST['country'],$server);
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />');
 print ("<input type='hidden' name='username' value='".$_POST['username']."' />"); //remember the username
 print ("<input type='hidden' name='country' value='".$_POST['country']."' />"); // remember the country
 print ('<input type="submit" value="Change Subdivision" />');
 print ('</form>');
}
?>