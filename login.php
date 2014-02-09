<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//Page generation from here down.

if ($_SESSION['authorized'])
{
 print_htmlhead("Account Login",0);	 
 print ('You are already logged in.<br> If you need to change accounts, <a href="logout.php">logout</a> of the current session first.<br />');
}
else
{
 if (!isset($_POST['submitted']))
 {
  print_htmlhead("Account Login",0);
  print_form();
 }
 else if (isset($_POST['submitted']))
 {
 	//Connect to Server
 	$server = bank_server_connect();
	$username = clean_input($_POST['username']); //clean up the input
	$password = clean_input($_POST['userpass']); //clean up. 
	
  if (find_account($username, $server)) //do they have an account
  {
   if (check_password($username, $password, $server)) //is this the correct password
	 {
	 	//Do login precedures..
	 	$_SESSION['username'] = $username; //give us the username information for later.
	 	$_SESSION['subdivision'] = get_subdivision($username,$server); //Where do they live? This should rarely change if ever.
	 	$_SESSION['country'] = get_country($username,$server);//what country are they in. 
	 	$_SESSION['authorized'] = true; //they have passed inspection.
	 	$_SESSION['style'] = get_style($username,$server); //get style
	 	$_SESSION['status'] = get_status($username,$server); //get their status.
		$_SESSION['type'] = get_type($username,$server); // get their type.
		$_SESSION['userip'] = getIP(); //get their ip address, we hope.
		$_SESSION['lastlog'] = get_lastlog($username, $server); //get lastlog before we update
		set_lastlog($username, $server); //update lastlog 
		
	 	if ($_SESSION['status'] == 'Inactive')
		{
		 print_htmlhead("Account Login",0);
		 bank_error('Your accounts has been set as inactive by an Administrator.');
		 bank_error('This will prevent you from logging in to the bank!');
		 bank_error('Please contact an Administrator.');
		 $_SESSION['authorized']= false;
		}
		else
	 	{
		 header('Location: ./index.php' ) ;
		 //print('You are now logged in.<br />');
		}
	 }
	 else
	 {
	 print_htmlhead("Account Login",0);
	 print ('Bad username or password. Try again.<br />');
	 print_form();
	 }
  }
  else 
  {
	 print_htmlhead("Account Login",0);
   print ('Bad username or password. Try again.<br />');
   print_form();
  }
  mysql_close($server); //disconnect
 }
}

print_htmlfoot();

//functions

function print_form()
{
 print ('<form action="login.php" method="post">');
 print ('Account Name ');
 print ('<input type="text" name="username" value="username" />');
 print ('<br />Password ');
 print ('<input type="password" name="userpass" value="" />');
 print ('<br /><br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Login" />');
 print ('</form>');
 print ('<br /><a href="resetpw.php">Lost Password?</a><br />');
 print ('<i><br />*Username and password are case sensitive.</i>');
}

?>
