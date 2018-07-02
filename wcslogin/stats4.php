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
$report2 = "";
//$CSV = "";
$CSV2 = <<<NXT
Crime	Prosecutions	Succesful prosecutions (%)	Acquittals	Dismissed by prosecutor	Dismissed by court	Cautions issued	Fines issued	Amount fined total	Prison term verdicts	Prison days total	Community service verdicts	Community service days total	Other sentences

NXT;

$totalprosecutions = 0;
$totalacquitted = 0;
$totaldismissedbyprosecutor = 0;
$totaldismissedbycourt = 0;
$totalcautioned = 0;
$totalfined = 0;
$totalprisonterm = 0;
$totalcommunityservice = 0;
$totalother = 0;

$totalprisondays = 0;
$totalcommunityservicedays = 0;
$totalfines = 0;

$offender = array ();
// array (fines, fines.amount, prisonterms, prisondays, comm.service terms, comm.servicedays)

$cases = new mySQLclass();
$cases2 = new mySQLclass();
$cases3 = new mySQLclass();
$casesN = new mySQLclass();
                            
if ($value == "(area)") $addon = ""; else $addon = "AND UWA_CASEID like '" . $value . "%'";

foreach ($crimetypearray as $crime)
             { if (($_POST['CRIME']) && ($_POST['CRIME'] != $crime)) continue;
                if ($_POST['CRIME']) $_crime = $_POST['CRIME']; else $_crime = $crime;
                if ($fromdate >= $_todate) $query = "SELECT FROM_UNIXTIME(REGISTERED) AS REGISTERED_DATE, UWA_CASEID, POLICE_CASE_ID, CRIME_TYPE, FROM_UNIXTIME(COURTDATE) AS COURT_DATE, UWA_PROSECUTOR, STATE_PROSECUTOR, DEFENCE_LAWYER, MAGISTRATE FROM cases WHERE !(ENDEDDATE) AND DELETED = 0 " . $addon . " order by id";
                  else $query = "SELECT FROM_UNIXTIME(REGISTERED) AS REGISTERED_DATE, UWA_CASEID, POLICE_CASE_ID, CRIME_TYPE, FROM_UNIXTIME(COURTDATE) AS COURT_DATE, UWA_PROSECUTOR, STATE_PROSECUTOR, DEFENCE_LAWYER, MAGISTRATE FROM cases WHERE (COURTDATE >= $fromdate) AND (ENDEDDATE <= $todate) AND !(ENDEDDATE) AND DELETED = 0 " . $addon . " order by id";
				//echo("Query : " . $query);
				$cases->_sqlLookup ($query);
                if ($cases->sql_result === false) $cases->_sqlerror (); 
					$countrow = 0;
					$report .= <<<NXT
						<TR>
						<TD class="tdsheader"><B>S. No.</B></TD>
						<TD class="tdsheader"><B>Registration Date</B></TD>
						<TD class="tdsheader"><B>UWA Case ID</B></TD>
						<TD class="tdsheader"><B>Police Case ID</B></TD>
						<TD class="tdsheader"><B>Crime Type</B></TD>
						<TD class="tdsheader"><B>Court Date</B></TD>
						<TD class="tdsheader"><B>UWA Prosecutor</B></TD>
						<TD class="tdsheader"><B>State Prosecutor</B></TD>
						<TD class="tdsheader"><B>Defence Lawyer</B></TD>
						<TD class="tdsheader"><B>Magistrate</B></TD>
						</TR>
NXT;

					$report1 .= <<<NXT
S. No.	Registration Date	UWA Case ID	Police Case ID	Crime Type	Court Date	UWA Prosecutor	State Prosecutor	Defence Lawyer	Magistrate
NXT;

                while ($row = mysql_fetch_assoc ($cases->sql_result)) 
				{ 
					$countrow = $countrow + 1;
					$report .= <<<NXT
						<TR>
						<TD>{$countrow}</TD>
						<TD>{$row['REGISTERED_DATE']}</TD>
						<TD>{$row['UWA_CASEID']}</TD>
						<TD>{$row['POLICE_CASE_ID']}</TD>
						<TD>{$row['CRIME_TYPE']}</TD>
						<TD>{$row['COURT_DATE']}</TD>
						<TD>{$row['UWA_PROSECUTOR']}</TD>
						<TD>{$row['STATE_PROSECUTOR']}</TD>
						<TD>{$row['DEFENCE_LAWYER']}</TD>
						<TD>{$row['MAGISTRATE']}</TD>
						</TR>
NXT;

$report1 = "$report1\n";
$report1 .= <<<NXT
{$countrow}	{$row['REGISTERED_DATE']}	{$row['UWA_CASEID']}	{$row['POLICE_CASE_ID']}	{$row['CRIME_TYPE']}	{$row['COURT_DATE']}	{$row['UWA_PROSECUTOR']}	{$row['STATE_PROSECUTOR']}	{$row['DEFENCE_LAWYER']}	{$row['MAGISTRATE']}
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


$scvfile = fopen ("$_docspath/opencasestats.csv", "w");
fwrite ($scvfile, $CSV);
fclose ($scvfile);

/* ---------------------------------------------------------------- */


?>

