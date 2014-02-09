<?php
/*
File:			incoming.rps.php
Version:		0.1
Author:			Jeroen

Description:	This page will accept remote and local POST encoded forms, clean the input to prevent missuse and it will check API access.

Changelog:
0.1 First version --> uses the old code and cleans it up. Added description.


*/

// Includes
include_once 'inc.rps.php'; // Main inclusion file for all pages

// Incoming variables
$receiver =  mysql_real_escape_string($_POST["USER"]); //Receiving party (usually store owner)
$amount =  mysql_real_escape_string($_POST["AMT"]); //How much credits (need to clean this?)
$description =  mysql_real_escape_string($_POST["PROD_NAME0"]); //Important for WP module
$tid =  mysql_real_escape_string($_POST["SID"]); //Wordpress transaction id
$return =  mysql_real_escape_string($_POST["return_url"]); //Used to cURL the result back
$inputedapi = mysql_real_escape_string($_POST["API"]); //API code (from store)

/* Alternative variables now not in use
$description = mysql_real_escape_string($_POST["DESC"]); <-- alternative 
$tid = mysql_real_escape_string($_POST["TID"]); <-- alternative
*/

// Print the hidden form:
print('
<form name="confirmation" method="post" action="'.$home_url.'process.rps.php">
<input name="receiver" type="hidden" value="'.$receiver.'" />
<input name="description" type="hidden" value="'.$description.'" />
<input name="amount" type="hidden" value="'.$amount.'" />
<input name="tid" type="hidden" value="'.$tid.'" />
<input name="return" type="hidden" value="'.$return.'" />
<input name="amount" type="hidden" value="'.$amount.'" />
<input name="api" type="hidden" value="'.$inputedapi.'" />
</form>');

?>

<SCRIPT LANGUAGE="JavaScript">
function submitForm(){
document.confirmation.submit(); 
}
</SCRIPT>

<body onload='submitForm()'>