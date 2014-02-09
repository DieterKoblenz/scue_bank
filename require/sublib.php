<?php

error_reporting(E_ALL); ///YES! YES! YES!
require_once 'config.php'; //Server info
require_once 'util.php'; //utility. 

//Various functions for the subdivision table.

function add_subdivision($subdivision, $country, $server)
//Add a subdivision to the subdivision table
{
 //clean inputs. 
 $subdivision = clean_input($subdivision);
 $country = clean_input($country);
 
 $preorders = sprintf("SELECT subdivision FROM ".DB_TABLE_SUBDIVISION." WHERE subdivision='%s' AND country='%s'", mysql_real_escape_string($subdivision), mysql_real_escape_string($country));
 if (mysql_num_rows(mysql_query($preorders, $server))) //check for existence
 {
 	print ("Could not add subdivision/country because it already existed.<br /> ");
	return 2;
 }
 else //new item
 {
 	$orders = sprintf("INSERT INTO ".DB_TABLE_SUBDIVISION." (subdivision, country) VALUES ('%s','%s')", mysql_real_escape_string($subdivision),mysql_real_escape_string($country));
 	if (!mysql_query($orders, $server)) //was there a problem?
 	{
	 print ("Could not add subdivision/country for reason: ");
	 echo mysql_error();
	 print ("<br />");
	 return 1;
 	}
 }
 return 0;
}

function country_count($server)
//gets the total number of countries in the table. 
{
 $orders = sprintf("SELECT DISTINCT country FROM ".DB_TABLE_SUBDIVISION); 
 $result = mysql_query($orders, $server);
 return mysql_num_rows($result);
}

function country_list($server)
//Return a list of all countries.
//return 0 on empty table.
{
 $orders = sprintf("SELECT DISTINCT country FROM ".DB_TABLE_SUBDIVISION); 
 $result = mysql_query($orders, $server);

 $i=0;
 $list = array(); //create our array
 
 if (!mysql_num_rows($result)) //is the list empty?
  return $list;//abort early
 else
  $countries = mysql_fetch_assoc($result); //prime the thingy
 
 while ($countries) //as long as we got names keep going.
 {
  $list[$i] = $countries['country'];
	$countries = mysql_fetch_assoc($result);
 	$i++;
 }

 usort($list, 'strnatcasecmp');//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $list;
}

function country_list_given($orders, $server)
//Return a list of all countries.
//makes list based on $orders
//return 0 on empty table.
{
 $result = mysql_query($orders, $server);

 $i=0;
 $list = array(); //create our array
 
 if (!mysql_num_rows($result)) //is the list empty?
  return $list;//abort early
 else
  $countries = mysql_fetch_assoc($result); //prime the thingy
 
 while ($countries) //as long as we got names keep going.
 {
  $list[$i] = $countries['country'];
	$countries = mysql_fetch_assoc($result);
 	$i++;
 }

 usort($list, 'strnatcasecmp');//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $list;
}

function delete_subdivision($subdivision, $country, $server)
//Function to delete countries from the server
//Will still leave accounts linked to the country. 
//Basicly makes it so you cannot create anymore account for that nation.
{
 //clean inputs. 
 $subdivision = clean_input($subdivision);
 $country = clean_input($country);

 $orders = sprintf("DELETE FROM ".DB_TABLE_SUBDIVISION." WHERE subdivision='%s' AND country='%s'",
 				 	 				mysql_real_escape_string($subdivision),mysql_real_escape_string($country));  
 if (!mysql_query($orders, $server)) //was there a problem?
 {
	print ("Could not delete subdivision for reason: ");
	echo mysql_error();
	print ("<br />");
	return 1;
 } 
}

function subdivision_list($server)
//Return a list of all subdivisions.
{
 $orders = sprintf("SELECT subdivision FROM ".DB_TABLE_SUBDIVISION); 
 $result = mysql_query($orders, $server);

 $i=0;
 $list = array(); //create our array
 
 if (!mysql_num_rows($result)) //is the list empty?
  return $list;//abort early
 else
  $subdivisions = mysql_fetch_assoc($result); //prime the thingy
 
 while ($subdivisions) //as long as we got names keep going.
 {
  $list[$i] = $subdivisions['subdivision'];
	$subdivisions = mysql_fetch_assoc($result);
 	$i++;
 }

 usort($list, 'strnatcasecmp');//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $list;
}

function subdivision_list_given($country, $server)
//Return a list of all subdivision from the given country.
{
 //clean input
 $country = clean_input($country);

 $orders = sprintf("SELECT subdivision FROM ".DB_TABLE_SUBDIVISION." WHERE country='%s'",mysql_real_escape_string($country)); 
 $result = mysql_query($orders, $server);

 $i=0;
 $list = array(); //create our array
 
 if (!mysql_num_rows($result)) //is the list empty?
 	return $list;//abort early
 else
 	$subdivisions = mysql_fetch_assoc($result); //prime the thingy
 
 while ($subdivisions) //as long as we got names keep going.
 {
  $list[$i] = $subdivisions['subdivision'];
	$subdivisions = mysql_fetch_assoc($result);
 	$i++;
 }

 usort($list, 'strnatcasecmp');//put in order
 mysql_free_result($result); //Hello...Housekeeping!
 return $list;
}

?>
