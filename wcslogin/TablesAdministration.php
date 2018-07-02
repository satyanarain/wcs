<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

$portal = 'TablesAdministration';
$table = 'tables';

define("_first_", 1);

if (isset ($_POST['first'])) $first = $_POST['first']; else $first = _first_;
if ($first < _first_) $first = _first_;
$count = 0;
$total = 0;
$max = 20;

include "$table.php";
include 'showtable.php';
include 'showtable.tableforms.php';
include 'showtable.tables.php';
include 'showtable.tablejava.php';



/* ---------------------------------------------------------------- */

if ((isset ($_POST['deleteid'])) && ($_POST['deleteid'] >= 1) && _deleteAllowed ())
   { _setField ($table, 'DELETED', $_POST['deleteid'], $GLOBALS['timestamp'], $_POST['id']);
   }

/* ---------------------------------------------------------------- */

$list = _showtable ($table, $_POST['role'], $portal, $first, $count, $total, $max, "TABLENAME", "");

include 'counts.php';

$list = <<<NXT
<TABLE cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
$tableforms
$list
<TR> <TD class="th" colspan="$tablecolumns">
$chunk
</TD>
</TR>
</FORM>
</TABLE>

NXT;


/* ---------------------------------------------------------- */

$showtable_tables = <<<NXT
<TABLE ID="newtablebgr" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

<TABLE ID="newtable" width="550" height="400" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="newframe" ID="newframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="deltable" width="280" height="140" cellspacing="0" celpadding="0" border="0" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden; border-style: ridge; border-width: 2; border-color: #807000; ">
<TR>
<TD colspan="2" align="center"><B>Delete: <SPAN ID="entrydel"></SPAN> ?
</TD>
</TR>
<TR>
<TD align="center">
<INPUT type="button" value="Delete" class="btn" onclick="document.showtable.submit();">
</TD>
<TD align="center">
<INPUT type="button" value="Cancel" class="btn" onclick="document.showtable.deleteid.value = ''; document.getElementById('newtablebgr').style.visibility = 'hidden'; document.getElementById('deltable').style.visibility = 'hidden';">
</TD>
</TR>
</TABLE>

<TABLE name="edittable" ID="edittable" width="360" height="244" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="editframe" ID="editframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"  scrolling="no"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="notestable" width="600" height="500" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="notesframe" ID="notesframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="docstable" width="600" height="500" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="docsframe" ID="docsframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="rectable" width="100%" height="100%" cellspacing="0" celpadding="0" border="1" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="recframe" ID="recframe" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE name="pickrecordtable" ID="pickrecordtable" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="pickrecordframe" ID="pickrecordframe" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>


<Iframe width="0" height="0" frameborder="0" SRC="editcheck.php?t={$GLOBALS['timestamp']}&n=$table&u={$_POST['id']}" allowTransparency="true" 
style="position: absolute; left: 0; top: 0; visibility: hidden;"></Iframe>

NXT;

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
Copyright © 2012 Jan Kirstein. All rights reserved!
-->

$style

$tablejava

<BODY style="margin: 8;" 
onload="
if (document.showtable.showrec.value)
   { _goRecview ('$table', document.showtable.showrec.value);
      document.showtable.showrec.value = '';
   }
">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
$list 
</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

$showtable_tables

</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

