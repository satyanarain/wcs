<?php
	$hostname = "localhost";
	$username = "opianfpe_wcs";
	$password = "wcspass@123";
	$database = "opianfpe_wcsportal";
	mysql_connect($hostname,$username,$password) or die(mysql_error()); 
	mysql_select_db($database);
?>