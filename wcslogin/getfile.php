<?php 
@session_start();

include_once 'std.php';


/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

echo <<<NXT
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


<BODY onload="document.downfrm.submit();">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<FORM name="downfrm" method="POST" action="getfile2.php">
<INPUT type="HIDDEN" name="_docspath" value="{$_POST['_docspath']}">
<INPUT type="HIDDEN" name="file" value="{$_POST['file']}">
</FORM>
<TR>
<TD align="center" Valign="middle">
&nbsp;
</TD>
</TR>
</TABLE>
</BODY>
</HTML>

NXT;



?>

