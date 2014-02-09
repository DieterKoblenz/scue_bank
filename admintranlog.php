<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/loglib.php'; //logging functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead(LANG_ADMINTRANLOG,0);


//the main event.
if ($_SESSION['authorized'] && (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))) //check for login and make sure they are the admin
{
  //Connect to Server
 $server = bank_server_connect();
	
 //get a list of ids and go through it
 $list = array();
 $list = log_list($server);
 $j = count($list);
 
 print ('<div class="selflink" align="right"><a href="#bottom">'.LANG_ADMINTRANLOG_1.'</a></div><br />');
 print ('<table>'); //and create a table as we go...
 print ('<tr><th class="left">ID#</th><th class="left">'.LANG_ADMINTRANLOG_2.'</th><th class="left">'.LANG_ADMINTRANLOG_3.'</th><th class="left">'.LANG_ADMINTRANLOG_4.'</th><th class="right">'.LANG_ADMINTRANLOG_5.'</th><th class="left">'.LANG_ADMINTRANLOG_6.'</th><th class="left" width="150">'.LANG_ADMINTRANLOG_7.'</th><th>IP</th></tr>');
 $count = 0;
 $parity = 0;
 for ($i = 0; $i < $j; $i++)
 {
  $parityPrinted = ($parity == 0) ? "One" : "Two";
  $parity = (1 + $parity) % 2;
 	print ("<tr class=' one".$parityPrinted."'><td class='left'>".$list[$i]."</td><td class='left'>".get_instigator($list[$i],$server)."</td><td class='left'>".get_source($list[$i],$server)."</td><td class='left'>".get_destination($list[$i],$server)."</td><td class='right'>".get_tranfunds($list[$i],$server)."</td><td class='left'>".get_reason($list[$i],$server)."</td><td class='left' width='150'>".get_date($list[$i],$server)."</td><td>".get_userip($list[$i],$server)."</td></tr>");
	$count++;
 }
 print ('</table>');
 print ('<div class="selflink" align="right"><a href="#">'.LANG_ADMINTRANLOG_8.'</a></div><br />');
 print ('<a name="bottom"></a>');
 
}
else
{
 print (LANG_MUSTBEADMIN);
}

print_htmlfoot();
?>