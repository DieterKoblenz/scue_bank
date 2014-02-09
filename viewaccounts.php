<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("User Account List",0);


//the main event.
if ($_SESSION['authorized'] AND (($_SESSION['username'] ==  DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional'))) //check for login and make sure they are the more then a user or inactive.
{
 //Connect to Server
 $server = bank_server_connect();

 //Generate orders. 
 if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))
 {
  $orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT); 
	$list = userlist_given($orders, $server);
 }
 else if ($_SESSION['status'] == 'National')
 //Generate list of users for just their nation
 {
 	$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE country='%s' AND status!='Inactive'",mysql_real_escape_string($_SESSION['country']));    
 	$list = userlist_given($orders, $server);
 }
 else if ($_SESSION['status'] == 'Regional')
 
 //Generate list of users for just their nation and subdivision
 {
 	$orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE (country='%s' AND subdivision='%s' AND status!='Inactive')",mysql_real_escape_string($_SESSION['country']),mysql_real_escape_string($_SESSION['subdivision']));    
 	$list = userlist_given($orders, $server);
 }
	
 //sort the list
 sort($list);

 //go through the list
 $j = count($list);
 
 //list of letter links
 print ('<div class="selflink" align="right"><a href="##">#</a>
 <a href="#A">A</a>
 <a href="#B">B</a>
 <a href="#C">C</a>
 <a href="#D">D</a>
 <a href="#E">E</a>
 <a href="#F">F</a>
 <a href="#G">G</a>
 <a href="#H">H</a>
 <a href="#I">I</a>
 <a href="#J">J</a>
 <a href="#K">K</a>
 <a href="#L">L</a>
 <a href="#M">M</a>
 <a href="#N">N</a>
 <a href="#O">O</a>
 <a href="#P">P</a>
 <a href="#Q">Q</a>
 <a href="#R">R</a>
 <a href="#S">S</a>
 <a href="#T">T</a>
 <a href="#U">U</a>
 <a href="#V">V</a>
 <a href="#W">W</a>
 <a href="#X">X</a>
 <a href="#Y">Y</a>
 <a href="#Z">Z</a><br />
 <a href="#bottom">Bottom of Page</a></div><br />');

 //table
 print ('<table>'); //and create a table as we go...
 print ('<tr><th class="left">Username</th><th class="left">Subdivision</th><th class="left">Country</th><th class="right">Funds</th><th class="right">Status</th><th class="right">Type</th><th class="right">Email</th><th class="right">Last Login</th></tr>');
 //link stuff
 $firstchar = '';
 $printchar = '';
 $count = 0;
 $fundcount = 0;
 $parity = 0;
 for ($i = 0; $i < $j; $i++)
 {
  $printbreak = false;
  $parityPrinted = ($parity == 0) ? "One" : "Two";
  $parity = (1 + $parity) % 2;
  //get the first letter for checking and make it uppercase.
  $firstchar = strtoupper(substr($list[$i],0,1));
  //start checking
  if (!ctype_alpha($firstchar)) //is a non-alpha
    {
      if ($printchar != '#') //did we print # already?
	{
	  $printchar = '#';
	  $printbreak = true;
	}      
    }
  else if ($printchar != $firstchar) //Now we print letters
    {
      $printchar = $firstchar;
      $printbreak = true;
    }
 
  if ($printbreak)
    {
      print ("<tr class='left'><td
class='left'><h3>".$printchar."</h3></td><td
class='left'><a name='".$printchar."'></a></td><td
</td><td
</td><td
</td><td
</td><td
class='right'><a href='#'>Top of page.</a></td></tr>");
    }
  
  //back to the table
 	print ("<tr class='left one".$parityPrinted."'><td
class='left'>".$list[$i]."</td><td class='left'>".get_Subdivision($list[$i],$server)."</td><td class='left'>".get_Country($list[$i],$server)."</td><td class='right'>".get_funds($list[$i],$server)."</td><td class='right'>".get_status($list[$i],$server)."</td><td class='right'>".get_type($list[$i],$server)."</td><td class='right'>".get_email($list[$i],$server)."</td><td class='right'>".get_lastlog($list[$i],$server)."</td></tr>");
	$fundcount += get_funds($list[$i],$server); //keep track of total funds.
	$count++;
 }
 print ('</table>');
 print ("<br>There are ".$count." accounts displayed. With a total of ".$fundcount." funds."); //How many accounts.
 print ('<div class="selflink" align="right"><a href="#">Top of page.</a></div><br />');
 print ('<a name="bottom"></a>');
 
}
else
{
 print ('You must <a href="login.php">login</a> as an admin, in order to use this page');
}

print_htmlfoot();
?>