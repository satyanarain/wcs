<?php 
@session_start();


/* ---------------------------------------------------------------- */

include 'fields.php';		// for the _getField () function

/* ---------------------------------------------------------------- */

if ($_POST['role'] == 'GOD') $Dformat = 'd/m-Y'; else $Dformat = _getField ('users', $_POST['id'], 'DATEFORMAT', '');
if ($_POST['role'] == 'GOD') $TSsepr = '.,'; else $TSsepr = _getField ('users', $_POST['id'], 'NUMSEPARATORS', '');
$TSsepr = str_replace ('|', ' ', $TSsepr);
$thouSep = substr ($TSsepr, 0, 1);
$decSep = substr ($TSsepr, 1, 1);
if ($thouSep == '-') $thouSep = '';

/* ---------------------------------------------------------------- */

_makePath ($GLOBALS['docspath'], "_users/{$_POST['id']}");
$_docspath = "{$GLOBALS['docspath']}_users/{$_POST['id']}";

/* ---------------------------------------------------------------- */

$crimetypearray = explode ("~", $crimetypes);

/* ---------------------------------------------------------------- */

$report = <<<NXT
<TR><TD class="tdsheader" colspan="9">
<B>Arrests / impoundments history
</TD></TR>

NXT;

$value = $_POST['item'];

$cases = new mySQLclass();
$cases2 = new mySQLclass();
                            
$query = "SELECT * FROM evidencetable WHERE (LABEL LIKE '%$value%') OR (EVIDENCE_NO LIKE '%$value%')";
$cases->_sqlLookup ($query);
if ($cases->sql_result === false) $cases->_sqlerror (); 
$count = mysql_num_rows ($cases->sql_result);

if ($count)
   { $report .= <<<NXT
<TR>
<TD class="tdsheader">
Evidence no.
</TD>

<TD class="tdsheader">
Item
</TD>

<TD class="tdsheader">
Spec.
</TD>

<TD class="tdsheader">
Arrest date
</TD>

<TD class="tdsheader">
Time
</TD>

<TD class="tdsheader">
UWA Case ID
</TD>

<TD class="tdsheader">
Station
</TD>

<TD class="tdsheader">
Evidence location
</TD>

<TD class="tdsheader">
Evidence status
</TD>

</TR>

NXT;
      while ($row = mysql_fetch_assoc ($cases->sql_result))
               { $query2 = "SELECT * FROM arrests WHERE id = {$row['ASSREC']}";
                  $cases2->_sqlLookup ($query2);
                  if ($cases2->sql_result === false) $cases2->_sqlerror (); 
                  while ($row2 = mysql_fetch_assoc ($cases2->sql_result))
                           { $date = date ($Dformat, $row2['ARRESTDATE']);
                              $report .= <<<NXT
<TR>
<TD class="tds">
{$row['EVIDENCE_NO']}
</TD>

<TD class="tds">
{$row['EVIDENCE']}
</TD>

<TD class="tds">
{$row['LABEL']}
</TD>

<TD class="tds">
$date
</TD>

<TD class="tds">
{$row2['ARRESTTIME']}
</TD>

<TD class="tds">
{$row2['UWA_CASEID']}
</TD>

<TD class="tds">
{$row2['OUTPOST']}
</TD>

<TD class="tds">
{$row['LOCATION']}
</TD>

<TD class="tds">
{$row['STATUS']}
</TD>

</TR>

NXT;
                           }
               }
   } else $report = <<<NXT
<TR><TD class="tdsheader">
<B>No records of evidence found!
</TD></TR>

NXT;






/* ---------------------------------------------------------------- */



$report = <<<NXT
<TABLE cellspacing="0" cellpadding="0" border="1">
$report
</TABLE>

NXT;

/* ---------------------------------------------------------------- */



/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */


?>

