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
                            


foreach ($crimetypearray as $crime)
             { if (($_POST['CRIME']) && ($_POST['CRIME'] != $crime)) continue;
                if ($_POST['CRIME']) $_crime = $_POST['CRIME']; else $_crime = $crime;
                if ($fromdate >= $_todate) $query = "SELECT * FROM cases WHERE DELETED = 0";
                  else $query = "SELECT * FROM cases WHERE (COURTDATE >= $fromdate) AND (ENDEDDATE <= $todate) AND (ENDEDDATE) AND DELETED = 0";
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
                                        else if ($verdict == 'other-') 
                                                  { $other++;
                                                     $totalother++;
                                                  }

                                               } // ???

                                     }
                         }
                if ($prosecutions > 0)
                {
                $succesfulprosecutions = round ((($prosecutions - $acquitted - $dismissedbyprosecutor - $dismissedbycourt) * 100) / $prosecutions, 1);
                $CSV2 .= <<<NXT
$crime	$prosecutions	$succesfulprosecutions	$acquitted	$dismissedbyprosecutor	$dismissedbycourt	$cautioned	$fined	$fines	$prisonterm	$prisondays	$communityservice	$communityservicedays	$other

NXT;
                $succesfulprosecutions = number_format ((($prosecutions - $acquitted - $dismissedbyprosecutor - $dismissedbycourt) * 100) / $prosecutions, 1, "$decSep", "$thouSep"); 
                $prosecutions = number_format ($prosecutions, 0, "$decSep", "$thouSep");
                $acquitted = number_format ($acquitted, 0, "$decSep", "$thouSep");
                $dismissedbyprosecutor = number_format ($dismissedbyprosecutor, 0, "$decSep", "$thouSep");
                $dismissedbycourt = number_format ($dismissedbycourt, 0, "$decSep", "$thouSep");
                $cautioned = number_format ($cautioned, 0, "$decSep", "$thouSep");
                $fined = number_format ($fined, 0, "$decSep", "$thouSep");
                $prisonterm = number_format ($prisonterm, 0, "$decSep", "$thouSep");
                $communityservice = number_format ($communityservice, 0, "$decSep", "$thouSep");
                $other = number_format ($other, 0, "$decSep", "$thouSep");
                $prisondays = number_format ($prisondays, 0, "$decSep", "$thouSep");
                $communityservicedays = number_format ($communityservicedays, 0, "$decSep", "$thouSep");
                $fines = number_format ($fines, 0, "$decSep", "$thouSep");
                $report .= <<<NXT
<TR><TD class="tdsheader" colspan="2">
<B>$crime
</TD></TR>

<TR><TD class="tds">
Prosecutions</TD>
<TD class="tds" align="right">
$prosecutions
</TD></TR>

<TR><TD class="tds">
Successful prosecutions (%)</TD>
<TD class="tds" align="right">
$succesfulprosecutions
</TD></TR>

<TR><TD class="tds">
Acquittals</TD>
<TD class="tds" align="right">
$acquitted
</TD></TR>

<TR><TD class="tds">
Dismissed by prosecutor</TD>
<TD class="tds" align="right">
$dismissedbyprosecutor
</TD></TR>

<TR><TD class="tds">
Dismissed by court</TD>
<TD class="tds" align="right">
$dismissedbycourt
</TD></TR>

<TR><TD class="tds">
Cautions issued</TD>
<TD class="tds" align="right">
$cautioned
</TD></TR>

<TR><TD class="tds">
Fines issued</TD>
<TD class="tds" align="right">
$fined
</TD></TR>

<TR><TD class="tds">
Amount fined total (UGX)</TD>
<TD class="tds" align="right">
$fines
</TD></TR>

<TR><TD class="tds">
Prison term verdicts</TD>
<TD class="tds" align="right">
$prisonterm
</TD></TR>

<TR><TD class="tds">
Prison days total</TD>
<TD class="tds" align="right">
$prisondays
</TD></TR>

<TR><TD class="tds">
Community service verdicts</TD>
<TD class="tds" align="right">
$communityservice
</TD></TR>

<TR><TD class="tds">
Community service days total</TD>
<TD class="tds" align="right">
$communityservicedays
</TD></TR>

<TR><TD class="tds">
Other sentences</TD>
<TD class="tds" align="right">
$other
</TD></TR>

NXT;
                }

             }



/* ---------------------------------------------------------------- */



$report = <<<NXT
<TABLE cellspacing="0" cellpadding="0" border="1">
$report
</TABLE>

NXT;

/* ---------------------------------------------------------------- */


$report2 = "";

$succesfulprosecutions = round ((($totalprosecutions - $totalacquitted - $totaldismissedbyprosecutor - $totaldismissedbycourt) * 100) / $totalprosecutions, 1);

$CSV2 .= <<<NXT
Summary	$totalprosecutions	$succesfulprosecutions	$totalacquitted	$totaldismissedbyprosecutor	$totaldismissedbycourt	$totalcautioned	$totalfined	$totalfines	$totalprisonterm	$totalprisondays	$totalcommunityservice	$totalcommunityservicedays	$totalother

NXT;

if ($totalprosecutions > 0) $succesfulprosecutions = number_format ((($totalprosecutions - $totalacquitted - $totaldismissedbyprosecutor - $totaldismissedbycourt) * 100) / $totalprosecutions, 1, "$decSep", "$thouSep"); else $succesfulprosecutions = "0";
$totalprosecutions = number_format ($totalprosecutions, 0, "$decSep", "$thouSep");
$totalacquitted = number_format ($totalacquitted, 0, "$decSep", "$thouSep");
$totaldismissedbyprosecutor = number_format ($totaldismissedbyprosecutor, 0, "$decSep", "$thouSep");
$totaldismissedbycourt = number_format ($totaldismissedbycourt, 0, "$decSep", "$thouSep");
$totalcautioned = number_format ($totalcautioned, 0, "$decSep", "$thouSep");
$totalfined = number_format ($totalfined, 0, "$decSep", "$thouSep");
$totalprisonterm = number_format ($totalprisonterm, 0, "$decSep", "$thouSep");
$totalcommunityservice = number_format ($totalcommunityservice, 0, "$decSep", "$thouSep");
$totalother = number_format ($totalother, 0, "$decSep", "$thouSep");
$totalprisondays = number_format ($totalprisondays, 0, "$decSep", "$thouSep");
$totalcommunityservicedays = number_format ($totalcommunityservicedays, 0, "$decSep", "$thouSep");
$totalfines = number_format ($totalfines, 0, "$decSep", "$thouSep");

$report2 .= <<<NXT
<TR><TD class="tdsheader" colspan="2">
<B>Summary
</TD></TR>

<TR><TD class="tds">
Prosecutions</TD>
<TD class="tds" align="right">
$totalprosecutions
</TD></TR>

<TR><TD class="tds">
Succesful prosecutions (%)</TD>
<TD class="tds" align="right">
$succesfulprosecutions
</TD></TR>

<TR><TD class="tds">
Acquittals</TD>
<TD class="tds" align="right">
$totalacquitted
</TD></TR>

<TR><TD class="tds">
Dismissed by prosecutor</TD>
<TD class="tds" align="right">
$totaldismissedbyprosecutor
</TD></TR>

<TR><TD class="tds">
Dismissed by court</TD>
<TD class="tds" align="right">
$totaldismissedbycourt
</TD></TR>

<TR><TD class="tds">
Cautions issued</TD>
<TD class="tds" align="right">
$totalcautioned
</TD></TR>

<TR><TD class="tds">
Fines issued</TD>
<TD class="tds" align="right">
$totalfined
</TD></TR>

<TR><TD class="tds">
Amount fined total (UGX)</TD>
<TD class="tds" align="right">
$totalfines
</TD></TR>

<TR><TD class="tds">
Prison term verdicts</TD>
<TD class="tds" align="right">
$totalprisonterm
</TD></TR>

<TR><TD class="tds">
Prison days total</TD>
<TD class="tds" align="right">
$totalprisondays
</TD></TR>

<TR><TD class="tds">
Community service verdicts</TD>
<TD class="tds" align="right">
$totalcommunityservice
</TD></TR>

<TR><TD class="tds">
Community service days total</TD>
<TD class="tds" align="right">
$totalcommunityservicedays
</TD></TR>

<TR><TD class="tds">
Other sentences</TD>
<TD class="tds" align="right">
$totalother
</TD></TR>

NXT;



/* ---------------------------------------------------------------- */

$report2 = <<<NXT
<TABLE cellspacing="0" cellpadding="0" border="1">
$report2
</TABLE>

NXT;

/* ---------------------------------------------------------------- */

$CSV3 = _reverseRC ($CSV2);

/* ---------------------------------------------------------------- */

//$CSV = "$CSV\n$CSV2\n$CSV3";
$CSV = "$CSV3\n";

if ($_POST['separator'] == "<;>") $CSV = str_replace ("	", ";", $CSV);
if ($_POST['separator'] == "<,>") $CSV = str_replace ("	", ",", $CSV);
$CSV = str_replace ("\r", "", $CSV);
$CSV = str_replace ("\n", "\r\n", $CSV);


$scvfile = fopen ("$_docspath/casestats.csv", "w");
fwrite ($scvfile, $CSV);
fclose ($scvfile);

/* ---------------------------------------------------------------- */


?>

