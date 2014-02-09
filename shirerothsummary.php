<?php
require_once 'require/util.php'; //utility functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Shireroth Summary");


//the main event.
if ($_SESSION['authorized']) //check for login
{
 //connect to the database
 $server = mysql_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD); //connect to server
 if (!$server)
 print ("Could not connect to ShireBank Server for reason: " . $access . "<br />");			 

 $database = mysql_select_db (DB_DATABASE, $server); //Select bank database
 if (!$database)
 print ("Could not select ShireBank Database for reason: " . $access . "<br />");
 
 $subdivisionFundsListRes = mysql_query ("SELECT funds, username, subdivision FROM " . DB_TABLE_ACCOUNT . " WHERE country = 'Shireroth'", $server);

 $subdivisionFundsList = array (); // array of subdivision name => subdivision, where a subdivision is an array of citizen name => citizen's funds
 $subdivisionFundsTotals = array (); // array of subdivision name => total funds in the subdivision

 while ($row = mysql_fetch_assoc ($subdivisionFundsListRes)) {
   $subdivisionFundsList[$row['subdivision']][$row['username']] = $row['funds'];
   if (isset ($subdivisionFundsTotals[$row['subdivision']]))
     $subdivisionFundsTotals[$row['subdivision']] += $row['funds'];
   else
     $subdivisionFundsTotals[$row['subdivision']] =  $row['funds'];
 }
	 
 if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR (($_SESSION['status'] == 'National') AND ($_SESSION['country'] == 'Shireroth')))
 //If they are an admin or a 'National' account of Shireroth.
 {
  // sort the duchies in alphabetical order to make things look nicer
  ksort ($subdivisionFundsList);
  ksort ($subdivisionFundsTotals); 
  
  // also sort the people within each duchy
  foreach ($subdivisionFundsList as $subdivision => $foo) 
    ksort ($subdivisionFundsList[$subdivision]);

  print ("<table>\n");
  $parity = 0;
  foreach ($subdivisionFundsList as $subdivision => $citizens) 
	{
    $parityPrinted = ($parity == 0) ? "one" : "two";
    $parity = (1 + $parity) % 2;

    print (" <tr>\n  <th class = '$parityPrinted' rowspan='" . (count($citizens) + 1) . "'>" . subdivision_name($subdivision) . "<br /> total: " . $subdivisionFundsTotals[$subdivision] . "</th></tr>");

    $parity2 = 0;
    foreach ($citizens as $cit => $citMoney) 
		{
      $parity2Printed = ($parity2 == 0) ? "One" : "Two";
      $parity2 = (1 + $parity2) % 2;

      print ("\n   <tr class='left $parityPrinted$parity2Printed'><td>" . $cit . "</td><td class='right'>" . $citMoney . "</td></tr>");
    }
  }

  print ("</table>");
 }

 print ("<h3>Subdivision Summary</h3>");

 print ("<table>\n<tr>\n<th class='left'>Subdivision Name</th>\n<th class='center'>Subdivision Total</th></tr>\n");

 $sumTotal = 0;

 foreach ($subdivisionFundsTotals as $subdivision => $funds) 
 {
  print ("<tr><td class='left'>" . subdivision_name($subdivision) . "<td class='right'>" . $funds . "</td></tr>");
  $sumTotal += $funds;
 }

 print("<tr><th class='left'>System Total</th><td class='right'>".$sumTotal."</td></tr>");
 print ("</table>");

}
else
{
 print ('You must <a href="login.php">login</a> to use this page.<br />');
}

bank_menu(); 
print_htmlfoot();
?>
 