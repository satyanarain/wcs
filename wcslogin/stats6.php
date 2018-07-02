<?php 
@session_start();


/* ---------------------------------------------------------------- */

include 'fields.php';		// for the _getField () function
include 'reverserc.php';

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

$value = $_POST['area'];

$report = "";
$report1 = "";

$parishes = new mySQLclass();
$suspects = new mySQLclass();
$offenders = new mySQLclass();

$query = "SELECT id, D_2010_NAME, S_2010_NAME, P_2010_NAME, SUBREGION, AREA_AREA, AREA_PARAMETER FROM parishes WHERE DELETED = 0 order by P_2010_NAME";
$parishes->_sqlLookup ($query);
if ($parishes->sql_result === false) $parishes->_sqlerror (); 
$countrow = 0;
$report .= <<<NXT
			<TR>
			<TD class="tdsheader"><B>S. No.</B></TD>
			<TD class="tdsheader"><B>Parish ID</B></TD>
			<TD class="tdsheader"><B>District (2010)</B></TD>
			<TD class="tdsheader"><B>Subcounty (2010)</B></TD>
			<TD class="tdsheader"><B>Parish (2010)</B></TD>
			<TD class="tdsheader"><B>Subregion</B></TD>
			<TD class="tdsheader"><B>Area</B></TD>
			<TD class="tdsheader"><B>Parameter</B></TD>
			<TD class="tdsheader"><B>Suspects</B></TD>
			<TD class="tdsheader"><B>Offenders</B></TD>
			</TR>
NXT;

$report1 .= <<<NXT
S. No.	Parish ID	District (2010)	Subcounty (2010)	Parish (2010)	Subregion	Area	Parameter	Suspects	Offenders
NXT;

while ($row = mysql_fetch_assoc ($parishes->sql_result)) 
{
	$SuspectCount = 0;
	$OffenderCount = 0;
	$querySuspect = "SELECT COUNT(*) AS COUNT_NUM FROM suspects WHERE PARISH = {$row['id']} AND DELETED = 0";
	$suspects->_sqlLookup ($querySuspect);
	if ($suspects->sql_result === false) $suspects->_sqlerror (); 
	while ($row2 = mysql_fetch_assoc ($suspects->sql_result))
	{ 
		$SuspectCount = $row2['COUNT_NUM'];
	}
	$queryOffender = "SELECT COUNT(*) AS COUNT_NUM FROM offenderstable WHERE PARISH = {$row['id']} AND DELETED = 0";
	$offenders->_sqlLookup ($queryOffender);
	if ($offenders->sql_result === false) $offenders->_sqlerror (); 
	while ($row3 = mysql_fetch_assoc ($offenders->sql_result))
	{ 
		$OffenderCount = $row3['COUNT_NUM'];
	}
	$countrow = $countrow + 1;
	$report .= <<<NXT
		<TR>
		<TD>{$countrow}</TD>
		<TD>{$row['id']}</TD>
		<TD>{$row['D_2010_NAME']}</TD>
		<TD>{$row['S_2010_NAME']}</TD>
		<TD>{$row['P_2010_NAME']}</TD>
		<TD>{$row['SUBREGION']}</TD>
		<TD>{$row['AREA_AREA']}</TD>
		<TD>{$row['AREA_PARAMETER']}</TD>
		<TD>{$SuspectCount}</TD>
		<TD>{$OffenderCount}</TD>
		</TR>
NXT;


$report1 = "$report1\n";
$report1 .= <<<NXT
{$countrow}	{$row['id']}	{$row['D_2010_NAME']}	{$row['S_2010_NAME']}	{$row['P_2010_NAME']}	{$row['SUBREGION']}	{$row['AREA_AREA']}	{$row['AREA_PARAMETER']}	{$SuspectCount}	{$OffenderCount}
NXT;
					
}

				
/* ---------------------------------------------------------------- */

//$CSV3 = _reverseRC ($report1);

/* ---------------------------------------------------------------- */

//$CSV = "$CSV\n$CSV2\n$CSV3";
$CSV = "$report1\n";

if ($_POST['separator'] == "<;>") $CSV = str_replace ("	", ";", $CSV);
if ($_POST['separator'] == "<,>") $CSV = str_replace ("	", ",", $CSV);
$CSV = str_replace ("\r", "", $CSV);
$CSV = str_replace ("\n", "\r\n", $CSV);


$scvfile = fopen ("$_docspath/ESRI_Analysis.csv", "w");
fwrite ($scvfile, $CSV);
fclose ($scvfile);

/* ---------------------------------------------------------------- */


?>

