<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

$fromday = $_POST['fromday'];
$frommonth = $_POST['frommonth'];
$fromyear = $_POST['fromyear'];
$today = $_POST['today'];
$tomonth = $_POST['tomonth'];
$toyear = $_POST['toyear'];

$fromdate = mktime (0, 0, 0, $frommonth, $fromday, $fromyear);
$_todate = mktime (0, 0, 0, $tomonth, $today, $toyear);
$todate = mktime (23, 59, 59, $tomonth, $today, $toyear);

include 'stats4.php';

/* ---------------------------------------------------------------- */

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein • e-mail: jan.kirstein\@jayksoft.com
Copyright © 2012 - 2013 Jan Kirstein. All rights reserved!
-->

$style

<STYLE>
.tds { padding: 0 4 0 4; }
.tdsheader { padding: 0 4 0 4; background: #585858; color: #FFFFFF; font-weight: bold; }
</STYLE>

<BODY style="margin: 8;">

<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="background: #F8F6F2;">
<TR>
<TD align="center" Valign="middle">

<DIV align="left" style="font-size: 10pt;">
<B>Open Cases summary</B>
</DIV>

<P>

<TABLE width="100%" cellspacing="0" cellpadding="4" border="0">
<TR>
<TD align="center" Valign="top">$report
</TD>
</TR>
</TABLE>


</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<Iframe name="downframe" ID="downframe" width="0" height="0" frameborder="0" SRC="getfile5.php?file=$_docspath/opencasestats.csv" allowTransparency="true"></Iframe>

</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

