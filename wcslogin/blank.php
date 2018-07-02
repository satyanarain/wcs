<?php
@session_start();

//	Copyright © 2012 JayKSoft / Jan Kirstein Møller. All rights reserved


/* ---------------------------------------------------------------------------*/

include 'std.php';
include 'style.php';

$name = $_GET['name'];
$fontval = $_GET['font'];
$meta = '<META http-equiv=Content-Type content="text/html; charset=utf-8">';
$message = "Welcome " . $name;
switch ($fontval) {
	case "FR":
		//$meta = '<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">';
		$message = "Bienvenue " . $name;
		break;
	case "ES":
		//$meta = '<META http-equiv=Content-Type content="text/html; charset=utf-8">';
		$message = "Bienvenido " . $name;
		break;
}


$page = <<<NXT
<HTML>
<HEAD>
<TITLE>Home Page</TITLE>
<meta http-equiv="expires" content="0">
$meta
</HEAD>

$style

<BODY>
<TABLE width="100%" height="100%" cellspacing="0" celpadding="0" border="0">
<TR>
<TD align="center">
{$_timg ("s=55&title=$message&font=$fontval")}
</TD>
</TR>
</TABLE>

<TABLE ID="newtablebgr" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

</BODY>
</HTML>

NXT;

echo $page;

error_reporting ($errorlevel);

?>


