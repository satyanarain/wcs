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

$sentences = new mySQLclass();

foreach ($crimetypearray as $crime)
             { $query = "SELECT  UWA_CASEID, OPT1, QTY1, UNIT1, REM1, OPT2, QTY2,  UNIT2, REM2,  OPTED, OPTEDREM, PRISONNAME, CASE PRISONSTART WHEN 0 THEN 'N/A' ELSE FROM_UNIXTIME(PRISONSTART) END AS PRISON_START, CASE PRISONEND WHEN 0 THEN 'N/A' ELSE FROM_UNIXTIME(PRISONEND) END AS PRISON_END FROM  sentencetable WHERE DELETED = 0 order by id";
				$sentences->_sqlLookup ($query);
                if ($sentences->sql_result === false) $sentences->_sqlerror (); 
					$countrow = 0;
					$report .= <<<NXT
						<TR>
						<TD class="tdsheader"><B>S. No.</B></TD>
						<TD class="tdsheader"><B>UWA Case ID</B></TD>
						<TD class="tdsheader"><B>Option 1</B></TD>
						<TD class="tdsheader"><B>Quantity 1</B></TD>
						<TD class="tdsheader"><B>Unit 1</B></TD>
						<TD class="tdsheader"><B>Remarks 1</B></TD>
						<TD class="tdsheader"><B>Option 2</B></TD>
						<TD class="tdsheader"><B>Quantity 2</B></TD>
						<TD class="tdsheader"><B>Unit 2</B></TD>
						<TD class="tdsheader"><B>Remarks 2</B></TD>
						<TD class="tdsheader"><B>Opted</B></TD>
						<TD class="tdsheader"><B>Opted Remarks</B></TD>
						<TD class="tdsheader"><B>Prison Name</B></TD>
						<TD class="tdsheader"><B>Prison Start Date</B></TD>
						<TD class="tdsheader"><B>Prison End Date</B></TD>
						</TR>
NXT;

					$report1 .= <<<NXT
S. No.	UWA Case ID	Option 1	Quantity 1	Unit 1	Remarks 1	Option 2	Quantity 2	Unit 2	Remarks 2	Opted	Opted Remarks	Prison Name	Prison Start Date	Prison End Date
NXT;

                while ($row = mysql_fetch_assoc ($sentences->sql_result)) 
				{ 
					$countrow = $countrow + 1;
					$report .= <<<NXT
						<TR>
						<TD>{$countrow}</TD>
						<TD>{$row['UWA_CASEID']}</TD>
						<TD>{$row['OPT1']}</TD>
						<TD>{$row['QTY1']}</TD>
						<TD>{$row['UNIT1']}</TD>
						<TD>{$row['REM1']}</TD>
						<TD>{$row['OPT2']}</TD>
						<TD>{$row['QTY2']}</TD>
						<TD>{$row['UNIT2']}</TD>
						<TD>{$row['REM2']}</TD>
						<TD>{$row['OPTED']}</TD>
						<TD>{$row['OPTEDREM']}</TD>
						<TD>{$row['PRISONNAME']}</TD>
						<TD>{$row['PRISON_START']}</TD>
						<TD>{$row['PRISON_END']}</TD>
						</TR>
NXT;


$report1 = "$report1\n";
$report1 .= <<<NXT
{$countrow}	{$row['UWA_CASEID']}	{$row['OPT1']}	{$row['QTY1']}	{$row['UNIT1']}	{$row['REM1']}	{$row['OPT2']}	{$row['QTY2']}	{$row['UNIT2']}	{$row['REM2']}	{$row['OPTED']}	{$row['OPTEDREM']}	{$row['PRISONNAME']}	{$row['PRISON_START']}	{$row['PRISON_END']}
NXT;
					
				}

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


$scvfile = fopen ("$_docspath/sentences.csv", "w");
fwrite ($scvfile, $CSV);
fclose ($scvfile);

/* ---------------------------------------------------------------- */


?>

