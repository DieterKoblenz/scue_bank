<?php
require_once 'require/util.php';
require_once 'require/dblib.php';
require_once 'require/htmllib.php'; //HTML functions
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();
//Begin HTML generation
print_htmlhead(LANG_ADMINCHANGEPW,0);

if ($_SESSION['authorized'] AND (($_SESSION['username'] ==  DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional'))) //check for login and make sure they are the more then a user or inactive.
{
  //Connect to Server
 $server = bank_server_connect();
	
 if (!isset($_POST['submitted'])) //was a form submitted
 {
 	print_form($server);
 }
 else if (isset($_POST['submitted'])) //a form was submitted
 {
  if (!isset($_POST['username'])) //make sure they selected a user. 
	{
	 print (LANG_ADMINCHANGEPW_1."<br />");
	 print_form($server);
	}
	else
	{	
   $newpass = clean_input($_POST['newpass']);
 	 $newpass2 = clean_input($_POST['newpass2']);	
	 //Spin that shit!
	 if ($newpass == $newpass2) //Do the passwords match?
	 {
	  set_password($_POST['username'],$newpass,$server);
	  print (LANG_ADMINCHANGEPW_2."<br />");
	 }
	 else //They do not match.
	 {
	  print (LANG_ADMINCHANGEPW_3."<br />");
	  print_form($server);
	 }
	}
 }
}
else
{
 print (LANG_MUSTBEADMIN."<br />");
}

print_htmlfoot();

function print_form($server)
//prints the form for password changes
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

 print ('<form action="adminchangepw.php" method="post">');
 print (LANG_ADMINCHANGEPW_4.'<br />');
 user_select_given('username', $list, $server);
 print ('<br /><br />');
 print (LANG_ADMINCHANGEPW_5.' ');
 print ('<input type="password" name="newpass" value="" />');
 print ('<br>'.LANG_ADMINCHANGEPW_6.' ');
 print ('<input type="password" name="newpass2" value="" />');
 print ('<br><br>');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ("<input type='submit' value='".LANG_ADMINCHANGEPW_7."' />");
 print ('</form><br />');
}
?>