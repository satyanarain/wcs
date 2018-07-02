<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

$portal = 'CasesAdministration';
$table = 'cases';

define("_first_", 1);

if (isset ($_POST['first'])) $first = $_POST['first']; else $first = _first_;
if ($first < _first_) $first = _first_;
$count = 0;
$total = 0;
$max = 18;

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


$list = _showtable ($table, $_POST['role'], $portal, $first, $count, $total, $max, "UWA_CASEID", "");

include 'counts.php';

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

<DIV align="left" style="font-size: 10pt;">
<B>Note: </B>Upload document scans -&gt; Prosecution script, Judicial Officer's opinion &amp; Rulings - to documents placeholder!
<BR><B>-</B>
</DIV>

<TABLE cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
$tableforms

$list

<TR> <TD class="th" colspan="$tablecolumns">
$chunk
</TD></TR>

</FORM>
</TABLE>

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

