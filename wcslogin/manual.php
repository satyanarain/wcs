<?php 
@session_start();

include_once 'std.php';

include 'logincheck.php';

/* ---------------------------------------------------------------- */

   if (($_POST['role'] == 'GOD') || ($_POST['role'] == 'SYSADMIN')) $file = 'UWA User Manual.html'; else $file = 'UWA User Manual NA.html';

/* ---------------------------------------------------------------- */

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

$style

<BODY style="background: #F8F6F2;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">

<FORM name="getform" method="POST" action="getfile4.php" target="pdfframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="file" value="UWA User Manual - ED.pdf">

<TR>
<TD width="100%" height="30" align="right" Valign="middle">
Click here to download the full manual in a 
<INPUT type="SUBMIT" value="PDF document" style="font-weight: bold; font-size: 10pt;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<Iframe name="pdfframe" ID="pdfframe" width="1" height="1" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>

</FORM>

<TR>
<TD align="center" Valign="top">
<Iframe name="manualframe" ID="manualframe" width="100%" height="100%" frameborder="0" SRC="$file" allowTransparency="true"></Iframe>
</TD>
</TR>

</TABLE>

NXT;

echo $page;

/* ---------------------------------------------------------------- */

/*
$helpfile = file ($file);
$page = implode ('', $helpfile);

echo $page;
*/

error_reporting ($errorlevel);

?>

