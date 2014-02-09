<?php
require_once 'require/bankops.php'; //functions for bank operations.
require_once 'require/loglib.php'; //logging functions
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead(LANG_ADMINTRANSFER,0);

  //Connect to Server
 $server = bank_server_connect();
	
//the main event.
if ($_SESSION['authorized'] AND (($_SESSION['username'] ==  DB_ADMIN_NAME) OR (($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] != 'National') OR ($_SESSION['status'] != 'Regional')))) //check for login and make sure they are the more then a user or inactive.
{
 if (!isset($_POST['submitted'])) //was the form submitted?
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	if ($_POST['confirmation'] == "true") //check for confirmation
 	{
	 $funds = $_POST['funds']; //get funds from post
	 //move the money and see if it worked.
	 begin_transaction($server);
	 if(transfer_funds($_SESSION['username'], $_POST['usersend'], $_POST['userreceive'], $funds, $_POST['reason'], $server)) //make the transfer
	  {
		 abort_transaction($server);
		 print_form($server); //failer!
		}
	 commit_transaction($server);
	}//confirmation
	else //No confirmation
 	{
	 print(LANG_ADMINTRANSFER_1.'<br />');
	 print_form($server);
	}//no confirm
 }//submitted
}//loged in
else //they are not logged in.
{
 print (LANG_ADMINTRANSFER_2.'<br />');
}

print_htmlfoot();

//function
function print_form($server)
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
 else if ($_SESSION['status'] == 'Regional')
 //Generate list of users for just their nation and subdivision
 {
 	$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE (country='%s' AND subdivision='%s')",mysql_real_escape_string($_SESSION['country']),mysql_real_escape_string($_SESSION['subdivision']));    
 	$list = userlist_given($orders, $server);
 }	

 print ('<form action="admintransfer.php" method="post">');
 print ('Sender\'s Account:<br />');
 user_select_given('usersend', $list, $server); //create a menu with all specified users named called 'usersend'
 print ('<br /><br />');
 print (LANG_ADMINTRANSFER_3.':<br />');
 user_select('userreceive',$server); //create a menu with all user names, called 'userrecieve'
 print ('<br /><br />');
 print (LANG_ADMINTRANSFER_4.':<br />');
 print ('<input type="text" size="17" value="0.00" name="funds">(Please note: Amount will be rounded down to 2nd decimal place.)<br />');
 print ('<br />'.LANG_ADMINTRANSFER_5);
 print ('<br /><input type="text" value="" name="reason"><br />');
 print ('<br>'.LANG_ADMINTRANSFER_6.':<br />');
 print ('<input type="radio" value="true" name="confirmation">'.LANG_ADMINTRANSFER_7.'.<br />');
 print ('<input type="radio" value="false" name="confirmation" checked="1">'.LANG_ADMINTRANSFER_8.'<br />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="'.LANG_ADMINTRANSFER_9.'" />');
 print ('</form>');
}
?>