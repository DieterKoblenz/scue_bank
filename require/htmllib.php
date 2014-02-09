<?php
require_once('config.php');
require_once('dblib.php');
require_once('sublib.php');

function link_button($text, $url)
{
 print ("<FORM METHOD='LINK' ACTION='".$url."'>");
 print ("<INPUT TYPE='submit' VALUE='".$text."'>");
 print ("</FORM>");
}

function link_button_java($text, $url)
{
 print ("<INPUT TYPE='button' VALUE='".$text."' onClick=\"parent.location='".$url."'\">");
}

function print_htmlfoot()
//prints the footer for all pages. 
{
print ("<hr />");
print (LANG_BANK_VERSION.": ".BANK_VERSION."<br />");
print ("</body></html>");
}

function print_htmlhead($page_title, $mod) 
//print the start of the HTML document including <html><head> and <body> as well
//as style sheet information
//$mod is used to tell the function from where you are using it. 
//the bank can keep it as afalse. mods must use true.
{
 //for letting mods use this function
 if ($mod)
 {
	$reference = '../../';
 }
 else
 	$reference = '';

 print ('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
 print ('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');
 print ('<head>');
 print ('<meta http-equiv="content-type" content="text/html; charset=utf-8"/>');
 print ("<link rel='stylesheet' type='text/css' href='");
  
 if (!isSet($_SESSION['style'])) //if no style var is set we are not logged in or some weirdness
 {
 	
 	print ($reference."styles/".HTML_STYLE); //use default style
 }
 else //we can proceed now.
 {
 	if ($_SESSION['style'] == '') //no style is set
	{
	 print ($reference."styles/".HTML_STYLE); //use default
	}
	else
	 print ($reference."styles/".$_SESSION['style']); //use what they got. Assume it's legit
 }
 print ("' />");
 print ("<title>".$page_title."</title>");
 print ('<script type="text/javascript" src="script/filterlist.js"></script>');
 print ('</head>');
 print ('<body>');
 print ("<center><img src='".$reference."images/".BANK_BANNER."' alt='Bank Banner'></center>");
 print ("<center><h3>".$page_title."</h3></center>");
 print ("<hr />");
 css_menu($mod); 
}

function style_select()
//creats a drop down menus for a form with name 'style'
//this is all available styles.
{
 print ('<select name="style">
       <option>novatainia.css</option>
 			 <option>shireroth.css</option>
			 <option selected>simple.css</option>
			 <option>terminal.css</option>
			 <option>VBNC.css</option>
			 </select>');
}

function type_select()
//creates a drop down menus for a form with name "type"
//This is all types
{
print ('<select name="type">
			<option selected>Private</option>
			<option>Company</option>
			<option>Government</option>
			</select>');
}

function user_select($name, $server)
//create a pull down menu of users with the name="$name" (Why did I do it this way???)
//use user_select_given if possible. (why?)
{
 $list = array();
 $list = userlist($server);
 $i = count($list);
 $j=0;

 print("<select id='$name' name='$name' size='7'>");
 while ($j < $i)
 {
 	if (get_status($list[$j],$server) != 'Inactive' OR $_SESSION['status'] == 'Admin') //only screen for none admins. 
		print ("<option value='".$list[$j]."'>".$list[$j]." <i>(".get_country($list[$j],$server).")</i></option>");
	$j++;
 }
 print('</select>');

 print ("<script type=\"text/javascript\"> var filter_$name = new filterlist (document.getElementById (\"$name\")); </script>");

 print ("<br />".LANG_FILTER." ");
 filter_select ($name);
}

function user_select_given($name, $list, $server)
//create a pull down menu of users with the name="$name" from the given list of users (To eventually replace user_select()
{
 $i = count($list);
 $j=0;

 print("<select id='$name' name='".$name."' size='7'>");
 while ($j < $i)
 {
 	if (get_status($list[$j],$server) != 'Inactive' OR $_SESSION['status'] == 'Admin') //only screen for none admins. 
		print ("<option value='".$list[$j]."'>".$list[$j]." <i>(".get_country($list[$j],$server).")</i></option>");
	$j++;
 }
 print('</select>');

 print ("<script type=\"text/javascript\"> var filter_$name = new filterlist (document.getElementById (\"$name\")); </script>");

 print ("<br />".LANG_FILTER." ");
 filter_select ($name);
}

function subdivision_select($country, $server)
//creates a drop down menu for a form with name 'subdivision'
//displays the list based on what $country the person is in.
{ 
 $list = array();
 $list = subdivision_list_given($country, $server);
 print ('<select name="subdivision">');
 foreach ($list as &$subdivision)
 {
 	print ("<option selected>".$subdivision."</option>");
 }
 print ('</select>'); //What subdivision are you in?
}

function subdivision_named_select($name, $country, $server)
//creates a drop down menu for a form with name 'subdivision'
//displays the list based on what $country the person is in.
//menu will have name $name.
{ 
 $list = array();
 $list = subdivision_list_given($country, $server);
 print ("<select name='".$name."'>");
 foreach ($list as &$subdivision)
 {
 	print ("<option selected>".$subdivision."</option>");
 }
 print ('</select>'); //What subdivision are you in?
}

function country_select($server)
//creates a drop down menu for a form with name 'country'
{
 $list = array();
 $list = country_list($server);
 print ('<select name="country">');
 foreach ($list as &$country)
 {
 	print ("<option selected>".$country."</option>");
 }
 print ('</select>'); //What country are you in?
}

function country_select_given($orders, $server)
//creates a drop down menu for a form with name 'country'
//This will get a country based on the orders.
{
 $list = array();
 $list = country_list_given($orders, $server);
 print ('<select name="country">');
 foreach ($list as &$country)
 {
 	print ("<option selected>".$country."</option>");
 }
 print ('</select>'); //What country are you in?
}

function status_select()
//creates a drop down menus for a form with name "status"
//This is all statuses
{
print ('<select name="status">
			<option selected>User</option>
			<option>National</option>
			<option>Regional</option>
			<option>Admin</option>
			<option>Inactive</option>
			</select>');
}

function status_select_national()
//creates a drop down menus for a form with name "status"
//This excludes Admin status
{
print ('<select name="status">
			<option selected>User</option>
			<option>National</option>
			<option>Regional</option>
			<option>Inactive</option>
			</select>');
}

function filter_select ($name)
{
  print ("<input type='text' name='filtertext_$name' onKeyUp='filter_$name.set(this.form.filtertext_$name.value)'>");
}

function bank_error($message)
//for printing bank errors. 
{
 print ('<error>');
 print ($message."<br />");
 print ('</error>');
}

function css_menu($mod)
//CSS menu for the bank. 
{
 if (!isSet($_SESSION['authorized'])) //so we don't have non-existant variables
	 $_SESSION['authorized'] = false;

 //for letting mods use this function
 if ($mod)
 {
  $reference = '../../';
 }
 else
  $reference = ''; 
	 
 if ($_SESSION['authorized']) //are they logged in?
 {
	//start menu HTML
  print ("
  <div id='menu'>
   <ul>
    <li><h2>User Control Panel</h2>
     <ul>
		  <li><a href='".$reference."index.php'>Home</a></li>
      <li><a href='".$reference."transfer.php'>Transfer Funds</a></li>
      <li><sub_menu>Account Options</sub_menu>
			 <ul>
			 	<li><a href='".$reference."changepw.php'>Change Password</a></li>
				<li><a href='".$reference."changeemail.php'>Change Email</a></li>
				<li><a href='".$reference."changestyle.php'>Change Style</a></li>
				<li><a href='".$reference."userchangesubdivision.php'>Change Subdivision</a></li>
				<li><a href='".$reference."disableaccount.php'>Disble Account</a></li>
			 </ul>
			</li>
      <li><a href='".$reference."tranlog.php'>Transaction Log</a></li>
		  <li><a href='".$reference."logout.php'>Log Out</a></li>
     </ul>
    </li>
   </ul>
   <ul>
    <li><h2>Other</h2>
	   <ul>
	    <li><a href='".$reference."mods/stock/index.php'>Stock Market</a></li>
		<li><a href='".$reference."mods/loan/index.php'>Loan Market</a></li>
		<li><a href='".$reference."mods/VBNC/Welcome.php'>SCX</a></li>
        <li><a href='".$reference."mods/casino/index.php'>Commonwealth Casino</a></li>");
//Now the company pages mod (have to do menu through the bank)
   echo "<li><sub_menu>Company Pages</sub_menu>
         <ul>";
  require_once($reference.'mods/company_page/require/CP_config.php');
  $server = bank_server_connect();
  $orders_cp = "SELECT * FROM ".TABLE_COMPANY_PAGES." ORDER BY `company` ASC";
  $results_cp = mysql_query($orders_cp, $server);
  while ($row_cp=mysql_fetch_assoc($results_cp))
    {
    echo "<li><a href='".$reference."mods/company_page/visit.php?id=".$row_cp['id']."'>".$row_cp['company']."</a></li>";
    }
  if ($_SESSION['type'] == "Company")
    {
	$orders_cp2 = sprintf("SELECT * FROM ".TABLE_COMPANY_PAGES." WHERE `company`='%s'",
     mysql_real_escape_string(clean_input($_SESSION['username']))); 
    $result_cp2 = mysql_query($orders_cp2, $server);
    $code_cp2 = mysql_fetch_assoc($result_cp2);
    if (!$code_cp2['id'])
      {$code_cp2['id'] = 0;}
	if ($code_cp2['id'] > 0)
	  {echo "<li><a href='".$reference."mods/company_page/Edit Page.php'>Edit Your Page</a></li>";}
	else
	  {echo "<li><a href='".$reference."mods/company_page/Add Page.php'>Add Your Company</a></li>";}
	}
  if ($_SESSION['username'] == COMPANY_PAGE_ADMIN)
	{echo "<li><a href='".$reference."mods/company_page/Admin.php'>Admin</a></li>";}
//End of Company Pages stuff.
  print("</ul>
      </li>
		  <li><a href='".$reference."countrysummary.php'>Country Summary</a></li>
			<li><a href='".$reference."bankstats.php'>System Stats</a></li>
	   </ul>
	  </li>
	 </ul>							
   ");
	
  if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional')) //are they an admin or national, or regional
  {
   print ("
	  <ul>
	   <li><h2>Admin</h2>
		  <ul>
			 <li><sub_menu>Accounts</sub_menu>
        <ul>
			  ");
 			  if ($_SESSION['status'] == 'National')	//National options
 	      {
		  	   print ("<li><a href='".$reference."changecountry_national.php'>Change User Country and Subdivision</a></li>");
	 		   print ("<li><a href='".$reference."changesubdivision.php'>Change User Subdivision</a></li>");
			   print ("<li><a href='".$reference."changetypenational.php'>Change User Type</a></li>");
			   print ("<li><a href='".$reference."changestatusnational.php'>Change User Status</a></li>");
	 		  }
			  if (($_SESSION['status'] == 'National') OR ($_SESSION['status'] == 'Regional'))
	 		  {
			   print ("<li><a href='".$reference."tax.php'>Apply Tax</a></li>");
			  }
			  if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))
 			  {
				 print ("<li><a href='".$reference."changestatus.php'>Change User Status</a></li>");
	 			 print ("<li><a href='".$reference."changetype.php'>Change User Type</a></li>");
	 			 print ("<li><a href='".$reference."changecountry.php'>Change User Country and Subdivision</a></li>");
 			  }
			  print ("<li><a href='".$reference."adminchangepw.php'>Change User Password</a></li>");
			  print ("<li><a href='".$reference."admintransfer.php'>Force Funds Transfer </a></li>");
			  print ("<li><a href='".$reference."viewaccounts.php'>View User Accounts</a></li>");
			  print ("
			  </ul>
	     </li>
			  ");	
			  if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin') OR ($_SESSION['status'] == 'National') )
 			  {			  
			print ("
			<li><sub_menu>Bank</sub_menu>
        <ul>
			");
 			  if ($_SESSION['status'] == 'National')	//National options
			  {
	 		     print ("<li><a href='".$reference."addsubdivision_national.php'>Add Subdivision</a></li>");
	 		     print ("<li><a href='".$reference."deletesubdivision_national.php'>Delete Subdivision</a></li>");
			  }
			  if (($_SESSION['username'] == DB_ADMIN_NAME) OR ($_SESSION['status'] == 'Admin'))
 			  {
	 		   print ("<li><a href='".$reference."repair.php'>Bank Repair Utility</a></li>");
				 print ("<li><a href='".$reference."createfunds.php'>Create Funds </a></li>");
	 			 print ("<li><a href='".$reference."addcountry.php'>Add Country</a></li>");
	 			 print ("<li><a href='".$reference."deletecountry.php'>Delete Country</a></li>");
	 			 print ("<li><a href='".$reference."addsubdivision.php'>Add Subdivision</a></li>");
	 			 print ("<li><a href='".$reference."deletesubdivision.php'>Delete Subdivision</a></li>");
	 			 print ("<li><a href='".$reference."viewcountries.php'>View Countries</a></li>");
	 			 print ("<li><a href='".$reference."viewsubdivisions.php'>View Subdivisions</a></li>");
	 			 print ("<li><a href='".$reference."admintranlog.php'>View System Transaction Log</a></li>");
 			  }
			  print ("
			  </ul>
	     </li> 
		 ");
			  }
		  print ("
		  </ul>
		 </li>
	  </ul>
	 </div>	
	 ");
  }
	print (" </div>");
	
 }
 else //they really ought log in.
 {
  print ("
	<div id='menu'>
	 <ul>
	  <li><a href='".$reference."login.php'>Login</a></li>
	 </ul>
	 <ul>
	  <li><a href='".$reference."createaccount.php'>Create Account</a></li>
	 </ul>
	</div>
  ");
 } 
}


?>