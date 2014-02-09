<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/sublib.php'; //subdivision database functions
require_once 'require/loglib.php'; //logging functions
require_once 'require/bankops.php'; //logging functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();
//Begin HTML generation
print_htmlhead('Delete Country',0);

if ($_SESSION['authorized'] && (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //yes yes, they are logged in and are the admin
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (!isset($_POST['submitted'])) //was a form submitted
	{
 	print_form($server);
	}
 else if (isset($_POST['submitted'])) //a form was submitted
	{
 	if ($_POST['confirmation'] == "true") //did they confirm it?
	{
	if ($_POST['country']=="SCUE")
		{print('You may not delete the SCUE as a country.<br />');}
	else
	 {
	 $list = subdivision_list_given($_POST['country'], $server);
	 foreach ($list as $subdivision)
		{
	
		$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE subdivision='%s' AND country='%s'",
		  mysql_real_escape_string($subdivision),
		  mysql_real_escape_string(clean_input($_POST['country'])));
		$list = userlist_given($orders, $server);
		
		foreach ($list as $user)
			{
			 if ($user == DB_ADMIN_NAME) //are they deleting themself?
			 {
				print ('You may not delete the admin account!<br />');
			 }
			 else if ($user == $_SESSION['username'])
			 {
				print ('You may not delete yourself!<br />');
			 }
			 else
			 {
				$funds = get_funds($user, $server);
				if ($funds > 0) //if they had money to remove
				  {
				  if(transfer_funds($_SESSION['username'], $user, DB_ADMIN_NAME, $funds, 'Account Deletion' , $server)) //make the transfer
				    {
					 abort_transaction($server);
					 print_form($server); //failer!
					}
				  commit_transaction($server);
				  }
				 set_status($user, 'Inactive', $server);
				 set_country($user, 'SCUE', $server);
				 set_subdivision($user, 'Deleted', $server);
			 }
			}
		// Delete users, then subdivision, so if there are any errors, it isn't deleted.
		delete_subdivision($subdivision, $_POST['country'], $server); //KILL! ANNIHILATE! DESTROY! or just delete... whatever.
		print ("Subdivision ".$subdivision.", ".$_POST['country']." has been deleted.<br />");
		}
	 }
	}
	else //they did not confirm. 
	{
	 print ('You must confirm the deletion if you desire it to happen.<br />');
	 print_form($server);
	}
	}
}
else //not logged in
{
 print ('You must <a href="login.php">login</a> as an admin, in order to use this page<br />');
 print_form($server);
}

print_htmlfoot();

function print_form($server)
//print the form.
{
 print ('<form action="deletecountry.php" method="post">');
 print ('Select Country<br />');
 country_select($server);
 print ('<br />');
 print ('Deleting a country sets all accounts within the country to inactive, returns all their funds to the admin account,
 and moves all accounts to the \'Deleted\' subdivision, and then removes all subdivisions and the country itself.  Once done,
 it cannot be reversed.  Are you sure you wish to do this?<br />');
 print ('<input type="radio" value="true" name="confirmation">I Confirm Deletion.<br />');
 print ('<input type="radio" value="false" name="confirmation" checked="1">I do NOT Confirm Deletion.<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="hidden" name="submitted2" value="false" />');
 print ('<input type="submit" value="Next" />');
 print ('</form><br />');
}
?>
