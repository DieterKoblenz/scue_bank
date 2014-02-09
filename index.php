<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();
print_htmlhead(BANK_NAME,0);

if ($_SESSION['authorized'])
{
 //Connect to Server
 $server = bank_server_connect();
 
 //User info
 print (LANG_INDEX_USERNAME.": ". $_SESSION['username']." <br />");
 print (LANG_INDEX_FUNDS.": ". get_funds($_SESSION['username'],$server). "<br />");
 print (LANG_INDEX_RESIDENCE.": ".get_subdivision($_SESSION['username'],$server).", ".get_country($_SESSION['username'],$server).".<br />");
 print (LANG_INDEX_STATUS.": ".get_status($_SESSION['username'], $server).".<br />");
 print (LANG_INDEX_CSSSTYLE.": ".$_SESSION['style']."<br />");
 print (LANG_INDEX_LASTLOGIN.": ".$_SESSION['lastlog']."<br />");
 $email = get_email($_SESSION['username'], $server);
 if ($email == '')
 {
 	print (LANG_INDEX_1);
 }
 else
 {
 	print (LANG_INDEX_EMAIL.": ".$email."<br />");
 }
 //End user info
}
else if (!$_SESSION['authorized'])
{
 print (LANG_INDEX_2);
}

print_htmlfoot();
?>