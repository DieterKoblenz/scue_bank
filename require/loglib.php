<?php

error_reporting(E_ALL); ///YES! YES! YES!
require_once 'util.php'; //utility functions
require_once 'config.php'; //Server info

//Various functions for the logging feature. These functions directly access the database. 

function get_date($id, $server)
//get the date of transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT date FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $date= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $date['date']; 
}

function get_destination($id, $server)
//return the destination of funds for transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT destination FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $destination= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $destination['destination']; 
}

function get_instigator($id, $server)
//return the instigator of transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT instigator FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $instigator= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $instigator['instigator']; 
}

function get_userip($id, $server)
//return the userip for instigator of transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT userip FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $data = mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $data['userip']; 
}

function get_last_log_id($server)
//Return the last idnumber generated
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT LAST_INSERT_ID() FROM ".DB_TABLE_LOG); 
 $result = mysql_query($orders, $server);
 return mysql_result($result, 0);
}

function get_reason($id, $server)
//Get the reason for transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT reason FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $reason= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $reason['reason']; 
}

function get_source($id, $server)
//return the source of funds for transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT source FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $source= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $source['source']; 
}

function get_tranfunds($id, $server)
//Get the funds that were moved in transaction $id
{
 $id = clean_input($id); //clean input.
 $orders = sprintf("SELECT funds FROM ".DB_TABLE_LOG." WHERE id='%u'",mysql_real_escape_string($id)); 
 $result = mysql_query($orders, $server);
 $funds= mysql_fetch_assoc($result);

 mysql_free_result($result); //Hello...Housekeeping!
 return $funds['funds']; 
}

function log_list($server)
//bring up a list of all transaction numbers and return it in an array, in numerical order.
{
 $orders = sprintf("SELECT id FROM ".DB_TABLE_LOG);
 $result = mysql_query($orders, $server);
 
 $i=0;
 $idlist = array(); //create our array
 $ids = mysql_fetch_assoc($result); //prime the thingy
 while ($ids) //as long as we got ids keep going.
 {
 	$idlist[$i] = $ids['id'];
	$ids = mysql_fetch_assoc($result);
	$i++;
 }

 sort($idlist);//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $idlist;
}

function log_transfer($instigator, $source, $destination, $funds, $reason, $server)
//Logs a transaction. Who moved the money, from where, to where, how much, why, when (server creats a id number for us. 
//Cleans all inputs. 
{
 //clean inputs
 $instigator = clean_input($instigator);
 $source = clean_input($source);
 $destination = clean_input($destination);
 $funds = clean_funds($funds);
 $reason = clean_input($reason);
 $userip = $_SESSION['userip'];

 $orders = sprintf("INSERT INTO ".DB_TABLE_LOG." (instigator, source, destination, funds, reason, userip, date) VALUES ('%s','%s', '%s', '%f', '%s', '%s', '%s')",
						mysql_real_escape_string($instigator),
            mysql_real_escape_string($source),
						mysql_real_escape_string($destination), 
						mysql_real_escape_string($funds),
						mysql_real_escape_String($reason),
						mysql_real_escape_String($userip),
						mysql_real_escape_string(date("Y-m-d G:i:s"))); //a sanitary query
 $result = mysql_query($orders, $server);

 if (!$result)
 {
	print ("Could not add log for reason: ");
	echo mysql_error();
	print ("<br>");
	return 1;
 }
 return 0;
}

function personal_log_list($viewtype, $username, $server)
//bring up a list of transaction numbers and return it in an array, in numerical order for a user
//on 0 bring them all up, on 2 bring up only when the user sent, on 2 bring up only the recieved
{
 $username = clean_input($username); //clean.
 //Create the quary we need
 if ($viewtype == 0)
 	$orders = sprintf("SELECT id FROM ".DB_TABLE_LOG." WHERE source = '%s' OR destination = '%s'",$username, $username);
 elseif ($viewtype == 1)
 	$orders = sprintf("SELECT id FROM ".DB_TABLE_LOG." WHERE source = '%s'",$username);
 elseif ($viewtype == 2)
  $orders = sprintf("SELECT id FROM ".DB_TABLE_LOG." WHERE destination = '%s'",$username);

 //Make it happen capin'
 $result = mysql_query($orders, $server);
 
 $i=0;
 $idlist = array(); //create our array
 $ids = mysql_fetch_assoc($result); //prime the thingy
 while ($ids) //as long as we got ids keep going.
 {
 	$idlist[$i] = $ids['id'];
	$ids = mysql_fetch_assoc($result);
	$i++;
 }

 sort($idlist);//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $idlist;
}
?>