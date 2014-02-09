<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/sublib.php'; //subdivision database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("View Countries",0);

 //Connect to Server
 $server = bank_server_connect();
	
//the main event.
if ($_SESSION['authorized'] AND (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //check for login and make sure they are the admin
{
 $list = country_list($server);
 foreach ($list as $country)
 {
 	print ($country."<br />");
 }
}
else
{
 print('You must <a href="login.php">login</a> as an admin, to use this page.<br />');
}

print_htmlfoot();

?>