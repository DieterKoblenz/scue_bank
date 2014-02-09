<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
error_reporting(E_ALL); ///YES! YES! YES!

if (!isSet($_SESSION))
	 begin_session();

//Page generation from here down.

if ($_SESSION['authorized'])
{
 session_unset(); //Kill the session if there is one, otherwise. you are already logged out.
 session_destroy();
 print_htmlhead("Log-out",0);
 print('You are now logged out.<br />');
}
else 
{
 print_htmlhead("Log-out",0);
 print ('You are not <a href="login.php">logged in</a>.<br />');
}

print('<br />');

if (!isSet($_SESSION))
	 begin_session();//keep things from breaking in bank_menu().

print_htmlfoot();

?>