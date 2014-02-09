<?php 
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//Page generation from here down.
print_htmlhead("Create Account",0);

if ($_SESSION['authorized'])
{
 print("You are currently logged in as ".$_SESSION['username'].".<br /> Please logout if you plan to create another account<br />"); 
}
else
{
 //Connect to Server
 $server = bank_server_connect();
	
 if (!isset($_POST['submitted']))
 {
  print_form($server);
 }
 else if (isset($_POST['submitted']))
 {
 	if ($_POST['submitted'] == "true")
   		 {
   		 	print_form2($server);//next step
   		 }
	else if ($_POST['submitted2'] == "true")
			 { 	
			 	 if (!ctype_alnum(preg_replace('/[[:space:]]/', '', $_POST['username']))) //check for alphanumericy but allow space. Stole code from: http://www.kliky.net/howto/use-php-ctype_alnum-to-check-alpha-numeric-characters/ 
				 {
				 	print ('Username may only contain letters, numbers and spaces. No funny characters.<br />');
					print_form($server);
				 }
 			 	 else if ($_POST['userpass'] != $_POST['userpass2']) //check that passwords match
	 		 	 {
	 		 	 	print ('Passwords don\'t match. Try agian.<br />');
	 				print_form($server);
	 		 	 }
 			 	 else if (find_account($_POST['username'], $server))
 	 		 	 {
 	 		 	 	print ('Account name already in use. Try again.<br />');
	 				print_form($server);
 	 		 	 }
			 	 else
	 		 	 {
 	  	 	 	if (add_account(clean_input($_POST['username']), clean_input($_POST['userpass']), '0', $_POST['subdivision'], $_POST['country'], $_POST['type'], $_POST['owner'], '', $server))
 	  			{
 	  			 print_form($server);
 	  			}
 	 				else
	  			{
	  			 print ('Account created succefully.<br />You may now <a href="login.php">login</a>.');
	  			}
   		 	 }
		 }
	mysql_close($server); //disconnect
 }
}

print_htmlfoot();

//functions

function print_form($server)
{
 print ('<form action="createaccount.php" method="post">');
 print ('Account Name ');
 print ('<input type="text" name="username" value="username" />');
 print ('<br />Password ');
 print ('<input type="password" name="userpass" value="" />');
 print ('<br />Confirm Password ');
 print ('<input type="password" name="userpass2" value="" />');
 print ('<br />');
 print ('Please select the country for this acount:<br />');
 country_select($server); //print the menu for country names
 print ('<br />');
 print ('Account Type:<br />');
 print ('<input type="radio" name="type" value="Private" checked="1"> Personal Account<br />');
 print ('<input type="radio" name="type" value="Company"> Company Account<br />');
 print ('<input type="radio" name="type" value="Government"> Government Account<br />');
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="hidden" name="submitted2" value=false" />');
 print ('<input type="submit" value="Create Account" />');
 print ('</form>'); 
 print ('<i><br />*Username and password are case sensitive and may only include letters, numbers and spaces.<br />');
 print ('Spaces at the start or end will be removed from both.<br /></i>');
}

function print_form2($server)
{
 print ('<form action="createaccount.php" method="post">');
 print ("<input type='hidden' name='country' value='".$_POST['country']."' />");//remember country
 print ("<input type='hidden' name='username' value='".$_POST['username']."' />"); //remember username
 print ("<input type='hidden' name='userpass' value='".$_POST['userpass']."' />");
 print ("<input type='hidden' name='userpass2' value='".$_POST['userpass2']."' />");
 print ('<br />');
 print ('Select a subdivision.<br />');
 subdivision_select($_POST['country'],$server); //print the menu of subdivision names
 print ('<br />');
 if ($_POST['type'] == 'Company')
 {
 	print ('Select the owner of this company:<br />');
 	user_select("owner", $server);
	print ('<input type="hidden" name="type" value="Company" />');
 }
 else if ($_POST['type'] == 'Private')
 {
 	print ('<input type="hidden" name="owner" value="" />');
 	print ('<input type="hidden" name="type" value="Private" />');
 }
  else if ($_POST['type'] == 'Government')
 {
 	print ('<input type="hidden" name="owner" value="" />');
 	print ('<input type="hidden" name="type" value="Government" />');
 }
 
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="false" />');
 print ('<input type="hidden" name="submitted2" value="true" />');
 print ('<input type="submit" value="Create Account" />');
 print ('</form>'); 
}
?>