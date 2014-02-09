<?php
/*
File:			inc.rps.php
Version:	
Author:			Jeroen

Description:	Inclusion file for all subpages.


*/

require_once '../../require/util.php'; //utility functions (need to connect to the server)
require_once '../../require/config.php'; //config file needed for various constants.
require_once '../../require/bankops.php'; //needed if you want to move money. 
require_once '../../require/htmllib.php'; // cool html stuff




//There are more libraries at your disposal but I don't plan to go into them here. 

error_reporting(E_ALL); //I require this. 

begin_session(); //needed for session to work. Only put it once. and before any HTML. 

$server = bank_server_connect(); //now you have a server connection. 
$authorized = $_SESSION['authorized']; //if true they are logged in 
$username = $_SESSION['username']; //their username.

$home_url = "http://www.jecom.nl/bank/mods/jecom/";

?>