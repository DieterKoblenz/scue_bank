<?php
/*
File:			sens.rps.php
Version:		0.1
Author:			Jeroen

Description:	This page handles the full payment and returns error or confirmation

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
// http://jecom.nl/blog/?wpsc_action=gateway_notification&gateway=my_new_gateway
$api = mysql_real_escape_string($_POST["api"]); //API code (from store)
$sender = $_SESSION['username']; // Who's sending it

begin_transaction($server);
$transaction = transfer_funds('Jecom International', $sender, $receiver, $amount, $description, $server);
commit_transaction($server);


if ($transaction == "0") {
    echo "<br>
	
	<p>Transaction succesfull! </p>"; //We now need to code the cURL return script so we can report to the payment module.
	
	$post_data['transaction_id']= $tid; //This is all wordpress needs right now




 
//traverse array and prepare data for posting (key1=value1)
foreach ( $post_data as $key => $value) {
    $post_items[] = $key . '=' . $value;
}
 
//create the final string to be posted using implode()
$post_string = implode ('&', $post_items);
 $url = $return;
//create cURL connection
$curl_connection = 
  curl_init($url);
  
  



curl_setopt($curl_connection,CURLOPT_URL,$url);
 
//set options
curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl_connection, CURLOPT_USERAGENT, 
  "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, true);
 
//set data to be posted
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
 
//perform our request
$result = curl_exec($curl_connection);
 
//show information regarding the request
(curl_getinfo($curl_connection));
// echo curl_errno($curl_connection) . '-' . 
  //              curl_error($curl_connection);
 
//close the connection
curl_close($curl_connection);
	                
	
	
	
	
} 
else if ($transaction !== "0")
{
    echo "<br>
	
	
	<p>Unfortunately this transaction was not executed properly because of the reason stated above. Please return to the application and try again.</p>";
}


?>