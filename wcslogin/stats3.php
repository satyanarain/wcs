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

$report = "";
$report2 = "";
$report3 = "";
$CSV = "";
$CSV2 = "";

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

foreach ($crimetypearray as $crime)
             { if (($_POST['CRIME']) && ($_POST['CRIME'] != $crime)) continue;
                if ($_POST['CRIME']) $_crime = $_POST['CRIME']; else $_crime = $crime;
                if ($fromdate >= $_todate) $query = "SELECT * FROM cases WHERE CRIME_TYPE = '$crime' AND DELETED = 0";
                  else $query = "SELECT * FROM cases WHERE (CRIME_TYPE = '$crime') AND (COURTDATE >= $fromdate) AND (COURTDATE <= $todate) AND (ENDEDDATE) AND DELETED = 0";
                $cases->_sqlLookup ($query);
                if ($cases->sql_result === false) $cases->_sqlerror (); 
                $prosecutions = 0;
                $acquitted = 0;
                $dismissedbyprosecutor = 0;
                $dismissedbycourt = 0;
                $cautioned = 0;
                $fined = 0;
                $prisonterm = 0;
                $communityservice = 0;
                $other = 0;
                $prisondays = 0;
                $communityservicedays = 0;
                $fines = 0;
                while ($row = mysql_fetch_assoc ($cases->sql_result))
                         { $query2 = "SELECT * FROM defendantstable WHERE ASSREC = {$row['id']} AND CRIME = '$_crime' AND DELETED = 0";
                            $cases2->_sqlLookup ($query2);
                            if ($cases2->sql_result === false) $cases2->_sqlerror (); 
                            while ($row2 = mysql_fetch_assoc ($cases2->sql_result))
                                     { 

                                        if (! isset ($offender["{$row2['DEFENDANT']}"])) $offender["{$row2['DEFENDANT']}"] = array (0, 0, 0, 0, 0, 0);
                                        $totalprosecutions++;
                                        $prosecutions++;

$queryN = "SELECT * FROM verdictstable WHERE ASSREC = {$row2['id']} AND VERDICT != 'Pending' AND APPEALED != 'yes' AND DELETED = 0";
                            $casesN->_sqlLookup ($queryN);
                            if ($casesN->sql_result === false) $casesN->_sqlerror (); 
                            while ($rowN = mysql_fetch_assoc ($casesN->sql_result))
                                     { $verdict = $rowN['VERDICT'];
                                        $qty = $rowN['QTY'];
                                        $unit = $rowN['UNIT'];
                                        if ($verdict == 'Acquitted') 
                                           { $acquitted++;
                                              $totalacquitted++;
                                           }
                                        elseif ($verdict == 'Dismissed by prosecutor') 
                                           { $dismissedbyprosecutor++;
                                              $totaldismissedbyprosecutor++;
                                           }
                                        elseif ($verdict == 'Dismissed by court') 
                                           { $dismissedbycourt++;
                                              $totaldismissedbycourt++;
                                           }
                                        elseif ($verdict == 'Cautioned') 
                                                 { $cautioned++;
                                                    $totalcautioned++;
                                                 }
                                        elseif ($verdict == 'Fined') 
                                                 { $fined++;
                                                    $totalfined++;
                                                    $fines += $qty;
                                                    $totalfines += $qty;
                                                    $offender["{$row2['DEFENDANT']}"][0]++;
                                                    $offender["{$row2['DEFENDANT']}"][1] += $qty;
                                                 }
                                        elseif ($verdict == 'Prison term') 
                                                 { $prisonterm++;
                                                    $totalprisonterm++;
                                                    $offender["{$row2['DEFENDANT']}"][2]++;
                                                    if ($unit == 'Weeks')
                                                      { $prisondays += $qty * 7;
                                                         $totalprisondays += $qty * 7;
                                                         $offender["{$row2['DEFENDANT']}"][3] += $qty * 7;
                                                      }
                                                    elseif ($unit == 'Months')
                                                      { $prisondays +=  round ($qty * 365.249 / 12, 0);
                                                         $totalprisondays += round ($qty * 365.249 / 12, 0);
                                                         $offender["{$row2['DEFENDANT']}"][3] += round ($qty * 365.249 / 12, 0);
                                                      }
                                                    elseif ($unit == 'Years')
                                                      { $prisondays += $qty * 365;
                                                         $totalprisondays += $qty * 365;
                                                         $offender["{$row2['DEFENDANT']}"][3] += $qty * 365;
                                                      }
                                                    elseif ($unit == 'Days')
                                                      { $prisondays += $qty;
                                                         $totalprisondays += $qty;
                                                         $offender["{$row2['DEFENDANT']}"][3] += $qty;
                                                      }
                                                 }
                                        elseif ($verdict == 'Community service') 
                                                 { $communityservice++;
                                                    $totalcommunityservice++;
                                                    $offender["{$row2['DEFENDANT']}"][4]++;
                                                    if ($unit == 'Weeks')
                                                      { $communityservicedays += $qty * 7;
                                                         $totalcommunityservicedays += $qty * 7;
                                                         $offender["{$row2['DEFENDANT']}"][5] += $qty * 7;
                                                      }
                                                    elseif ($unit == 'Months')
                                                      { $communityservicedays += round ($qty * 365.249 / 12, 0);
                                                         $totalcommunityservicedays += round ($qty * 365.249 / 12, 0);
                                                         $offender["{$row2['DEFENDANT']}"][5] += round ($qty * 365.249 / 12, 0);
                                                      }
                                                    elseif ($unit == 'Years')
                                                      { $communityservicedays += $qty * 365;
                                                         $totalcommunityservicedays += $qty * 365;
                                                         $offender["{$row2['DEFENDANT']}"][5] += $qty * 365;
                                                      }
                                                    elseif ($unit == 'Days')
                                                      { $communityservicedays += $qty;
                                                         $totalcommunityservicedays += $qty;
                                                         $offender["{$row2['DEFENDANT']}"][5] += $qty;
                                                      }
                                                 }
                                        else { $other++;
                                                   $totalother++;
                                                }

                                               } // ???

                                     }
                         }

             }
/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */



/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

$offenders = 0;
$repeatoffenders= 0;
$fines = 0;
$repeatfines = 0;
$prisondays = 0;
$repeatprisondays = 0;
$commsdays = 0;
$repeatcommsdays = 0;

$nofines = 0;
$norepeatfines = 0;
$noprisondays = 0;
$norepeatprisondays = 0;
$nocommsdays = 0;
$norepeatcommsdays = 0;

foreach ($offender as $offndkey => $offnd)
             { 
/*$query3 = "SELECT COUNT(DEFENDANT) FROM defendantstable WHERE DEFENDANT = $offndkey AND DELETED = 0";
                $cases3->_sqlLookup ($query3);
                if ($cases3->sql_result === false) $cases3->_sqlerror (); 
                $row3 = mysql_fetch_assoc ($cases3->sql_result);
                if ($row3['COUNT(DEFENDANT)'] > 1)
*/
                if (($offnd[0] + $offnd[2] + $offnd[4]) > 1)
                   { $repeatoffenders++;
                      $repeatfines += $offnd[1];
                      $repeatprisondays += $offnd[3];
                      $repeatcommsdays += $offnd[5];
                      $norepeatfines += $offnd[0];
                      $norepeatprisondays += $offnd[2];
                      $norepeatcommsdays += $offnd[4];
                   } else { $offenders++;
                                 $fines += $offnd[1];
                                 $prisondays += $offnd[3];
                                 $commsdays += $offnd[5];
                                 $nofines += $offnd[0];
                                 $noprisondays += $offnd[2];
                                 $nocommsdays += $offnd[4];
                              }
             }

$avgfines = round ($fines / $nofines, 0);
$avgrepeatfines = round ($repeatfines / $norepeatfines, 0);
$avgprisondays = round ($prisondays / $noprisondays, 1);
$avgrepeatprisondays = round ($repeatprisondays / $norepeatprisondays, 1);
$avgcommsdays = round ($commsdays / $nocommsdays, 1);
$avgrepeatcommsdays = round ($repeatcommsdays / $norepeatcommsdays, 1);

$CSV .= <<<NXT
Average fine, non-repeat offenders	$avgfines
Average fine, repeat offenders	$avgrepeatfines
Average prison days, non-repeat offenders	$avgprisondays
Average prison days, repeat offenders	$avgrepeatprisondays
Average community service days, non-repeat offenders	$avgcommsdays
Average community service days, repeat offenders	$avgrepeatcommsdays

NXT;

/*
$CSV2 .= <<<NXT

Average fine, non-repeat offenders	Average fine, repeat offenders	Average prison days, non-repeat offenders	Average prison days, repeat offenders	Average community service days, non-repeat offenders	Average community service days, repeat offenders
$avgfines	$avgrepeatfines	$avgprisondays	$avgrepeatprisondays	$avgcommsdays	$avgrepeatcommsdays

NXT;
*/

/* ---------------------------------------------------------------- */

$avgfines = number_format ($avgfines, 0, "$decSep", "$thouSep");
$avgrepeatfines = number_format ($avgrepeatfines, 0, "$decSep", "$thouSep");
$avgprisondays = number_format ($avgprisondays, 1, "$decSep", "$thouSep");
$avgrepeatprisondays = number_format ($avgrepeatprisondays, 1, "$decSep", "$thouSep");
$avgcommsdays = number_format ($avgcommsdays, 1, "$decSep", "$thouSep");
$avgrepeatcommsdays = number_format ($avgrepeatcommsdays, 1, "$decSep", "$thouSep");

$report3 .= <<<NXT
<TR><TD class="tdsheader" colspan="2">
<B>Average punishments
</TD></TR>

<TR><TD class="tds">
Average fine, non-repeat offenders</TD>
<TD class="tds" align="right">
$avgfines
</TD></TR>

<TR><TD class="tds">
Average fine, repeat offenders</TD>
<TD class="tds" align="right">
$avgrepeatfines
</TD></TR>

<TR><TD class="tds">
Average prison days, non-repeat offenders</TD>
<TD class="tds" align="right">
$avgprisondays
</TD></TR>

<TR><TD class="tds">
Average prison days, repeat offenders</TD>
<TD class="tds" align="right">
$avgrepeatprisondays
</TD></TR>

<TR><TD class="tds">
Average community service days, non-repeat offenders</TD>
<TD class="tds" align="right">
$avgcommsdays
</TD></TR>

<TR><TD class="tds">
Average community service days, repeat offenders</TD>
<TD class="tds" align="right">
$avgrepeatcommsdays
</TD></TR>

NXT;

/* ---------------------------------------------------------------- */

$report3 = <<<NXT
<TABLE cellspacing="0" cellpadding="0" border="1">
$report3
</TABLE>

NXT;

/* ---------------------------------------------------------------- */

//$CSV = "$CSV\n$CSV2";

if ($_POST['separator'] == "<;>") $CSV = str_replace ("	", ";", $CSV);
if ($_POST['separator'] == "<,>") $CSV = str_replace ("	", ",", $CSV);
$CSV = str_replace ("\r", "", $CSV);
$CSV = str_replace ("\n", "\r\n", $CSV);


$scvfile = fopen ("$_docspath/verdictsstats.csv", "w");
fwrite ($scvfile, $CSV);
fclose ($scvfile);

/* ---------------------------------------------------------------- */


?>

