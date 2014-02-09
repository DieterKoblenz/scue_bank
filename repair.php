<?php
require_once 'require/bankops.php'; //functions for bank operations.
require_once 'require/loglib.php'; //logging functions
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Bank Repair Utility",0);

//Connect to Server
$server = bank_server_connect();
 
//the main event.
if ($_SESSION['authorized'] AND (($_SESSION['username'] ==  DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //check for login and make sure they are an admin
{
 if (!isset($_POST['submitted'])) //was the form submitted?
 {
 	print_form();
 }
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	print ('Beginning repair.<br />');
 	$userlist = userlist($server);	
 	foreach ($userlist as &$user)
 	{
 	 //type (text)
	 if (get_type($user, $server) == '')
	 {
	  set_type($user, 'Private', $server);
		print ("Set ".$user." Type to Private.<br />");
	 }
	 //style (text)
	 if (get_style($user, $server) == '')
	 {
	 	set_style($user, HTML_STYLE, $server);
	 	print ("Set ".$user." Style to ".HTML_STYLE.".<br />");
	 }
	 //status (text)
	 if (get_status($user, $server) == '')
	 {
	 	set_status($user, 'User', $server);
	 	print ("Set ".$user." Status to User.<br />");
	 }
	 //set National and Regional accounts as Government accounts.
	 $status = get_status($user, $server);
	 if ( (($status == 'National') OR ($status == 'Regional')) AND (get_type($user, $server) != 'Government') )
	 {
	 	set_type($user, 'Government', $server);
		print ("Set ".$user." Type to Government because there Status is National or Regional.<br />");
	 }	
	 //subdivisions checks (these get messed up sometimes)
	 if (get_subdivision($user, $server) == 'Elywnn')
	 {
	 	set_subdivision($user, 'Elwynn', $server);
		print ("Set ".$user." subdivision to Elwynn from Elywnn.<br />");
	 }
	}
	print ('Repair completed!<br />');
 }
}
else //they are not logged in.
{
 print ('It is required that you <a href="login.php">login</a> as an admin, in order to use this page.<br />');
}
 
print_htmlfoot();


//
function print_form()
{
 print ('<form action="repair.php" method="post">');
 print ('You are about to run the bank repair utility.<br />');
 print ('Doing this will try to fix various potential problems with the bank tables.<br />');
 print ('Doing this will set blank fields to their default values.<br />');
 print ('Doing this will set accounts with Status Admin, National and Regional to Type Government.<br  />');
 print ('This utility may not cover all concievable table problems.<br />');
 print ('Depending on the size of your tables this might take some time. Don\'t hit Reload.<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Continue" />');
 print ('</form>');
}

?>