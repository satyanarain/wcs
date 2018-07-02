<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */


$table = $_POST['table'];
$recID = $_POST['record'];

include "$table.php";
include 'showtable.php';		// for the _checkFields() & _addSlashes () functions

/* ---------------------------------------------------------------- */

$query = "SELECT * FROM $table WHERE id = '$recID'";
$sql = new mySQLclass();
$sql->_sqlLookup ($query);
if ($sql->sql_result === false) $sql->_sqlerror ();

$title = $GLOBALS['tablehandlings'][$table]['NOTES'];

if ($row = mysql_fetch_assoc ($sql->sql_result))
   { foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
      $notesheader = <<<NXT
$title
NXT;
   }

$docicon = _notesimage2_;

/* ---------------------------------------------------------------- */

_makePath ($GLOBALS['notespath'], $_POST['table']);
$notesfilename = "{$GLOBALS['notespath']}{$_POST['table']}/{$_POST['record']}.txt";
$notes = file ($notesfilename);
if ($notes === false) $notes = ""; else $notes = implode ("", $notes);
$notes = str_replace ("<br>", "\n", $notes);

/* ---------------------------------------------------------------- */


if ($_POST['edit'])
   $list = <<<EDITNXT
<TABLE width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<FORM name="notesform" method="POST" action="notes.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="record" value="$recID">
<INPUT type="HIDDEN" name="edit" value="1">
<TR>
<TD height="10">
<TABLE width="100%" cellspacing="0" cellpadding="2" border="0">
<TR>
<TD class="th">
$docicon
</TD>
<TD class="th">
$notesheader
</TD>
<TD class="th" align="center">
<INPUT type="SUBMIT" Value="Save notes" class="btn" onclick="parent.document.getElementById('notestable').style.visibility = 'hidden';">
</TD>
<TD class="th" align="center"><INPUT type="BUTTON" class="btn" Value="Close" onclick="parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('notestable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR>
<TD>
<TEXTAREA class="ntsedit" name="NEW_TEXT" cols="60" rows="20">$notes</TEXTAREA>
</TD>
</TR>
</FORM>
</TABLE>

EDITNXT;
   else $list = <<<NNXT
<TABLE width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD height="10">
<TABLE width="100%" cellspacing="0" cellpadding="2" border="0">
<TR>
<TD class="th">
$docicon
</TD>
<TD class="th">
$notesheader
</TD>
<TD class="th" align="center"><INPUT type="BUTTON" class="btn" Value="Close" onclick="parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('notestable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR>
<TD style="padding: 12 0 12 8;">
$notes
</TD>
</TR>
</TABLE>

NNXT;


/* ---------------------------------------------------------------- */

$load = "";

if (isset ($_POST['NEW_TEXT']))
   { $nts = fopen ($notesfilename, "w");
      if (get_magic_quotes_gpc()) $_POST['NEW_TEXT'] = stripslashes ($_POST['NEW_TEXT']);
      fwrite ($nts, $_POST['NEW_TEXT']);
      fclose ($nts);
      $query =  "UPDATE $table SET NOTES = '{$GLOBALS['timestamp']}' WHERE id = '$recID'";
      $sql->_sqlLookup ($query);
      if ($sql->sql_result === false) $sql->_sqlerror ();
      $query = "UPDATE tables SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}', USER = '{$_POST['id']}' WHERE TABLENAME = '$table'";
      $sql->_sqlLookup ($query);
      $query = "UPDATE $table SET CHANGED = '{$GLOBALS['timestamp']}', CHANGEDBY = '{$_POST['id']}' WHERE id = '$recID'";
      $sql->_sqlLookup ($query);
      $list = "<B>Notes saved";
      $load = "onload=\"parent.document.getElementById('notestable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.showtable.submit();\"";
   }

/* ---------------------------------------------------------------- */

$list = <<<NXT
<P>
$list

NXT;

/* ---------------------------------------------------------------- */




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

<BODY $load>
<TABLE ID="maintable" width="566" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="top">
<DIV ID="viewtable">
$list 
</DIV>
</TD>
</TR>
</TABLE>
</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

