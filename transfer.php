<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/loglib.php'; //logging functions
require_once 'require/bankops.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Transfer Funds",0);

//Connect to Server
$server = bank_server_connect();
 	
//the main event.
if ($_SESSION['authorized']) //check for login.
{
 if (!isset($_POST['submitted'])) //was the form submitted?
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	if ($_POST['confirmation'] == "true") //check for confirmation
 	{
	 $funds = clean_funds($_POST['funds']); //get funds from post and clean them.
	 //move the money and see if it worked.
	 begin_transaction($server);
	 if(transfer_funds($_SESSION['username'], $_SESSION['username'], clean_input($_POST['userselect']), $funds, clean_input($_POST['reason']), $server)) //make the transfer
	 {
		abort_transaction();
		print_form($server); //failer!
	 }
	 commit_transaction($server);
	}
	else //No confirmation
 	{
	 print('You must confirm the transfer if you desire to have it proceed.<br />');
	 print_form($server);
	}//no confirm
 }//submitted
}//login
else //they are not logged in.
{
 print ('It is required that you <a href="login.php">login</a> in order to use this page.<br />');
}
 
print_htmlfoot();

//function
function print_form($server)
{
 print ("Current Funds: ".get_funds($_SESSION['username'], $server)."<br />");
 print ('<form action="transfer.php" method="post">');
 print ('Receiver\'s Account:<br />');
 user_select('userselect',$server); //create a menu with all user names, called 'userselect'
 print ('<br /><br />');
 print ('Funds to be transfered:<br />');
 print ('<input type="text" size="17" value="0.00" name="funds">(Please note: Amount will be rounded down to 2nd decimal place.)<br />');
 print ('<br />Reason for transfer.');
 print ('<br /><input type="text" value="" name="reason"><br />');
 print ('<br />Please Confirm the Transfer:<br />');
 print ('<input type="radio" value="true" name="confirmation">I Confirm this Transfer.<br />');
 print ('<input type="radio" value="false" name="confirmation" checked="1">I do NOT Confirm this Transfer.<br />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Transfer" />');
 print ('</form>');
}
?>