<?php

error_reporting(E_ALL); ///YES! YES! YES!
require_once 'util.php'; //utility functions
require_once 'config.php'; //Server info
require_once 'htmllib.php';//html functions

///Database access and various operation functions///

function add_account($username, $userpass, $funds, $subdivision, $country, $type, $owner, $email, $server)
//creates a user account and sets all supplied parts.
{
 $orders = sprintf("INSERT INTO ".DB_TABLE_ACCOUNT." (username, userpass, funds, subdivision, country, type, owner, style, status, email, lastlog) VALUES ('%s','%s','%f','%s','%s','%s','%s','%s','%s','%s','%s')",
 				 	 	mysql_real_escape_string(clean_input($username)),
            mysql_real_escape_string(encrypt_password(clean_input($username),clean_input($userpass))), 
						mysql_real_escape_string(clean_funds($funds)),
						mysql_real_escape_string(clean_input($subdivision)),
						mysql_real_escape_string(clean_input($country)),
						mysql_real_escape_string(clean_input($type)),
						mysql_real_escape_String(clean_input($owner)),
						mysql_real_escape_string(HTML_STYLE),
						mysql_real_escape_string('User'),
						mysql_real_escape_string($email),
						mysql_real_escape_string(date("Y-m-d G:i:s"))); //a sanitary query
 $result = mysql_query($orders, $server);

 if (!$result)
 {
	print ("Could not add account for reason: ");
	echo mysql_error();
	print (".<br />");
	return 1;
 }
 return 0;
}

function delete_account($username, $server)
//Deletes a user account. Assumes you really wanna do this..
//Make sure to move their money first, before calling this, if you want to keep it.
{
 $orders = sprintf("DELETE FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username)));  
 mysql_query($orders, $server); 
}

function find_account($username, $server)
//Checks to see if an account exists.
//returns false if they don't exist.
//otherwise will return the number that match
//Though we always assume only 1 match if we get any. If we had more then it's a serious problem. 
//Error handling should be added somewhere for that.
{
 $orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username)));  
 $result = mysql_query($orders, $server); //pull up results for all username that match.. username.
 $existence = mysql_num_rows($result); //check how many rows we got. 0 means we got no person of that name otherwise..we got something...

 mysql_free_result($result); //Hello...Housekeeping!
 return $existence;
}

function get_password($username, $server)
//retrieves the password from the database
//note: it will be encrypted when we get it.
{
 $orders = sprintf("SELECT userpass FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $password= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $password['userpass']; 
}

function set_password($username, $userpass, $server)
//set the password on an account
//passwords will be encypted then put into the database
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET userpass='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string(encrypt_password(clean_input($username),clean_input($userpass))), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_email($username,$server)
//Get the email for the account
//we assume the account is there and was checked for previously.
{
 $orders = sprintf("SELECT email FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $data = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $data['email']; 
}

function set_email($username, $email, $server)
//Sets the email on a username
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET email='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string($email), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_funds($username,$server)
//Get the funds in an account
//we assume the account is there and was checked for previously.
{
 $orders = sprintf("SELECT funds FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $funds = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $funds['funds']; 
}

function add_funds($username, $funds, $server)
//adds funds to an account
{
 $current_funds = get_funds(clean_input($username), $server); //get current funds
 $new_funds = $current_funds + clean_funds($funds); //figure out the new funds
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET funds='%f' WHERE username='%s'",$new_funds, mysql_real_escape_string(clean_input($username))); 

 mysql_query($orders,$server);    //raise the blade and make the change

 //Some error check function
 $changed_funds = get_funds(clean_input($username), $server);
 if (clean_funds($new_funds) != clean_funds($changed_funds))
   bank_error("SOMETHING BAD HAPPENED! We should have put in ".$new_funds." but we really put in ".$changed_funds);
}

function remove_funds($username, $funds, $server)
//removed funds from an account
//making sure they have the funds to be removed would be done before calling this function
{
 $current_funds = get_funds(clean_input($username), $server); //get current funds
 $new_funds = $current_funds - clean_funds($funds); //figure out the new funds
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET funds='%f' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string($new_funds), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_subdivision($username, $server)
//return the subdivision for the username
{
 $orders = sprintf("SELECT subdivision FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $code = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $code['subdivision']; 
}

function set_subdivision($username, $subdivision, $server)
//Sets the subdivision on a username
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET subdivision='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string(clean_input($subdivision)), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_country($username, $server)
//return the country code for the username
{
 $orders = sprintf("SELECT country FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $code = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $code['country']; 
}

function set_country($username, $country, $server)
//Sets the country code on a username
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET country='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string(clean_input($country)), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_type($username, $server)
//returns type of the account
{
 $orders = sprintf("SELECT type FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $code = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $code['type'];
}

function set_type($username, $type, $server)
//set the account type
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET type='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string(clean_input($type)), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_owner($company, $server)
//gets the name of the owner of the company
//return empty if there is no company
{
 $orders = sprintf("SELECT owner FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($company))); 
 $result = mysql_query($orders, $server);
 $code = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $code['owner'];
}

function set_owner($owner, $company, $server)
//Sets the name of the owner of the company
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET owner='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string(clean_input($owner)), 
																 mysql_real_escape_string(clean_input($company)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function set_style($username, $style, $server)
//Sets the style on a username
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET style='%s' WHERE username='%s'",
			 	 								 				 mysql_real_escape_string(clean_input($style)), 
																 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_style($username, $server)
//return the style for the username
{
 $orders = sprintf("SELECT style FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $code = mysql_fetch_assoc($result);
 
 mysql_free_result($result); //Hello...Housekeeping!
 return $code['style']; 
}

function set_status($username, $status, $server)
//Sets the status of a username
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET status='%s' WHERE username='%s'",
 				 mysql_real_escape_string(clean_input($status)), 
				 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_status($username, $server)
//return the status of the username
{
 $orders = sprintf("SELECT status FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $code = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $code['status']; 
}

function set_lastlog($username, $server)
//Sets the lastlog of a username
{
 $orders = sprintf("UPDATE ".DB_TABLE_ACCOUNT." SET lastlog='%s' WHERE username='%s'",
 				 mysql_real_escape_string(date("Y-m-d G:i:s")), 
				 mysql_real_escape_string(clean_input($username)));
 mysql_query($orders,$server);    //raise the blade and make the change
}

function get_lastlog($username, $server)
//return the status of the username
{
 $orders = sprintf("SELECT lastlog FROM ".DB_TABLE_ACCOUNT." WHERE username='%s'",mysql_real_escape_string(clean_input($username))); 
 $result = mysql_query($orders, $server);
 $data = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $data['lastlog']; 
}

function userlist($server)
//get a list of users and return an array with those names in alphabetical order.
//Inactive status accounts will be included. 
{
 $orders = sprintf("SELECT username FROM ".DB_TABLE_ACCOUNT); 
 $result = mysql_query($orders, $server);

 $i=0;
 $userlist = array(); //create our array
 $users = mysql_fetch_assoc($result); //prime the thingy
 while ($users) //as long as we got names keep going.
 {
 	$userlist[$i] = $users['username'];
 	$users = mysql_fetch_assoc($result);
 	$i++;
 }

 usort($userlist, 'strnatcasecmp');//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $userlist;
}

function userlist_given($orders, $server)
//get a list of users based on orders given and return an array with those names in alphabetical order.
{
 $result = mysql_query($orders, $server);

 $i=0;
 $userlist = array(); //create our array
 $users = mysql_fetch_assoc($result); //prime the thingy
 while ($users) //as long as we got names keep going.
 {
 	$userlist[$i] = $users['username'];
 	$users = mysql_fetch_assoc($result);
 	$i++;
 }

 usort($userlist, 'strnatcasecmp');//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $userlist;
}

?>