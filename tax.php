<?php
require_once 'require/bankops.php'; //functions for bank operations.
require_once 'require/loglib.php'; //logging functions
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Taxes",0);

//Connect to Server
$server = bank_server_connect();
	
//the main event.
if ($_SESSION['authorized'] AND (($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional'))) //check for login and make sure they are Regional or National
{
 //Generate list. 
 if ($_SESSION['status'] == 'National')
 //Generate list of users for just their nation, all User accounts that are not governmental OR that are regional accounts
 {
 	$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE (country='%s' AND ((status='User' AND type!='Government') OR (status='Regional')) )",mysql_real_escape_string($_SESSION['country']));    
 	$list = userlist_given($orders, $server);
 }
 else if ($_SESSION['status'] == 'Regional')
 //Generate list of users for just their nation and subdivision, that are user accounts but not governmental
 {
 	$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE ((country='%s' AND subdivision='%s') AND (status='User' AND type!='Government'))",mysql_real_escape_string($_SESSION['country']),mysql_real_escape_string($_SESSION['subdivision']));    
 	$list = userlist_given($orders, $server);
 }
 
 //move on with life.
 if (!isset($_POST['submitted'])) //was the form submitted?
 {
 	print_form($list, $server);
 }
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	if ($_POST['confirmation'] == "true") //check for confirmation
 	{
	 	//Tax the peasentry! MUAHAHAH!
		if (apply_tax($_SESSION['username'], &$list, clean_input($_POST['rate']), $_POST['reason'], $server))
		{
		 bank_error ('There was a problem while attempting to apply taxes!');
		 bank_error ('Review transaction log to see which accounts where taxed successfully.');
		}
		else
		{
		 print ('Taxation Completed with Success!<br />');
		}
 	}
 	else //No confirmation
 	{
	 print('You must confirm the taxes if you desire to have them proceed.<br />');
	 print_form($list, $server);
	}
 }
}
else //they are not logged in.
{
 print ('It is required that you <a href="login.php">login</a> as a National or Regional account in order to use this page.<br />');
}

print_htmlfoot();

//function
function print_form($list, $server)
{
 print ('<form action="tax.php" method="post">');
 print ('Tax rate to apply: ');
 print ('<input type="text" size="4" value="00.00" name="rate" />%<br />');
 print ('<tiny>(Please note: Amount will be rounded down to 2nd decimal place.)</tiny><br />');
 print ('The following accounts will be taxed:<br />');
 user_select_given('userlist', $list, $server);
 print ('<br />The Tax will be deposited into your account.<br />');
 print ('<input type="hidden" value="Taxes" name="reason">');
 print ('<br>Please Confirm the Taxation:<br />');
 print ('<input type="radio" value="true" name="confirmation" />I Confirm this Tax.<br />');
 print ('<input type="radio" value="false" name="confirmation" checked="1" />I do NOT Confirm this Taxr.<br />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Apply Tax" />');
 print ('</form>');
}
?>