<?php 
@session_start();

if (! _loggedIn ($_POST['id']))
   { $page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">

</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein • e-mail: jan.kirstein\@jayksoft.com
Copyright © 2012 Jan Kirstein. All rights reserved!
-->

$style


<BODY style="margin: 8;" onload="top.document.location='{$GLOBALS['loginpage']}';">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
<B style="background: #FFFFFF;">&nbsp;Login error or inactivity timeout&nbsp;
</TD>
</TR>
</TABLE>
</BODY>
</HTML>

NXT;
echo $page;
die();
   }

?>

