<?php


//Bank Change Log//
/*
0.1.0 original basic bank
0.2.0 included logging, and logging viewers
0.2.1 improved log viewers and minor fixes to userlists.
0.3.0 included ability to register accounts to countries (company sub accounts 
			not implemented though pondered)
0.3.1 country specific options and other nit picking
0.3.5 made subdivision menu's based on currently set nation.
0.3.6 fixed bugs
0.4.0 started work on putting countries in the DB and not hardcoding them, 
			pre-emptively added Natopia. renamed tables to less Shireroth based names. 
			Renamed bank to Small Commonwealth, made defaultsummary.php page.
			Implemented custom CSS styles for users
0.4.1 Created a stats page for the bank.
0.4.2 Changed how the admin account works. Now can be set here. Moving toward 
			multiple admin accounts
0.4.3 Initiated account Status, Shireroth Financial Privacy Act incorperated. 
			Delete account now logs.
0.4.4 Changestatus page created works for DB_ADMIN_NAME and 'Admin' accounts.
0.4.5 All current pages should now recognize the 'Admin' status as being equal 
			to DB_ADMIN_NAME where applicable.
0.4.6 Changed account creation to prevent names with anything not alphnumeric 
			in it. This had caused issues with apostrophes
0.4.7 Created CountrySummary to generate a summary page for the users nation 
			that would display info based on permisions.
0.4.8 Fixed an error in deleteAccount that may have been caused when admins hit 
			the back button.
0.4.9 Made simple.css default and made it work for summary pages.
0.4.10 Added Stormark and preparing for National Accounts to do things by 
			 making changes to menu to display what pages are available based on 
			 account type. Mostly UI stuff. Preparing to replace user_select with
			 something that takes a list and doesn't generate it. This means we can 
			 now make custome userlists based on what the user has permissions to 
			 access and modify.
0.5.0 National and Regional accounts now have the power to forcibly move money 
			from accounts in their subdivisions. National and Regional accounts have 
			the following powers: change passwords, change subdivision, view accounts.
			Change Subdivision is a National only feature(Admins do this with Change 
			Country and Subdivision).
			View Transaction log is an Admin only feature. 
			Also fixed formatting on change log(woop de doo)
0.5.1 Adjustments to simple.css. 
0.6.0 Created countrylib.php for the country table.
			Created addcountry.php, viewcountry.php and deletecountry.php
			Still pondering how to add subdivision to the server. Do they get their
			own table or do I add them as a list in a single element to the Country
			table? Their own table is winner for simplicity despite being yet another
			table to deal with and make a lib for. Some minor changes to the menu. 
0.6.1 created the table for subivisions and added it to the config file. 
0.7.0 Discovered 'SELECT DISTINCT'. Removing DB_TABLE_COUNTRY. updating config.
			Removing countrylib.php. Creating sublib.php to handle all this. 
			Rewriting addcountry.php, use addsubdivision.php to add new subdivisions.
			Removed deletecountry.php (you will have to remove all its subdivisions.
			Now use deletesubdivision.php to remove subdivision.
			Created viewsubdivisions.php.
0.7.1 Fixed db to no longer need the Ari made function subdivision_name()
			in util.php. countrysumary.php updated to know this.  
0.7.2 Adjusted the database table for accounts so that companiy accounts can
			keep track of who owns them. updating accordingly. Changes will allow for
			the owning of multiple companies by one account. Updating getter and 
			setter in dblib.php. Also a formatting fix on the menu. 
0.7.3	Created htmllib.php to put html creation functions. Much is currently in 
			util.php and needs to be moved. Changed how the user control panel is made.
			Made a function to generate buttons to take the user to their pages. 
			Trying to find a better way to do it. Javascript is an option. nope.
			CSS? Bah. More resaerch needed. link_button() will have to be for now. 
			Got button_link_java() to work! Menu changed to use it for user controls. 
0.7.4 Fixes here and there in HTML. Added to the createaccount.php file to allow
			for creating company accounts. Though this won't do much at this time.
			Change iscompany in the db to 'type'. Gotta make the needed mods. updated
			config file. No pages make use of this new functionality. Should users be
			able to change their account type at will, or leave it to admins?
			Need to fix login.php to use the users style when they login. Refresh the
			page somehow? Fixed it. Now to do it for logout.php. Done.
0.7.5 changed how transferfunds() worked by integrating some of the error checking
			into it. This will make it easier to incorperate functionality into forums.
			also takes care of logging now. Should I make account deletion easier for 
			admins by removing the password requirement? Screw the password! It is gone. 
0.8.0 Format fixes in dblib and creation of tax page(not finished).
			Added <tiny> to the styles. Created repair.php for table fixing.  
			Updated bank menu. updated viewaccounts.php for type. 
			More fixes to simple.css. added typeselect() to htmllib.php
			Created changetype.php. Added apply_tax() to bankops.php 
			Finnished tax.php though appply_tax() is not complete.
			'Government' Type accounts are now active.
0.8.1 rebuilt transfer.php and admintransfer.php, and moved the last of the error
			handling into transfer(). Finnshed apply_tax(). Created bank_error()
			Added <error> to the css. Started making some pages use bank_error().
0.8.2 Fixed all Change*.php pages so that they don't mess with the session 
			unless you made changes to your accounts. Also a glich in changetype.php
			was fixed, it would mess up the users status.
0.8.3 Moved more funciton from util.php into htmllib.php and made pages aware
			of this change. Some libs are more alphabetized then previously. 
			Fixxed some XHTML issues. Created Terminal.css. Created and fixed a strange
			problem with Sessions by having one to many newlines at the end of sublib.
			Fixed subdivision error in the table related to 'Elywnn'. Repair.php dealt
			with it.
0.8.4 Added a note to deletesubdivision. Should I make a FAQ? There's now a blank one. 
0.8.5 stats page and gdp page, written by Andreas the Wise added. But gdp needs work. 
			tables requested for it are not implemented. Everything fixed. Config updated.
0.8.6 More fixes supplied by Ari for bankstats and gdp. .htmlaccess page provided by Ari. 
			Added county_count() to sublib.php for later use. 
			GDP tables removed from config/server. 
0.8.7 Fixed the userlist functions to display the users country, as requested.
			Adjusted how the admin trans logs are displayed. dates don't wrap. 
			What is $access that is used on every page that connects to the server? undefined.
			It is now gone. And had to update ALL pages to do it. Some pages still had shirebank
			references on them. 
0.8.8 Adding Inactive account status. Updated user_select(), user_select_given(),
			status_select(). User_select and user_select_given will still show to admins. 
			Changed Tranfer_funds() to stop transfers to Inactive accounts. Cannot be 
			instigator and Inactive. 
			Added note to deleteaccount.php Inactive accounts will still be taxed. 
			Set_status() now changes your type to Private if you were not private or 
			company before being set Inactive. 
			Countrysummary.php ignored Inactive acocunts now. viewaccounts.php updated. 
			login.php keeps Inactive accounts from being considered 'Authorized'. 
			Found strange error in how user_select_given() displays on admintransfer.php
			the country won't show next to the name. Mistake is in the function. Weird.	
			Fixed problem by giving the function '$server'. Why did I not get errors, I don't know. 
			Fixing pages that use the function.
0.8.9 bankstats.php now ignores Inactive accounts.  
0.8.10 Created bank_server_connect() in util.php to handle connecting. 
			 Now if we need to modify that code it's in one spot. 
			 Updating all files!
			 Added 'mods' directory for addons to the bank. 
0.8.11 Added a 'Stock' mod. See that mods change log for details. It will not be
			 outlined in this file. Changes how some HTML functions work to help mods
			 work with them. print_htmlhead() changed. bank_menu() also updated. 
			 All pages updated for changes in function use. Linked stock mod. 
0.8.12 Added 'Type' to session info. Made needed updates to function and login. 
0.8.13 Removed all admin functions from the menu and gave them their own page.
0.8.14 Ever so minor fix in htmllib.php that caused issues with a mod.  
			 Moved all select() functions and bank_error() into htmllib.php from util.php.
			 Created install.sql to become install.php to create tables for the bank. 
0.9.0 created clean_input() in util for use in the bank. More security.
			 Implemented clean_input() on username and password given for new accounts.
			 Updaed: login.php, changepw.php, adminchangepw.php, addcountry.php, addsubdivision.php,
			 				 tax.php, transfer.php. 
			 transfer_funds() now cleans up ALL inputs given to it. Made fix to apply_tax().
			 log_transfer() now cleans up ALL inputs. 
			 All functions in sublib.php, loglib.php and dblib.php clean inputs. 
			 check_password() cleans. 
			 Probably got a little caried away implementing clean_input().
0.9.1 Created business.php for businesses that use the bank. 
0.9.2 Minor fix to adminchangepw.php. Seeing variables that aren't around yet. 
0.9.3 Fix to changesubdivision.php. missing function parameter. 
0.9.4 Introduced script/filterlist.js, and changed require/htmllib.php
			and transfer.php to implement Javascript filtering for the account 
			selection interface for transfers. (thanks to Ari Rahikkala)
0.9.5 Moved filter into select functions instead of the individual pages. 
			minor unrelated change to bussiness.php. 
0.9.6 Discovered 'COMMIT'. Making use of it in bankops.php.
0.9.7 Discovered problem when using COMMIT inside of Transfer_funds() if someone
			uses that function as part of larger transaction. 
			Added 'transaction' = false to the session info for seeing if we have a 
			transaction in progress. 
			Creating begin_transaction(), commit_transaction(), and abort_transaction()
			in util.php. 
			Modifying transfer_funds() to make use of them. transfer_funds can be 
			self-contained, or part of another transaction without causeing issues
			as long as begin_transaction() is used. You must also complete with commit or abort.
			failure to do so will lock up the bank for the user. 
0.9.8 Updates to CSS styles so they can handle CSS menus. (basic)
			Undid changes to transfer_funds() in 0.9.7 (they sucked). 
			Simplified begin, commit and abort_transaction(). 
			Logic fix in transfer_funds().
			Created css_menu(). Set bank_menu() to deprecated. 
			Removed bank_menu() from pages. admin.php deprecated. 
			Making use of transaction functions on transfer pages. 
			account.php is deprecated. Fixed up styles a bit.
0.9.9 Made viewsubdivisions list by country. 
			Fixed issue that allowed improper names to be added to subdivision table.
			We saw this issue with usernames. Funny character made for issues.
			Pondering making an adminlog page eventually to view error logs. 
0.9.10 added links to go to top of the page in transaction log viewing pages.
			 also added it to viewaccounts.php 
0.9.11 added date.php to require folder with function DateMath(), retrieved online. 
			 used in a mod, but felt it was useful in general. 
0.9.12 Made it so deleting a subdivision will change all users to a specified one. 
			 Had to make a fix to htmllib to add subdivision_named_select().
			 more fixes to sublib and htmllib to make this happen... messier then I like. 
			 Now if it's the last subdivision you have to say so and set a new country too. 
0.9.13 More styles. updates. Should make it easier to add styles...
0.9.14 Fixed silly mistake that's been lurking in adminchangepw.php for ages. 
0.9.15 Disabled the link to the deleteaccount page. It still exists, but the
			 deleting of accounts is depreciated and should be discouraged.
			 Added userchangesubdivision.php to allow people to change their own
			 subdivision, but not that country. Updated menu. 
			 Should we add a field to track last login? 
			 We need a better way to add in mods that doesn't require changing code. 
			 Perhaps a table with mod information. Then we can just generate a list of
			 mods from the table. 
			 Added note to account.php to remind me it's not needed anymore.
0.9.16 Updates to styles to include some addition modifications to links. 
			 added SCX to bussiness list. Added bussinesses to menu.  
0.9.17 2010/6/5 Fix to the css_menu to make them work properly in mods. 
			 mods going to mods broke. 
0.9.18 2010/6/16 Created disableaccount.php so people can disable their 
			 own accounts. Updated menus.
0.9.19 2010/9/20 Added getip() to utils.php. 
			 SESSION now keeps this information. Added
			 'userip' to DB_TABLE_LOG. login.phph now grabs the user ip. 
			 log_transaction() updated to log ips. created get_userip() in loglib.php
			 These changes were made for increased security resulting from a set of
			 questionable transactions. IP's were requested, but not logged at the
			 time. Now they will be logged so we can help look into such things. 
			 Updated admintranlog.php. Also updated install.sql to keep up with that. 
			 IP's from old logs will just come up as UNKNOWN.
0.9.20 2010/10/29 added loan mod to main menu, despite it not being functional. 
			 perhaps it'll intice me to get it working. Made the change log into a global
			 variable so we can use it in a to be create later about/version page.   
0.10.1 2010/11/1 added email field to accounts, added '$email' to add_account().
			 Fixed some formating issues with the last change. Not sure that tweek was a good idea.
			 More trouble perhaps then it was worth. Removed that ability due to annoyance.
			 It wasn't worth the upkeep. 
			 emails given to add_account() aren't cleaned or checked at this time. 
			 updated createaccount.php so that it knows about the change. 
0.10.2 2010/11/4 added function to util.php to check email format.
			 Created changeemail.php so a user can change it themselves. Must have pw. 
			 Updated menu. Minor tweak to changepw.php removing link to account.php.
			 viewaccount.php now shows emails. 
			 Delete subdivision can be used to change one subdivision into another country. 
			 Just say it's the last subdivision. Should make this clear when I have time.  
0.10.3 2010/11/14 Made it clear that you can use the page in this way. 
			 Removed account.php as it is deprecated. It's job is taken up by the css menu now. 
			 Why is bank_menu() still in util.php? Removed it. 
			 I should research a better function to get IP's. 
0.10.4 2010/11/18 Changes to how we log IPs. We no longer use the stored IP, but in fact grab it a new each time. 
			 This is to be a Possible solution to a technical issue expereinced when using an
			 external program to interact with the bank.
0.10.5 2010/11/19 Changes getIP() so it might now return 127.0.0.1 instead of UNKNOWN 
			 when the logger is called by a cron job or other local means.
0.10.6 2010/11/20 added lastlog as a timestamp field that will be updated whenever the 
			 user logs in. added to table, added set/get function. update login.php.
			 updated add_account(). updated begin_session(). updated viewaccount.php
			 Previous update's fixes had no effect. Curses. 
0.10.7 2010/11/21 changed DateMath() to date_math() for consistency.  
			 added index.php to the menu as Home. Added lastlog info to index.php
0.10.8 2010/11/22 undid previous fix to log function concerning getip(). 
			 We'll just have to lie to the system. 
			 Fixed terminal.css so it works properly with the css menu.
			 Made changestyle.css update prior to page gen. Bout time. 
			 added cron directory for later implementation. We just don't have anything
			 that needs to be run every day or such.
0.10.9 2010/11/27 split admin part of the menu into account and bank categories.  
1.0.0 2010/12/1 added/stole function to create passwords. 
			Now you give an email when you register an account. 
			resetpw.php created so users can retrieve pw. 
			index.php made so it yells if no email is set. 
			login.php will now redirect to index.php once login is complete. 
			Decided to consider the bank version 1.0.0! It has enough functions, 
			mods, and ability to be a functional version. It has all basic functionality. 
1.0.1 2010/12/2 Fixing errors on changestatus.php 
			Found the same erros in other change*.php pages. 
			Happens if user isn't actually selected. 
			Fixing: type, subdivision, country, adminchangepw.
			Made viewaccounts.php easier to read stealing from countrysummary.php
			Making same changes to admintranlog.php. Works but feels cludgy.
			Random wording fix on countrysummary.php
1.0.2 2011/1/8 Laying ground work for internationalization/translation to dutch. 
			created require/lang/ en.php and nl.php. 
			made changes to index and addcountry.php so the use en.php
			made some changes in htmllib.php but not the menu yet.
1.0.3 2011/1/10 Moving on to addsubdivision.php and some fixes to index for consistancy.
			marked admin.php as depreciated. 
			language work on: adminchangepw, admintranlog, 
1.0.4 2011/4/27 [Andreas] Made new versions of changetype.php and changestatus.php (changetypenational.php and changestatusnational.php).
			These new versions are accessible by national status accounts, so they can use them.
			They cannot change the status of admin accounts.
			Also fixed up the menu to reflect this change.
			And allowed accounts to register as government.	
1.0.5 2011/11/15 [Andreas] Fixed an error with addsubdivision.php.
1.0.6 2011/12/20 [Andreas] Uploaded old copies of 'adminchangepw.php' and 'bankstats.php' because they weren't working.
1.0.7 2012/1/5 [Andreas] Added Company Pages to bank menu and rearranged the other things to suit this in htmllib.php.
1.0.8 2012/7/16 Minor fix to bankstats.php
1.0.9 2012/7/16 viewaccounts.php will now sort names and has links to the first letter of the names. 
1.0.10 2012/8/13 created install/install.php which will create all the core tables needed to run the bank
 Also creates the main admin account as defined in this file with password and country, which it adds too. 
 Modified config.php as needed. discovered that mysql_*() is deprecated in PHP need to migrate to mysqli_*().
 This to be done in the next release, 1.2. 
1.0.11 2012/8/21 Changed tranlog.php so that it auto sort with newest first and the option to dictate how it sorts. 
1.0.12 2012/11/10 Modified install script/db so the bank can handle up to 999,999,999,999,999 funds. Decimal(17,2) is anyone cares. Also had to fix create, transfer, and admintransfer to like large numbers. Having issue with adding over millions, modified add_funds() to check the change. Changed how I ronud in clean_funds(). using round(x,2) now. 
1.1.0 2012/11/11 Release Version Last release was 1.0.7 to live servers. All major releases will change second number upwards. Minor fixes and bug fixes just change the internal third number. Some of these may appear on live server. 
1.1.1 2013/12/23 [Andreas] Gave National status accounts the ability to add and remove subdivisions within their nation.
				 Fixed up a typo in user_select and user_select_given (had an extra : ).
				 Gave National status accounts the ability to change a user country for users within their nation.
				 Found that set_status was automatically changing people's status to Private if not already Private or Company.  Stopped that (it was setting Government accounts to Private).
				 Created a 'Delete Nation' page that: removes funds from all deleted accounts, sets account status to Inactive, and subdivision to 'Deleted' in country 'SCUE',
				   and then deletes the subdivisions.
				 Fixed an error in deletesubdivision.php which would have moved all user accounts with that subdivision, even if it occurred in multiple countries.
1.1.2 2013/1/4   [Andreas] Was getting random errors with add_funds, so modified it so the error trapping cleans both sets of funds before checking.  Seems to work now.

*/

require_once 'lang/en.php';

define('BANK_VERSION','1.1.2');

//Set timezone
date_default_timezone_set('UTC');

//server constants
define('DB_SERVER','');
define('DB_USERNAME','');
define('DB_PASSWORD','');
define('DB_DATABASE','');


//table names
define('DB_TABLE_ACCOUNT','bankaccounts'); //where are accounts stored
define('DB_TABLE_LOG','banklog'); //where are logs stored
define('DB_TABLE_SUBDIVISION', 'banksubdivisions'); //list of subdivisions

//bank constants
define('DB_ADMIN_NAME','admin'); //name of the primary admin account for the bank. This account has certain protections.
define('DB_ADMIN_DEFAULT_PW', 'default'); //starting default password. To be changed by user after account creation
define('DB_ADMIN_DEFAULT_NATION', 'Defaultistan'); //starting country to work with. 
define('DB_ADMIN_DEFAULT_SUBDIVISION', 'Proper'); //default first subdivision.
define('DB_ADMIN_DEFAULT_FUNDS', '100000'); //default starting funds
define('DB_ADMIN_DEFAULT_EMAIL', 'default@default.com'); //default email

//Customization constants
define('HTML_STYLE', 'simple.css'); //the name of the  default style sheet for the bank.
define('BANK_BANNER', 'SC_logo_proposal_1.png'); //Banner for pages. 


////DataBase layouts////
/*

DB_TABLE_ACCOUNT must have the following fields (9)
username (text)
userpass (text)
funds (decimal[17,2])
subdivision (text)
country (text)
type (text)
owner (text) //for companies, name of owner.
style (text) //what css style do they use
status (text) //See status table below.
email (text) //a email to reach the user at.
lastlog (datetime) //last login datetime 

DB_TABLE_LOG must have the following fields (7)
id (uint) (auto_increment)
instigator (text)
source (text)
destination (text)
funds [Decimal](17,2)
reason (text)
date (date)
userip (char 17)

DB_TABLE_SUBDIVISION(2)
subdivision (text)
country (text)

///Account Status list///
User -> standard user (default)
Admin -> full access to ALL accounts, may change statuses
National -> Full access to all accounts of the same country
Regional -> Full access to all acounts of the same nation AND subdivision
Inactive -> User may not login. Other issues too.

///Account Type list///
Private -> a private individuals account.
Company -> a company account.
Government -> a government account
*/
?>
