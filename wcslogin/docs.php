<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

if (strpos ($browser, "Firefox") > -1) $filewidth = 45; else $filewidth = 65;

/* ---------------------------------------------------------------- */

$ROLE = $_POST['role'];
$portal = $_POST['portal'];

$table = $_POST['table'];
$recID = $_POST['record'];

if ($ROLE == 'GOD') $permissions = 0xFF; else $permissions = $GLOBALS['permissions'][$ROLE][$portal][$table];

include "$table.php";
include 'showtable.php';		// for the _checkFields() & _addSlashes () functions

/* ---------------------------------------------------------------- */

$load = "";


_makePath ($GLOBALS['docspath'], "{$_POST['table']}/{$_POST['record']}");
$_docspath = "{$GLOBALS['docspath']}{$_POST['table']}/{$_POST['record']}";

/* ---------------------------------------------------------------- */

if ((isset ($_POST['_delete'])) && ($_POST['_delete'] != ''))
   { $index = file ("$_docspath/index.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if ($index !== false)
         { $ovr = fopen ("$_docspath/index.txt", "w");
            foreach ($index as &$entry)
                        { list ($time, $name, $description) = explode ("\t", $entry);
                           if ($name == $_POST['_delete']) unlink ("$_docspath/$name");
                              else fwrite ($ovr, "$entry\n");
                        }
            fclose ($ovr);
            $sqld = new mySQLclass();
            $query = "UPDATE tables SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}', USER = '{$_POST['id']}' WHERE TABLENAME = '$table'";
            $sqld->_sqlLookup ($query);
         }
   }

/* ---------------------------------------------------------------- */

if ((isset ($_FILES['uploadedfile']['name'])) && ($_FILES['uploadedfile']['name'] != ''))
   { $filename = basename( $_FILES['uploadedfile']['name']);
      if (file_exists ("$_docspath/$filename"))
         { $load = <<<NXT
onload="alert ('File: $filename\\nalready exists in this document folder!');"
NXT;
         } else 
      if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], "$_docspath/$filename"))
         { $nts = fopen ("$_docspath/index.txt", "a");
            if (get_magic_quotes_gpc()) $_POST['NEW_DESCRIPTION'] = stripslashes ($_POST['NEW_DESCRIPTION']);
            fwrite ($nts, "{$GLOBALS['timestamp']}	$filename	{$_POST['NEW_DESCRIPTION']}\n");
            fclose ($nts);
            $sql = new mySQLclass();
            $query =  "UPDATE $table SET DOCS = '{$GLOBALS['timestamp']}' WHERE id = '$recID'";
            $sql->_sqlLookup ($query);
            if ($sql->sql_result === false) $sql->_sqlerror ();
            $query = "UPDATE tables SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}', USER = '{$_POST['id']}' WHERE TABLENAME = '$table'";
            $sql->_sqlLookup ($query);
            $query = "UPDATE $table SET CHANGED = '{$GLOBALS['timestamp']}', CHANGEDBY = '{$_POST['id']}' WHERE id = '$recID'";
            $sql->_sqlLookup ($query);
         }
   }

/* ---------------------------------------------------------------- */

$query = "SELECT * FROM $table WHERE id = '$recID'";
$sql = new mySQLclass();
$sql->_sqlLookup ($query);
if ($sql->sql_result === false) $sql->_sqlerror ();

$title = $GLOBALS['tablehandlings'][$table]['DOCS'];
if ($row = mysql_fetch_assoc ($sql->sql_result))
   { foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
      $notesheader = <<<NXT
$title

NXT;
   }

/* ---------------------------------------------------------------- */


$index = file ("$_docspath/index.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($index === false) 
   { $ndx = fopen ("$_docspath/index.txt", "w");
      fclose ($ndx);
      $index = array (); 
   }
$list = "";
$i = 0;
foreach ($index as &$entry)
             { list ($time, $name, $description) = explode ("\t", $entry);
                $showtime = date ("d/m-Y H:i:s", $time);
                if ($description == '') $description = "&nbsp;";
                if ($i & 1) $class = " class=\"ta\""; else $class = " class=\"tb\"";
                if ($permissions & _delete_) 
                   { $del = <<<NXT
<TD$class style="cursor: {$GLOBALS['cursor']}"
onclick="_goDelete2 ('$name');"><IMG src="images/del2.png"></TD>
NXT;
                   } else $del = "";
                $down = <<<NXT
 style="cursor: {$GLOBALS['cursor']};" onclick="document.downfrm.file.value='$name'; document.downfrm.submit();"
NXT;
                $fsize= number_format ( filesize ("$_docspath/$name") / 1024 , 1 , ',' , '.' ).' kB';
                $list .= <<<NXT
<TR>$del<TD$class>$showtime</TD><TD$class$down><B>$name</TD><TD$class align="right" style="font-size: 8pt;">$fsize</TD><TD$class>$description</TD></TR>

NXT;
                $i++;
             }

if ($permissions & _delete_) 
      { $del = <<<NXT
<TD class="th"><IMG src="images/del3.png"></TD>
NXT;
      } else $del = "";

$list = <<<NXT
<TABLE width="100%" cellspacing="0" cellpading="2" border="1">
<TR>$del<TD class="th">Uploaded</TD><TD class="th">File</TD><TD class="th">Size</TD><TD class="th">Description</TD></TR>
$list
</TABLE>

NXT;


/* ---------------------------------------------------------------- */

$folder = _docsimage2_;

if ($_POST['edit'])
   $list = <<<EDITNXT
<TABLE width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<FORM name="downfrm" method="POST" action="getfile.php" target="downframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="portal" value="{$_POST['portal']}">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="record" value="$recID">
<INPUT type="HIDDEN" name="_docspath" value="$_docspath">
<INPUT type="HIDDEN" name="file" value="">
</FORM>
<FORM name="docsfrm" method="POST" action="docs.php" enctype="multipart/form-data" 
onsubmit="document.getElementById('wait').style.visibility ='visible';">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="portal" value="{$_POST['portal']}">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="record" value="$recID">
<INPUT type="HIDDEN" name="edit" value="1">
<INPUT type="HIDDEN" name="_delete" value="">
<TR>
<TD height="10">
<TABLE width="100%" cellspacing="0" cellpadding="2" border="0">
<TR>
<TD class="th2">
$folder
</TD>
<TD class="th2">
<B>$notesheader
</TD>
<TD class="th2" align="center"><INPUT type="BUTTON" class="btn" Value="Close" 
onclick="
document.getElementById('wait').style.visibility ='visible';
parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
parent.document.getElementById('docstable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR>
<TD class="thf" height="10">

<div class="fileinputs">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="FILE" class="file" style="cursor: default;" name="uploadedfile" size="$filewidth" maxlength="80" 
onchange="_extract(value);" />&nbsp;&nbsp;&nbsp;
	<div class="fakefile">
		File:&nbsp;<INPUT style="cursor: default;" name="filename" type="TEXT" class="btn" size="50" 
                                    onclick="document.docsfrm.NEW_DESCRIPTION.focus();">
		<INPUT type="BUTTON" class="btn" Value="Pick">
	</div>
</div>

Description:&nbsp;<INPUT type="TEXT" name="NEW_DESCRIPTION" class="btn" size="68" maxlength="128">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="SUBMIT" Value="Upload file" class="btn">
</TD>
</TR>
</TR>
<TR>
<TD align="center" Valign="top" style="padding: 0 0 12 0;">
<DIV align="left" style="font-size: 8pt; padding: 12 0 2 0;">
Click on 'File'-name to view / download a document.
</DIV>
$list
</TD>
</TR>
</FORM>
</TABLE>

EDITNXT;
   else $list = <<<NNXT
<TABLE width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<FORM name="downfrm" method="POST" action="getfile.php" target="downframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="portal" value="{$_POST['portal']}">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="record" value="$recID">
<INPUT type="HIDDEN" name="_docspath" value="$_docspath">
<INPUT type="HIDDEN" name="file" value="">
</FORM>
<TR>
<TD height="10">
<TABLE width="100%" cellspacing="0" cellpadding="2" border="0">
<TR>
<TD class="th">
$folder
</TD>
<TD class="th">
$notesheader
</TD>
<TD class="th" align="center"><INPUT type="BUTTON" class="btn" Value="Close" 
onclick="
parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
parent.document.getElementById('docstable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR>
<TD align="center" Valign="top" style="padding: 0 0 12 0;">
<DIV align="left" style="font-size: 8pt; padding: 12 0 2 0;">
Click on 'File'-name to view / download a document.
</DIV>
$list
</TD>
</TR>
</TABLE>

NNXT;


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

<SCRIPT language="JavaScript">
function _centerElement (elmID, W, H)
{ document.getElementById(elmID).style.top=0+Math.round((document.body.clientHeight + document.body.scrollTop - H) / 2); 
   document.getElementById(elmID).style.left=0+Math.round((document.body.clientWidth + document.body.scrollLeft - W) / 2);
}
function _setDelFrame2 (W, H, _name)
{ document.docsfrm._delete.value = _name;
   document.getElementById('entrydel').innerHTML = _name;
   _centerElement ('deltable2', W, H);
   document.getElementById('shade').style.visibility = 'visible';
   document.getElementById('deltable2').style.visibility = 'visible';
}  
function _goDelete2 (_name)
{ _setDelFrame2 (280, 140, _name); 
}

function _extract(what) 
{ var name = '';
   if (what.indexOf('/') > -1)
      name = what.substring(what.lastIndexOf('/')+1,what.length);
      else name = what.substring(what.lastIndexOf('\\\')+1,what.length);
   document.docsfrm.filename.value = name;
}
</SCRIPT>

<BODY $load>
<TABLE ID="maintable" width="566" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="top">
$list 
</TD>
</TR>
</TABLE>

<TABLE ID="shade" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

<TABLE ID="wait" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #FFFFFF;">
<TR>
<TD align="center" Valign="middle">
<IMG src="images/loading.gif">
</TD>
</TR>
</TABLE>

<TABLE ID="deltable2" width="280" height="140" cellspacing="0" celpadding="0" border="0" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden; border-style: ridge; border-width: 2; border-color: #807000; ">
<TR>
<TD colspan="2" align="center"><B>Remove: <SPAN ID="entrydel"></SPAN> ?
</TD>
</TR>
<TR>
<TD align="center">
<INPUT type="button" value="Delete" class="btn" onclick="document.docsfrm.submit();">
</TD>
<TD align="center">
<INPUT type="button" value="Cancel" class="btn" 
onclick="document.docsfrm._delete.value = ''; 
document.getElementById('shade').style.visibility = 'hidden'; 
document.getElementById('deltable2').style.visibility = 'hidden';">
</TD>
</TR>
</TABLE>

<Iframe name="downframe" ID="downframe" width="0" height="0" frameborder="0" SRC="" allowTransparency="true"></Iframe>

</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

