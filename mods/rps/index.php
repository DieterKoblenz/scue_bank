<?php
/*
File:			index.php
Version:		0.1
Author:			Jeroen

Description:	Index page with just information about this system. Perhaps later a request form or api access.

Changelog:
0.1 First version --> Just some basic information


*/

// Includes
include_once 'inc.rps.php'; // Main inclusion file for all pages

//HEADER
print_htmlhead("Remote Payment System", true); 

//BODY
print('
<br><h1>Remote Payment System</h1>
	<p>JeCom International\'s Remote Payment System (or RPS in short) is a system designed for webshops and fora to include automated payments for users. With this system a WordPress payment provider is included and can be used to make a true online (download only) webshop. Planned is also a phpBB module or bbcode where people can make payment links.</p>
	<br>


');

//FOOTER
print_htmlfoot();


?>