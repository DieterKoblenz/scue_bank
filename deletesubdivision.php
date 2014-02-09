<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/sublib.php'; //subdivision database functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();
//Begin HTML generation
print_htmlhead('Delete Subdivision',0);

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
 	if ($_POST['submitted2'] == 'true') //is this the 2nd form?
 	{
 	 if (!isset($_POST['subdivision'])) //back button issues
	 {
	 	print ('There was an error, and no subdivision was submitted. This may be caused if you hit the back button. Please try again.<br />');
	 }
	 else if (isset($_POST['submitted3']))
	 {
	 	print_form4($server);
	 }
	 else if (isset($_POST['checked']))
	 {
	 	print_form3($server);
	 }
	 else if ($_POST['subdivision'] == $_POST['alternate'])
	 {
	 	print ("The deleted subdivision and the alternate cannot be the same!<br />");
		print_form2($server);
	 }
	 else //make it happen..
	 {
	 	delete_subdivision($_POST['subdivision'], $_POST['country'], $server); //KILL! ANNIHILATE! DESTROY! or just delete... whatever.
	 	print ("Subdivision ".$_POST['subdivision'].", ".$_POST['country']." has been deleted.<br />");
		
		$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE subdivision='%s' AND country='%s'",
		  mysql_real_escape_string(clean_input($_POST['subdivision'])),
		  mysql_real_escape_string(clean_input($_POST['country'])));
		$list = userlist_given($orders, $server);
		
		foreach ($list as $user)
		{
		if (isset($_POST['newcountry']))
		{
		 set_country($user, $_POST['newcountry'], $server);
		}
		 set_subdivision($user, $_POST['alternate'], $server);
		}
	 }
	}
	else //second form needs to be submitted
	{
	 print_form2($server);
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
 print ('<form action="deletesubdivision.php" method="post">');
 print ('Select Country<br />');
 country_select($server);
 print ('<br />');
 print ('<tiny>Deleting the last subdivision for a country will remove the country.</tiny><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="hidden" name="submitted2" value="false" />');
 print ('<input type="submit" value="Next" />');
 print ('</form><br />');
}

function print_form2($server)
//print the form.
{
 print ('<form action="deletesubdivision.php" method="post">');
 print ('Select Subdivision to Delete<br />');
 subdivision_select($_POST['country'], $server);
 print ('<br />');
 print ('Select Subdivision to move accounts into<br />');
 subdivision_named_select("alternate", $_POST['country'], $server);
 print ('<br />');
 print ("Check this box if you are moving the accounts in the subdivision into another country: ");
 print ('<input type="checkbox" name="checked" value="true" /><br />');
 print ("<br /><tiny>This is used also if it's the last subdivision in the country.<br /></tiny>");
 print ('<input type="hidden" name="submitted" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />'); 
 print ("<input type='hidden' name='country' value='".$_POST['country']."' />"); //pass on the country
 print ('<input type="submit" value="Delete Subdivision" />');
 print ('</form><br />');
}

function print_form3($server)
//print the form.
{
 $orders = sprintf("SELECT DISTINCT country FROM ".DB_TABLE_SUBDIVISION." WHERE country <> '%s'", mysql_real_escape_string(clean_input($_POST['country']))); 
 print ('<form action="deletesubdivision.php" method="post">');
 print ('Select the new country<br />');
 country_select_given($orders, $server);
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />'); 
 print ('<input type="hidden" name="submitted3" value="true" />'); 
 print ("<input type='hidden' name='oldcountry' value='".$_POST['country']."' />");
 print ("<input type='hidden' name='subdivision' value='".$_POST['subdivision']."' />");
 print ('<input type="submit" value="Set Country" />');
 print ('</form><br />');
}

function print_form4($server)
//print the form.
{
 print ('<form action="deletesubdivision.php" method="post">');
 print ('Select Subdivision to move accounts into<br />');
 subdivision_named_select("alternate", $_POST['country'], $server);
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />'); 
 print ("<input type='hidden' name='subdivision' value='".$_POST['subdivision']."' />");
 print ("<input type='hidden' name='country' value='".$_POST['oldcountry']."' />");
 print ("<input type='hidden' name='newcountry' value='".$_POST['country']."' />");
 print ('<input type="submit" value="Delete Subdivision" />');
 print ('</form><br />');
}
?>
