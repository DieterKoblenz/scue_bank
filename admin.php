<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

//THIS PAGE IS DEPRECIATED BY THE CSS MENU!

begin_session();
print_htmlhead(BANK_NAME." Admin Page",0);

if ($_SESSION['authorized'])
{
 if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional')) //are they an admin or national, or regional
 //Universal admin stuff. 
 {
 	if ($_SESSION['status'] == 'National')	//National options
 	{
 	 print ('<h5>National Control Panel</h5>');
	 print ("<a href='changesubdivision.php'>Change User Subdivision</a><br />");
	}
	
	if ($_SESSION['status'] == 'Regional')	//Regional options
 	{
 	 print ('<h5>Regional Control Panel</h5>');
	}
	
	if (($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional'))
	{
	 print ("<a href='tax.php'>Apply Tax</a><br />");
	}
	
 	if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))
 	{
	 print ('<h5>Admin Control Panel</h5>'); //Admin options
	 print ("<a href='repair.php'>Bank Repair Utility</a><br />");
	 print ("<a href='createfunds.php'>Create Funds </a><br />");
	 print ("<a href='deleteaccount.php'>Delete User Account</a><br />");
	 print ("<a href='changestatus.php'>Change User Status</a><br />");
	 print ("<a href='changetype.php'>Change User Type</a><br />");
	 print ("<a href='changecountry.php'>Change User Country and Subdivision</a><br />");
	 print ("<a href='addcountry.php'>Add Country</a><br />");
	 print ("<a href='addsubdivision.php'>Add Subdivision</a><br />");
	 print ("<a href='deletesubdivision.php'>Delete Subdivision</a><br />");
	 print ("<a href='viewcountries.php'>View Countries</a><br />");
	 print ("<a href='viewsubdivisions.php'>View Subdivisions</a><br />");
	 print ("<a href='admintranlog.php'>View System Transaction Log</a><br />");
	 print ('<h5>Shared Control Panel</h5>');
 	}
	print ("<a href='adminchangepw.php'>Change User Password</a><br />");
	print ("<a href='admintransfer.php'>Force Funds Transfer </a><br />");
	print ("<a href='viewaccounts.php'>View User Accounts</a><br />");
 }
 else
 {
 	print ("You have no priviledges on this page. <br />");
 } 
}
else if (!$_SESSION['authorized'])
{
 print ('Please login.<br />');
}

print_htmlfoot();
?>