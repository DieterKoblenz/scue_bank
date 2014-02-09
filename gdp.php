<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead("Summary Economic Statistics",0);

 //Connect to Server
 $server = bank_server_connect();
	
 if (!isset($_POST['submitted']))
   {print_form($server);}
 else if (isset($_POST['submitted']))
   {
   if ($_POST['submitted'] == "true")
     {
     if (!isset($_POST['start']))
       {echo "Please enter a start date";
       print_form($server);}
     if (!strtotime($_POST['start']))
       {echo "Please enter a valid date";
       print_form($server);}
     else
       {
       //Take in info, set date into a form the database can understand, and set the end date three months later.
       $gdp_country = $_POST['country'];
       $start_1 = strtotime($_POST['start']);
       $start_2 = getdate($start_1);
       $start = "'" . $start_2['year'] . "-0" . $start_2['mon'] . "-0" . $start_2['mday']. "'";
       $start_date =  $start_2['mday'] . " " . $start_2['month'] . " " . $start_2['year'];
       $end_1 = strtotime('+3 months',$start_1);
       $end_2 = getdate($end_1);
       $end = "'" . $end_2['year'] . "-0" . $end_2['mon'] . "-0" . $end_2['mday']. "'";
       $end_date = $end_2['mday'] . " " .  $end_2['month'] . " " .$end_2['year'];

       $sql = "SELECT l.funds, s.country AS sourceCountry, d.country AS destinationCountry, s.type 
         FROM ".DB_TABLE_ACCOUNT." AS s, ".DB_TABLE_ACCOUNT." AS d, ".DB_TABLE_LOG." AS l 
         WHERE l.source = s.username AND l.destination = d.username AND l.date > $start AND l.date < $end AND (s.country = '$gdp_country' OR d.country = '$gdp_country')";
          
       $res = mysql_query ($sql, $server);

       $consumption = 0;
       $investment = 0;
       $government = 0;
       $exports = 0;
       $imports = 0;

       while ($row = mysql_fetch_assoc ($res)) {
	 if ($row['sourceCountry'] == $gdp_country) {
	   if ($row['destinationCountry'] != $gdp_country)
	     $imports += $row['funds'];

	   switch ($row['type']) {
	   case "Government": $government += $row['funds']; break;
	   case "Private": $consumption += $row['funds']; break;
	   case "Company": $investment += $row['funds']; break;
	   }
	 }
	 else
	   if ($row['destinationCountry'] == $gdp_country)
	     $exports += $row['funds'];
       }

       $gdp = $consumption + $investment + $government + $exports - $imports;
       $nx = $exports - $imports;

       //Results   
       echo "Total GDP of $gdp_country between $start_date and $end_date was $gdp. <br>  Of this, Consumption was ".$consumption.", Investment was ".$investment.", Government Spending was ".$government.", Exports were ".$exports." and Imports were ".$imports.".";
       echo "<br> This meant Net Exports were $nx.";
       //debugging
       //echo "Consumption ".$consumption['C']." Investment ".$investment['I']." Government ".$government['G']." Imports ".$imports['M']." Exports ".$exports['X'];
       //echo "<br> $sql_source <br> $sql_source2 <br> $sql_destination <br> $sql_consumption <br> $sql_investment <br> $sql_government <br> $sql_imports <br> $sql_exports <br>";
       }
     }
   }

print_htmlfoot();

//functions

function print_form($server)
{
 $date = date("d F Y");
 print ('<form action="gdp.php" method="post">');
 print ('Select Country:<br />');
 country_select($server); //print the menu for country names
 print ('<br />');
 print ('Start Date:');
 echo "<input type='text' name='start' value='" . $date . " '/>";
 print ('<br />');
 print ('<input type="hidden" name="submitted" value="true" />');
 print ('<input type="submit" value="Calculate GDP" />');
 print ('</form>'); 
 print ('<i>GDP is calcualted over a three month period.</i>');
}

?>