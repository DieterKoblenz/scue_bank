<?php
require_once 'require/util.php'; //utility functions
require_once 'require/dblib.php'; //database functions
require_once 'require/sublib.php'; //subdivision database functions
require_once 'require/htmllib.php'; //HTML functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();
//Begin HTML generation
print_htmlhead(LANG_ADDSUBDIVISION,0);

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
	 	print (LANG_ADDSUBDIVISION_1."<br />");
	 }
	 else //make it happen..
	 {
	 	$subdivision = clean_input($_POST['subdivision']);
		$country = clean_input($_POST['country']);
		if (!ctype_alnum(preg_replace('/[[:space:]]/', '', $subdivision)))
		{
		 print (LANG_ADDSUBDIVISION_2."<br />");
		}
		else if (!ctype_alnum(preg_replace('/[[:space:]]/', '', $country)))
		{
		 print (LANG_ADDSUBDIVISION_3."<br />");
		}
	 	else if (!add_subdivision($subdivision, $country, $server)) //add
	 	 print (LANG_ADDSUBDIVISION_4a.$subdivision.", ".$country.LANG_ADDSUBDIVISION_4b."<br />");
		else
		{
		 bank_error(LANG_ADDSUBDIVISION_5."<br />");
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
 print (LANG_MUSTBEADMIN.'<br />');
 print_form($server);
}

print_htmlfoot();

function print_form($server)
//print the form.
{
 print ('<form action="addsubdivision.php" method="post">');
 print ('Select Country<br />');
 country_select($server);
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="hidden" name="submitted2" value="false" />');
 print ("<input type='submit' value='".LANG_ADDSUBDIVISION_NEXT."' />");
 print ('</form><br />');
}

function print_form2($server)
//print the form.
{
 print ('<form action="addsubdivision.php" method="post">');
 print (LANG_ADDSUBDIVISION_6.'<br />');
 print ('<input type="text" name="subdivision" value="" />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />'); 
 print ("<input type='hidden' name='country' value='".$_POST['country']."' />"); //pass on the country
 print ("<input type='submit' value='".LANG_ADDSUBDIVISION."' />");
 print ('</form><br />');
}
?>
