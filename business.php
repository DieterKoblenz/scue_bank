<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

//Depreciated page!

begin_session();
print_htmlhead("Businesses",0);

if ($_SESSION['authorized'])
{
 link_button_java("Commonwealth Casino", "mods/casino/index.php");
}
else if (!$_SESSION['authorized'])
{
 print ('Please login.<br />');
}

print_htmlfoot();
?>