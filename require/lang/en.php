<?php
//English
//Constants for language usage. 

//General
define('BANK_NAME','Small Commonwealth Bank'); //Display name of the bank.
define('CURRENCY_NAME','Funds'); //Currency name

//recuring
define('LANG_MUSTBEADMIN', 'You must <a href="login.php">login</a> as an admin, to use this page.');
define('LANG_MUSTBENATIONAL', 'You must <a href="login.php">login</a> as a national account to use this page.');

////HTMLLIB.php///
define('LANG_BANK_VERSION', 'Bank Version');
define('LANG_FILTER', 'Filter accounts by name or nation');
//CSS Menu


//addcountry.php
define('LANG_ADDCOUNTRY', 'Add Country');
define('LANG_ADDCOUNTRY_1', 'Subdivision name may only contain letters, numbers and spaces. No funny characters.');
define('LANG_ADDCOUNTRY_2', 'Country name may only contain letters, numbers and spaces. No funny characters.');
define('LANG_ADDCOUNTRY_3', ' was added with success to the database');
define('LANG_ADDCOUNTRY_4', 'Something happened! Likely we could not add the country/subdivision.');
define('LANG_ADDCOUNTRY_5', 'Country to be Added');
define('LANG_ADDCOUNTRY_6', 'Initial Subdivision');
define('LANG_ADDCOUNTRY_ADD', 'Add');

//addsubdivision.php
define('LANG_ADDSUBDIVISION', 'Add Subdivision');
define('LANG_ADDSUBDIVISION_1', 'There was an error, and no subdivision was submitted. This may be caused if you hit the back button. Please try again.');
define('LANG_ADDSUBDIVISION_2', 'Subdivision name may only contain letters, numbers and spaces. No funny characters.');
define('LANG_ADDSUBDIVISION_3', 'Country name may only contain letters, numbers and spaces. No funny characters.');
define('LANG_ADDSUBDIVISION_4a', 'Subdivision and Country of ');
define('LANG_ADDSUBDIVISION_4b', ' has been added.');
define('LANG_ADDSUBDIVISION_5', 'Something unexpected happened! Consult the admin.');
define('LANG_ADDSUBDIVISION_NEXT', 'Next');
define('LANG_ADDSUBDIVISION_6', 'Subdivision Name');

//adminchangepw.php
define('LANG_ADMINCHANGEPW', 'Change User Password');
define('LANG_ADMINCHANGEPW_1', 'You must select a user to change the password of. ');
define('LANG_ADMINCHANGEPW_2', 'New password has been set.');
define('LANG_ADMINCHANGEPW_3', 'The new password did not match, try again.');
define('LANG_ADMINCHANGEPW_4', 'Select User Account');
define('LANG_ADMINCHANGEPW_5', 'New Password');
define('LANG_ADMINCHANGEPW_6', 'Confirm Password');
define('LANG_ADMINCHANGEPW_7', 'Change Password');

//admintranlog.php

define('LANG_ADMINTRANLOG', 'System Transaction Log');
define('LANG_ADMINTRANLOG_1', 'Bottom of Page');
define('LANG_ADMINTRANLOG_2', 'Instigator');
define('LANG_ADMINTRANLOG_3', 'Source');
define('LANG_ADMINTRANLOG_4', 'Destination');
define('LANG_ADMINTRANLOG_5', 'Funds');
define('LANG_ADMINTRANLOG_6', 'Reason');
define('LANG_ADMINTRANLOG_7', 'Date');
define('LANG_ADMINTRANLOG_8', 'Top of page.');

//admintransfer.php

define('LANG_ADMINTRANSFER', 'Admin Funds Transfer');
define('LANG_ADMINTRANSFER_1', 'You must confirm the transfer if you desire to have it proceed.');
define('LANG_ADMINTRANSFER_2', 'It is required that you <a href="login.php">login</a> as an admin, in order to use this page.');
define('LANG_ADMINTRANSFER_3', 'Reciever\'s Account');
define('LANG_ADMINTRANSFER_4', 'Funds to be transfered');
define('LANG_ADMINTRANSFER_5', 'Reason for transfer.');
define('LANG_ADMINTRANSFER_6', 'Please Confirm the Transfer');
define('LANG_ADMINTRANSFER_7', 'I Confirm this Transfer');
define('LANG_ADMINTRANSFER_8', 'I do NOT Confirm this Transfer.');
define('LANG_ADMINTRANSFER_9', 'Transfer');

//bankstats.php
define('LANG_BANKSTATS', 'Bank Statistics');
define('LANG_BANKSTATS_1', 'Total Bank Funds');
define('LANG_BANKSTATS_2', 'Account Stats'); 
define('LANG_BANKSTATS_3', 'Total Number of Accounts');
define('LANG_BANKSTATS_4', 'Average Funds per Account');
define('LANG_BANKSTATS_5', 'Average Funds per Private Account');
define('LANG_BANKSTATS_6', 'Average Funds per Company Account');
define('LANG_BANKSTATS_7', 'Average Funds per Government Account');
define('LANG_BANKSTATS_8', 'Country Stats');
define('LANG_BANKSTATS_9', 'Total Number of Countries');
define('LANG_BANKSTATS_10', 'Average Funds per Country');
define('LANG_BANKSTATS_11', 'Totals by Country');
define('LANG_BANKSTATS_12', 'Country');
define('LANG_BANKSTATS_13', 'Total');
define('LANG_BANKSTATS_14', 'Private');
define('LANG_BANKSTATS_15', 'Company');
define('LANG_BANKSTATS_16', 'Government');
define('LANG_BANKSTATS_17', 'Private Average');
define('LANG_BANKSTATS_18', 'Company Average');
define('LANG_BANKSTATS_19', 'Government Average');
define('LANG_BANKSTATS_20', 'Calculate GDP');
 
//index.php
define('LANG_INDEX_USERNAME', 'Username');
define('LANG_INDEX_FUNDS', 'Funds');
define('LANG_INDEX_RESIDENCE', 'Residence');
define('LANG_INDEX_STATUS', 'Status');
define('LANG_INDEX_CSSSTYLE', 'CSS Style');
define('LANG_INDEX_LASTLOGIN', 'Last Login');
define('LANG_INDEX_1', "No email has been set for this account!<br /> Please set one <a href='changeemail.php'>Here</a>. This is required for password retrieval.<br />");
define('LANG_INDEX_EMAIL', 'Email');
define('LANG_INDEX_2', 'Please Login');


?>