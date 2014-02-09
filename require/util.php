<?php

require_once 'dblib.php';
require_once 'sublib.php';
require_once 'config.php'; 
require_once 'htmllib.php';

define ("UTIL_PHP", true); //so we can see if this lib is loaded. 

/// utility functions ///

function check_password($username, $userpass, $server)
//check to see if the supplied password matches the password for the account
{
if (get_password(clean_input($username), $server) == encrypt_password($username, $userpass))
	return true;
else
	return false;
}

function encrypt_password($username, $userpass)
//encypts a password using md5 and the username to salt..
{
$saltpass = $username.$userpass;//"salt" the password
return md5($saltpass); //encypt it  
}

function clean_funds($funds)
//makes a string from a form into a float with 2 decimal places, rounding extra
//will use clean_input() for further safety. 
{
//clean up the number. (round down to 2nd decimal place)
$t = clean_input($funds); 
$t = round($t,2);
return $t;
}

function clean_input($input)
//removes white space at front and back, and trims off tags. 
{
 //clean up an input.
 return trim(strip_tags($input));
}

function begin_session()
//start up a new session. set initial variables we might need.
{
 session_start();
 if (!isset($_SESSION['session'])) 
 {
 	$_SESSION['session'] = true;
	$_SESSION['authorized'] = false;
	$_SESSION['username'] = '';
	$_SESSION['subdivision'] = '';
	$_SESSION['country'] = '';
	$_SESSION['admin'] = false;
	$_SESSION['style'] = '';
	$_SESSION['status'] = 'User';
	$_SESSION['type'] = 'Private';
	$_SESSION['userip'] = 'UNKNOWN';
	$_SESSION['lastlog'] = '';
 }
}

function bank_server_connect()
//Connects to the bank server and returns a resource for other functions to use. 
{
 //Connect to Server
 $server = mysql_connect (DB_SERVER, DB_USERNAME, DB_PASSWORD); //connect to server
 if (!$server)
 print ("Could not connect to ".BANK_NAME." Server<br />");			 

 $database = mysql_select_db (DB_DATABASE, $server); //Select bank database
 if (!$database)
 print ("Could not select ".BANK_NAME." Database<br />");
 
 return $server;
}

function begin_transaction($server)
//begins a new transaction
{
 mysql_query("BEGIN", $server);
}

function abort_transaction($server)
//will bring a transaction to a close and abort.
{
 mysql_query("ROLLBACK", $server);
}

function commit_transaction($server)
//will bring a transaction to a close and commit. 
{
 mysql_query("COMMIT", $server);
}

function getIP() 
//posted by 8ta8ta on phpbuilder.com
//gets the user's IP address for later logging. 
//Returns a the content of the enviornment variable. 
//returns UNKOWN is we can't get an IP. 
{
$ip;
if (getenv("HTTP_CLIENT_IP"))
$ip = getenv("HTTP_CLIENT_IP");
else if(getenv("HTTP_X_FORWARDED_FOR"))
$ip = getenv("HTTP_X_FORWARDED_FOR");
else if(getenv("REMOTE_ADDR"))
$ip = getenv("REMOTE_ADDR");
else if ($_SERVER['SERVER_ADDR'])
$ip = $_SERVER['SERVER_ADDR'];
else
$ip = "UNKNOWN";
return $ip;

} 

function email_valid($email)
//validates the format of an email address. 
//return false if not valid. True if valid. 
{
 return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

 
function generate_password($length=9, $strength=4)
//generates a password. 
//Stolen from http://www.webtoolkit.info/php-random-password-generator.html
//strength 8 isn't supported by the bank. 
{
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) 
	{
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) 
	{
		$vowels .= "AEUY";
	}
	if ($strength & 4) 
	{
		$consonants .= '23456789';
	}
	if ($strength & 8) 
	{
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) 
	{
		if ($alt == 1) 
		{
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else 
		{
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}
 
?>