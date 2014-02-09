<?php
/*
File:			process.rps.php
Version:		0.1
Author:			Jeroen

Description:	Get information again and start veryfing. Present user with confirmation page! Perhaps do this in one page

Changelog:
0.1 First version --> uses the old code and cleans it up. Added description.


*/

// Includes
include_once 'inc.rps.php'; // Main inclusion file for all pages

// Incoming variables
$receiver =  mysql_real_escape_string($_POST["receiver"]); //Receiving party (usually store owner)
$amount =  mysql_real_escape_string($_POST["amount"]); //How much credits (need to clean this?)
$description =  mysql_real_escape_string($_POST["description"]); //Important for WP module
$tid =  mysql_real_escape_string($_POST["tid"]); //Wordpress transaction id
$return =  mysql_real_escape_string($_POST["return"]); //Used to cURL the result back
$api = mysql_real_escape_string($_POST["api"]); //API code (from store)

//Begin page
//Header
print_htmlhead("Remote Payment System", true); 

//Body
if ($_SESSION['authorized']) {

print('
<h1>Confirm payment</h1>
<p>Please be carefull to confirm your payment to the other party. All transactions are logged to prevent fraud.</p>

<form name="confirmation" method="post" action="'.$home_url.'send.rps.php">
<table width="200" border="1">
  <tr>
    <td>Your account name:</td>
    <td>'.$sender.'</td>
  </tr>
  <tr>
    <td>Current Balance:</td>
    <td>'.$balance.'</td>
  </tr>
</table>
<hr>
<table width="200" border="1">
  <tr>
    <td>Receiving party:</td>
    <td>'.$receiver.'</td>
  </tr>
  <tr>
    <td>Description:</td>
    <td>'.$description.'</td>
  </tr>
    <tr>
    <td>Amount:</td>
    <td>'.$amount.'</td>
  </tr>
</table>
<hr>
<table width="500" border="1">
  <tr>
    <td align="center">I hereby confirm this information is correct and want to transfer the money.</td>
  </tr>
    <tr>
    <td align="center"><input name="Submit" type="submit" value="Submit"></td>
  </tr>
</table>
<form name="confirmation" method="post" action="'.$home_url.'process.rps.php">
<input name="receiver" type="hidden" value="'.$receiver.'" />
<input name="description" type="hidden" value="'.$description.'" />
<input name="amount" type="hidden" value="'.$amount.'" />
<input name="tid" type="hidden" value="'.$tid.'" />
<input name="return" type="hidden" value="'.$return.'" />
<input name="amount" type="hidden" value="'.$amount.'" />
<input name="api" type="hidden" value="'.$inputedapi.'" />
</form>');


 }
 else //they really ought log in.
 {
  print ("
	<br><br>
	 <ul>
	  <li>You should have logged in first. Do this, then return to the order. No money was withdrawn.</li>
	 </ul>
	 <br><br>

	
  ");
 } 

//Footer
print_htmlfoot();



?>