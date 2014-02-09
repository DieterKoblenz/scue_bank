<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/sublib.php'; //subdivision database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead(LANG_ADDCOUNTRY,0);

//Connect to Server
$server = bank_server_connect();
	
//the main event.
if ($_SESSION['authorized'] AND (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //check for login and make sure they are the admin
{
 if (!isset($_POST['submitted'])) //was the form submitted?
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	$subdivision = clean_input($_POST['subdivision']);
	$country = clean_input($_POST['country']);
	
	if (!ctype_alnum(preg_replace('/[[:space:]]/', '', $subdivision)))
	{
	 print (LANG_ADDCOUNTRY_1."<br />");
	}
	else if (!ctype_alnum(preg_replace('/[[:space:]]/', '', $country)))
	{
	 print (LANG_ADDCOUNTRY_2."<br />");
	}
 	else if (!add_subdivision($subdivision, $country, $server))
	{
	 print ($subdivision.", ".$country.LANG_ADDCOUNTRY_3.".<br />");
	}
	else
	{
	 bank_error(LANG_ADDCOUNTRY_4."<br />");
	}	
 }
}
else
{
 print(LANG_MUSTBEADMIN."<br />");
}

print_htmlfoot();

//functions
function print_form($server)
//prints the form
{
 print ('<form action="addcountry.php" method="post">');
 print (LANG_ADDCOUNTRY_5.":<br />");
 print ('<input type="text" size="20" value="" name="country"><br />');
 print ('<br />');
 print (LANG_ADDCOUNTRY_6.":<br />");
 print ('<input type="text" size="20" value="Proper" name="subdivision"><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ("<input type='submit' value='".LANG_ADDCOUNTRY_ADD."' />");
 print ('</form>');
}

?>