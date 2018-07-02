<?php
@session_start();

//	Copyright © 2012 JayKSoft / Jan Kirstein Møller. All rights reserved

include_once 'std.php';
include 'logincheck.php';
include 'style.php';

/* ---------------------------------------------------------------- */

$sql = new mySQLclass();

/* ---------------------------------------------------------------------------*/

function _locationError ()
{ return <<<NXT
<TR><TD>
<DIV class="the">
Error!
</DIV>
<P>
One or more prerequisite fields
<BR>
(Order: District -&gt; County -&gt; Subcounty)
<BR>
must be filled before this!
</TD>
</TR>
NXT;
}

/* ---------------------------------------------------------------------------*/

if (isset ($_POST['filter'])) 
   { $error = false;
      $values = array ();
      $filters = explode (",", $_POST['filter']);
      $query = <<<NXT
SELECT * from {$_POST['table']} WHERE id = '{$_POST['record']}'
NXT;
      $sql->_sqlLookup ($query);
      $row = mysql_fetch_assoc ($sql->sql_result);
      foreach ($filters as &$_filter)
                   { if ($row[$_filter] == '') $error = true;
                      $_filter = "$_filter = '{$row[$_filter]}'";
                   }
      $filter = implode (" AND ", $filters);
      $filter = "AND $filter";
   } else $filter = "";


$suggestions = "";
$suggestion = false;
$used = array ();
$visibility = array (false => 'hidden', true => 'visible' );

$see = "SEE";

if (! $error)
{
$query = <<<NXT
SELECT * from {$_POST['picktable']} WHERE {$_POST['pickfield']} LIKE '{$_POST['match']}%' $filter
NXT;
$sql->_sqlLookup ($query);
if ($sql->sql_result === false) $sql->_sqlerror (); 
//if ($sql->sql_result === false) $see .= "<P>query: $query"; 


while ($row = mysql_fetch_assoc ($sql->sql_result))
         { if (! in_array ($row[$_POST['returnfield']], $used))
              { array_push ($used, $row[$_POST['returnfield']]);
                 $suggestion = true;
                 $suggestions .= <<<NXT
<TR><TD style="cursor: default;"
onmouseover="
this.style.background='#0000A0';
this.style.color='#F7F6F0';
"
onmouseout="
this.style.background='#FFFFFF';
this.style.color='#000000';
"
onclick="
parent.document.parform.NEW_VALUE.value='{$row[$_POST['returnfield']]}';
parent.document.parform.submit();
document.getElementById('msg').innerHTML = 'Saving...';
"
>{$row[$_POST['returnfield']]}</TD>
</TR>
NXT;
             }
         }
} else { $suggestion = true;
              $suggestions = _locationError ();
          }

$load = <<<NXT
onload="parent.document.getElementById('pickframe').style.visibility = '{$visibility[$suggestion]}';"
NXT;

if ($suggestion)
   $suggestions = <<<NXT
<TABLE ID="suggestion" cellspacing="0" cellpadding="2" border="0">
$suggestions
</TABLE>
NXT;


/* ---------------------------------------------------------------------------*/


$page = <<<NXT
<HTML>
<HEAD>
<TITLE>districts & parishes</TITLE>
<meta name="AUTHOR" content="Jan Kirstein">
<meta http-equiv="expires" content="0">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</HEAD>

$style

<BODY style="background: #FFFFFF;" $load>
<TABLE ID="suggestion" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; border-color: #0000A0; border-style: solid; border-width: 2 0 0 2;">
<TR>
<TD Valign="middle" ID="msg">
$suggestions
</TD>
</TR>
</TABLE>

</BODY>
</HTML>

NXT;

echo $page;

error_reporting ($errorlevel);

?>


