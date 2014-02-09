<?php
require_once 'require/util.php'; //utility functions
require_once 'require/htmllib.php'; //HTML functions
require_once 'require/dblib.php'; //database functions
require_once 'require/config.php'; //server info
error_reporting(E_ALL); ///YES! YES! YES!

begin_session();

//begin page generation
print_htmlhead(LANG_BANKSTATS,0);


 //Connect to Server
 $server = bank_server_connect();

$BankListResult = mysql_query ("SELECT funds, type, username, subdivision, country FROM " . DB_TABLE_ACCOUNT . " WHERE status != 'Inactive'", $server); //pull up the table. ignore inactive accounts.
 
$bankTotal = 0; //total bank funds
$accounts = 0; //number of accounts
$PrivateTotal = 0; //total private funds
$accounts_p = 0; //number of private accounts
$CompanyTotal = 0; //total company funds
$accounts_c = 0; //number of company accounts
$GovernmentTotal = 0; //total government funds
$accounts_g = 0; //number of government accounts
$countryList = array(); //array of countries and their total funds
$accountsList = array(); // all accounts, indexed by country, type (Private/Company/Government), arbitrary number
  
while ($row = mysql_fetch_assoc ($BankListResult)) //go through the table
{
 $bankTotal += $row['funds'];
 $accounts++;
 if (!isSet($countryList[$row['country']])) //how much in each nation.
   $countryList[$row['country']] = $row['funds'];
 else
   $countryList[$row['country']] += $row['funds'];

 $accountsList[$row['country']][$row['type']][] = $row['funds'];
 
 switch ($row['type'])
   {case "Private":
       $PrivateTotal += $row['funds'];
       $accounts_p++;
       break;
   case "Company":
     $CompanyTotal += $row['funds'];
     $accounts_c++;
     break;   
   case "Government":
     $GovernmentTotal += $row['funds'];
     $accounts_g++;
     break;
   }   
 
}
 
$accountAverage = $bankTotal/$accounts;
$privateAverage = $PrivateTotal/$accounts_p;
$companyAverage = $CompanyTotal/$accounts_c;
$governmentAverage = $GovernmentTotal/$accounts_g;
$countryAverage = $bankTotal/count($countryList);



//Print stats
print ("<h3>".LANG_BANKSTATS."</h3>");
print (LANG_BANKSTATS_1.": $bankTotal<br />");
print ("<h3>".LANG_BANKSTATS_2."</h3>");
print (LANG_BANKSTATS_3.": $accounts.<br />");
print (LANG_BANKSTATS_4.": $accountAverage.<br />");
print (LANG_BANKSTATS_5.": $privateAverage.<br />");
print (LANG_BANKSTATS_6.": $companyAverage.<br />");
print (LANG_BANKSTATS_7.": $governmentAverage.<br />");

print ("<h3>".LANG_BANKSTATS_8."</h3>");
print (LANG_BANKSTATS_9.": ".count($countryList).".<br />");
print (LANG_BANKSTATS_10.": $countryAverage.<br />");

print ("<h3>".LANG_BANKSTATS_11."</h3>");
print ("<table border='1'>\n<tr>\n<th>".LANG_BANKSTATS_12."</th>\n<th>".LANG_BANKSTATS_13."</th>\n<th>".LANG_BANKSTATS_14."</th>\n<th>".LANG_BANKSTATS_15."</th>\n<th>".LANG_BANKSTATS_16."</th>\n<th>".LANG_BANKSTATS_17."</th>\n<th>".LANG_BANKSTATS_18."</th>\n<th>".LANG_BANKSTATS_19."</th></tr>\n");

foreach ($countryList as $countryKey => $country)
{ 
 $government = isset($accountsList[$countryKey]['Government']) ? array_sum ($accountsList[$countryKey]['Government']) : 0;
 $company = isset($accountsList[$countryKey]['Company']) ? array_sum ($accountsList[$countryKey]['Company']) : 0;
 $private = isset($accountsList[$countryKey]['Private']) ? array_sum ($accountsList[$countryKey]['Private']) : 0;

 $averagePrivate = isset($accountsList[$countryKey]['Private']) ? ($private / count ($accountsList[$countryKey]['Private'])) : "N/A";
 $averageCompany = isset($accountsList[$countryKey]['Company']) ? ($company / count ($accountsList[$countryKey]['Company'])) : "N/A";
 $averageGovernment = isset($accountsList[$countryKey]['Government']) ? ($government / count ($accountsList[$countryKey]['Government'])) : "N/A";

 print ("<tr><td>" . $countryKey . "</td> <td>" . $countryList[$countryKey] . "</td> <td>" . $private . "</td> <td>" . $company . "</td> <td>" . $government . "</td> <td>" . $averagePrivate . "</td> <td>" . $averageCompany . "</td> <td>" . $averageGovernment . "</td></tr>");
 next($countryList);
}
print ("</table>");

echo "<br> <a href='gdp.php'>".LANG_BANKSTATS_20."</a> <br>";
 
print_htmlfoot();
?>
