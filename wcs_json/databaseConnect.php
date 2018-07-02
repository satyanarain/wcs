<?php
	$hostname = "localhost";
	$username = "root";
	$password = "root@1234";
	$database = "opianfpe_wcsportal";
	mysql_connect($hostname,$username,$password) or die(mysql_error()); 
	mysql_select_db($database);
?>