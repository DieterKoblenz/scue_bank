<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/loglib.php'; //logging functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Personal Transaction Log",0);

//the main event.
if ($_SESSION['authorized']) //check for login
{

  print_form();
 //Connect to Server
 $server = bank_server_connect();
	
 //get a list of ids and go through it
 $list = array();
 
 if (!isset($_POST['submitted'])) //was the form submitted?
  $list = personal_log_list(0, $_SESSION['username'],$server); //Default to showing all
 else if (isset($_POST['submitted'])) //The form was submitted.
 {
 	if ($_POST['viewtype'] == 0)
	 $list = personal_log_list(0, $_SESSION['username'],$server);
 	else if ($_POST['viewtype'] == 1)
	 $list = personal_log_list(1, $_SESSION['username'],$server);
	else if ($_POST['viewtype'] == 2)
	 $list = personal_log_list(2, $_SESSION['username'],$server);
 }
 
 //list sorting
 if (!isset($_POST['submitted']))
   $list = array_reverse($list);
 else if ($_POST['descending'] == 'true')
   $list = array_reverse($list);

 $j = count($list);
 print ('<div class="selflink" align="right"><a href="#bottom">Bottom of Page</a></div><br />');
 print ('<table>'); //and create a table as we go...
 print ('<tr><th class="left">ID#</th><th class="left">Source</th><th class="left">Destination</th><th class="right">Funds</th><th class="left">Reason</th><th class="left">Date</th></tr>');
 $count = 0;
 for ($i = 0; $i < $j; $i++)
 {
 	print ("<tr><td class='left'>".$list[$i]."</td><td class='left'>".get_source($list[$i],$server)."</td><td class='left'>".get_destination($list[$i],$server)."</td><td class='right'>".get_tranfunds($list[$i],$server)."</td><td class='left'>".get_reason($list[$i],$server)."</td><td class='left'>".get_date($list[$i],$server)."</td></tr>");
	$count++;
 }
 print ('</table><br />');
 
 print ("<br />Current funds: ".get_funds($_SESSION['username'],$server)."<br />"); 
 print ('<div class="selflink" align="right"><a href="#">Top of page.</a></div><br />');
 print ('<a name="bottom"></a>');
 
 
}
else
{
 print ('You must <a href="login.php">login</a>, in order to use this page');
}

print_htmlfoot();
 
function print_form()
{
 print ('<br />Which transactions do you wish to view?<br />');
 print ('<form action="tranlog.php" method="post">');
 print ('<input type="radio" value="0" name="viewtype checked">All<br />');
 print ('<input type="radio" value="1" name="viewtype">Outgoing<br />');
 print ('<input type="radio" value="2" name="viewtype">Incoming<br />');
 print ('How shall we sort Transactions?<br />');
 print ('<input type="radio" value="true" name="descending" checked>Sort Descending<br />');
 print ('<input type="radio" value="false" name="descending">Sort Ascending<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="View" />');
 print ('</form>');
}
?>