<?php
require_once 'require/util.php';
require_once 'require/htmllib.php';
error_reporting(E_ALL); ///YES! YES! YES!

////THIS PAGE IS DEPRECIATED! Look to the menu function in htmllib.php.///

begin_session();
print_htmlhead("Account Options",0);

if ($_SESSION['authorized'])
{
 print ('<a href="changepw.php">Change Password</a><br />');
 print ('<a href="changestyle.php">Change CSS Style</a><br />');
 print ('<a href="userchangesubdivision.php">Change Subdivision</a><br />');
}
else
{
 print ('You must <a href="login.php">login</a> to use this page.<br />');
}
print_htmlfoot();
?>