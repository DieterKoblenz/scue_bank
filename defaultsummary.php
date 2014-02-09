<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("National Summary");


//the main event.
if ($_SESSION['authorized']) //check for login
{
 //Connect to Server
 $server = mysql_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD); //connect to server
 if (!$server)
 print ("Could not connect to ".BANK_NAME." Server<br />");			 

 $database = mysql_select_db (DB_DATABASE, $server); //Select bank database
 if (!$database)
 print ("Could not select ".BANK_NAME." Database<br />");

 $subdivisionFundsListRes = mysql_query ("SELECT funds, username, subdivision FROM " . DB_TABLE_ACCOUNT . " WHERE country = '".$_SESSION['country']."'", $server);


 $subdivisionFundsList = array (); // array of subdivision name => subdivision, where a subdivision is an array of citizen name => citizen's funds
 $subdivisionFundsTotals = array (); // array of subdivision name => total funds in the subdivision

 while ($row = mysql_fetch_assoc ($subdivisionFundsListRes)) {
   $subdivisionFundsList[$row['subdivision']][$row['username']] = $row['funds'];
   if (isset ($subdivisionFundsTotals[$row['subdivision']]))
     $subdivisionFundsTotals[$row['subdivision']] += $row['funds'];
   else
     $subdivisionFundsTotals[$row['subdivision']] =  $row['funds'];
 }
 
 print ("<h3>Subdivision Summary</h3>");

 print ("<table>\n<tr>\n<th class='left'>Subdivision Name</th>\n<th class='center'>Subdivision Total</th></tr>\n");

 $sumTotal = 0;

 foreach ($subdivisionFundsTotals as $subdivision => $funds) {
   print ("<tr><td class='left'>" . $subdivision . "<td class='right'>" . $funds . "</td></tr>");
   $sumTotal += $funds;
 }

 print("<tr><th class='left'>National Total</th><td class='right'>".$sumTotal."</td></tr>");
 print ("</table>");

}
else
{
 print ('You must <a href="login.php">login</a> to use this page.<br />');
}

bank_menu(); 
print_htmlfoot();
?>