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

$outpostsarray = explode ("~", $outposts);
$actiontypesarray  = explode ("~", $actiontypes);

$_evidence = explode ("~", $evidencetypes2);
array_pop ($_evidence);

$_species = explode ("~", $species);

$__species = array();

foreach ($_species as $spc)
            if ($spc)
              { $__species[] = "$spc No";
                 $__species[] = "$spc Kg";
              }

/* ---------------------------------------------------------------- */

$report = "";
$CSV = "";
$CSV2 = <<<NXT
Station	No. of arrests	No. of offenders	First time offenders	Second time offenders	Third+ time offenders	No. of charges
NXT;

$totalevidence = array ();

foreach ($_evidence as $someevidence) 
            { $totalevidence["$someevidence"] = 0;
               $CSV2 .= "\t$someevidence";
            }

$totalspec = array ();

foreach ($__species as $spc)
            { $totalspec["$spc"] = 0;
               $CSV2 .= "\t$spc";
            }

$CSV2 .= "\n";


$totalactions = array ();
$totaloffenders = array ();
$totalarrests = 0;
$totalcharged = array ();

$cases = new mySQLclass();
$cases2 = new mySQLclass();
$cases3 = new mySQLclass();
$cases4 = new mySQLclass();

function _sumarray (&$array)
{ $sum = 0;
   foreach ($array as $count) $sum += $count;
return $sum;
}

function _countLevel (&$array, $level, $shelf)
{ $count = 0;
   foreach ($array as $value) 
                 if ($shelf) 
                    { if ($value >= $level) $count++;
                    } else if ($value == $level) $count++;
return $count;
}


foreach ($outpostsarray as $post)
             { if ($fromdate >= $_todate) $query = "SELECT * FROM arrests WHERE OUTPOST = '$post' AND DELETED = 0";
                  else $query = "SELECT * FROM arrests WHERE (OUTPOST = '$post') AND (ARRESTDATE >= $fromdate) AND (ARRESTDATE <= $todate) AND DELETED = 0";
                $cases->_sqlLookup ($query);
                if ($cases->sql_result === false) $cases->_sqlerror (); 

                $evidence = array ();
                $spec = array ();
                foreach ($_evidence as $someevidence) $evidence["$someevidence"] = 0;
                foreach ($__species as $_spec) $spec["$_spec"] = 0;
                $actions = array ();
                $offenders = array ();
                $arrests = 0;
                $charged = array ();

                while ($row = mysql_fetch_assoc ($cases->sql_result))
                         { $query2 = "SELECT * FROM offenderstable WHERE ASSREC = {$row['id']} AND DELETED = 0";
                            $cases2->_sqlLookup ($query2);
                            if ($cases2->sql_result === false) $cases2->_sqlerror (); 

                            $query3 = "SELECT * FROM evidencetable WHERE ASSREC = {$row['id']} AND DELETED = 0";
                            $cases3->_sqlLookup ($query3);
                            if ($cases3->sql_result === false) $cases3->_sqlerror (); 

                            $countedoffenders = array ();
                            $countedtotaloffenders = array ();
                            while ($row2 = mysql_fetch_assoc ($cases2->sql_result))
                                     { if ((isset ($offenders["{$row2['OFFENDER']}"])) && (! isset ($countedoffenders["{$row2['OFFENDER']}"])))
                                           { $offenders["{$row2['OFFENDER']}"]++; 
                                              $countedoffenders["{$row2['OFFENDER']}"] = true;
                                           } else $offenders["{$row2['OFFENDER']}"] = 1;
                                        if ((isset ($totaloffenders["{$row2['OFFENDER']}"])) && (! isset ($countedtotaloffenders["{$row2['OFFENDER']}"])))
                                           { $totaloffenders["{$row2['OFFENDER']}"]++; 
                                              $countedtotaloffenders["{$row2['OFFENDER']}"] = true;
                                           } else $totaloffenders["{$row2['OFFENDER']}"] = 1;
                                        $arrests++;
                                        $totalarrests++;
                                        if ($row2['ACTION'] == 'Address mark') continue;
                                        if (isset ($actions["{$row2['ACTION']}"])) $actions["{$row2['ACTION']}"]++; else $actions["{$row2['ACTION']}"] = 1;
                                        if (isset ($totalactions["{$row2['ACTION']}"])) $totalactions["{$row2['ACTION']}"]++; else $totalactions["{$row2['ACTION']}"] = 1;
                                        if ($row2['ACTION'] == 'Charged')
                                           { if (isset ($charged["{$row2['OFFENDER']}"])) $charged["{$row2['OFFENDER']}"]++; else $charged["{$row2['OFFENDER']}"] = 1;
                                              if (isset ($totalcharged["{$row2['OFFENDER']}"])) $totalcharged["{$row2['OFFENDER']}"]++; else $totalcharged["{$row2['OFFENDER']}"] = 1;
                                           }
                                     }

                            while ($row3 = mysql_fetch_assoc ($cases3->sql_result))
                                     { if (in_array ($row3['EVIDENCE'], $_evidence)) 
                                           { if (isset ($evidence["{$row3['EVIDENCE']}"])) $evidence["{$row3['EVIDENCE']}"] += $row3['QTY']; else $evidence["{$row3['EVIDENCE']}"] = $row3['QTY'];
                                              if (isset ($totalevidence["{$row3['EVIDENCE']}"])) $totalevidence["{$row3['EVIDENCE']}"] += $row3['QTY']; else $totalevidence["{$row3['EVIDENCE']}"] = $row3['QTY'];
                                           }
                                        $thisitem = "{$row3['SPECIES']} {$row3['UNIT']}";
                                        if ((in_array ($thisitem, $spec)) && ($thisitem != " No") && ($thisitem != " Kg"))
                                           { $spec["$thisitem"] += $row3['QTY']; 
                                              $totalspec["$thisitem"] += $row3['QTY'];
                                           }
                                     }
                         }
                $noofoffenders = count ($offenders);
                $twotimesoffenders = 0;
                $threeplustimesoffenders = 0;
                $onetimeoffenders = $noofoffenders - ($twotimesoffenders + $threeplustimesoffenders);
                foreach ($offenders as $offndkey => $offnd)
                             { $query4 = "SELECT COUNT(OFFENDER) FROM offenderstable WHERE OFFENDER = $offndkey AND DELETED = 0";
                                $cases4->_sqlLookup ($query4);
                                if ($cases4->sql_result === false) $cases4->_sqlerror (); 
                                $row4 = mysql_fetch_assoc ($cases4->sql_result);
                                if ($row4['COUNT(OFFENDER)'] == 2) $twotimesoffenders++;
                                if ($row4['COUNT(OFFENDER)'] >= 3) $threeplustimesoffenders++;
                             }
                $onetimeoffenders = $noofoffenders - ($twotimesoffenders + $threeplustimesoffenders);
                $noofcharges = _sumarray ($charged);
                

/*
                if ($noofoffenders) $CSV .= <<<NXT
Station:	$post
No. of arrests	$arrests
No. of offenders	$noofoffenders
First time offenders	$onetimeoffenders
Second time offenders	$twotimesoffenders
Third+ time offenders	$threeplustimesoffenders
No. of charges	$noofcharges


NXT;
*/
                if ($noofoffenders) 
                  { $CSV2 .= <<<NXT
$post	$arrests	$noofoffenders	$onetimeoffenders	$twotimesoffenders	$threeplustimesoffenders	$noofcharges
NXT;
                foreach ($evidence as $evd2) $CSV2 .= "\t$evd2";
                foreach ($spec as $specrep) $CSV2 .= "\t$specrep";
                $CSV2 .= "\n";
                  }
                if ($noofoffenders) $report .= <<<NXT
<TR><TD class="tdsheader" colspan="2">
<B>Station: $post
</TD></TR>

<TR><TD class="tds">
No. of arrests</TD>
<TD class="tds" align="right">
$arrests
</TD></TR>

<TR><TD class="tds">
No. of offenders</TD>
<TD class="tds" align="right">
$noofoffenders
</TD></TR>

<TR><TD class="tds">
First time offenders</TD>
<TD class="tds" align="right">
$onetimeoffenders
</TD></TR>

<TR><TD class="tds">
Second time offenders</TD>
<TD class="tds" align="right">
$twotimesoffenders
</TD></TR>

<TR><TD class="tds">
Third+ time offenders</TD>
<TD class="tds" align="right">
$threeplustimesoffenders
</TD></TR>

<TR><TD class="tds">
No. of charges</TD>
<TD class="tds" align="right">
$noofcharges
</TD></TR>

NXT;

                if ((_sumarray ($evidence) > 0) || (_sumarray ($spec) > 0)) $report .= <<<NXT
<TR><TD class="tdsheader2">
Evidence impounded
</TD><TD>&nbsp;
</TD></TR>

NXT;

                foreach ($evidence as $evk => $ev)
                             if ($ev)
                               { /*
$CSV .= <<<NXT
$evk	$ev


NXT;
*/
                                     $report .= <<<NXT
<TR><TD class="tds">
$evk</TD>
<TD class="tds" align="right">
$ev
</TD></TR>

NXT;
                                  $CSV .= "\n";
                               } 

                foreach ($spec as $_spec => $__spec)
                             if ($__spec)
                               {$report .= <<<NXT
<TR><TD class="tds">
$_spec</TD>
<TD class="tds" align="right">
$__spec
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

$totalnoofoffenders = count ($totaloffenders);
$totaltwotimesoffenders = 0;
$totalthreeplustimesoffenders = 0;
$onetimeoffenders = $noofoffenders - ($twotimesoffenders + $threeplustimesoffenders);
foreach ($totaloffenders as $offndkey => $offnd)
             { $query4 = "SELECT COUNT(OFFENDER) FROM offenderstable WHERE OFFENDER = $offndkey AND DELETED = 0";
                $cases4->_sqlLookup ($query4);
                if ($cases4->sql_result === false) $cases4->_sqlerror (); 
                $row4 = mysql_fetch_assoc ($cases4->sql_result);
                if ($row4['COUNT(OFFENDER)'] == 2) $totaltwotimesoffenders++;
                if ($row4['COUNT(OFFENDER)'] >= 3) $totalthreeplustimesoffenders++;
             }
$totalonetimeoffenders = $totalnoofoffenders - ($totaltwotimesoffenders + $totalthreeplustimesoffenders);
$totalnoofcharges = _sumarray ($totalcharged);

$report2 = "";

/*
$CSV .= <<<NXT
Station:	All stations (totals f. period)
No. of arrests	$totalarrests
No. of offenders	$totalnoofoffenders
No. of charges	$totalnoofcharges
First time offenders	$totalonetimeoffenders
Second time offenders	$totaltwotimesoffenders
Third+ time offenders	$totalthreeplustimesoffenders

NXT;
*/

$CSV2 .= <<<NXT
All stations	$totalarrests	$totalnoofoffenders	$totalonetimeoffenders	$totaltwotimesoffenders	$totalthreeplustimesoffenders	$totalnoofcharges
NXT;
foreach ($totalevidence as $evd2) $CSV2 .= "\t$evd2";
foreach ($totalspec as $specrep) $CSV2 .= "\t$specrep";
$CSV2 .= "\n";


$report2 .= <<<NXT
<TR><TD class="tdsheader" colspan="2">
<B>Totals: All stations
</TD></TR>

NXT;

$report2 .= <<<NXT
<TR><TD class="tds">
No. of arrests</TD>
<TD class="tds" align="right">
$totalarrests
</TD></TR>

<TR><TD class="tds">
No. of offenders</TD>
<TD class="tds" align="right">
$totalnoofoffenders
</TD></TR>

<TR><TD class="tds">
No. of first time offenders</TD>
<TD class="tds" align="right">
$totalonetimeoffenders
</TD></TR>

<TR><TD class="tds">
No. of two time offenders</TD>
<TD class="tds" align="right">
$totaltwotimesoffenders
</TD></TR>

<TR><TD class="tds">
No. of 3+ time offenders</TD>
<TD class="tds" align="right">
$totalthreeplustimesoffenders
</TD></TR>

<TR><TD class="tds">
No. of charges</TD>
<TD class="tds" align="right">
$totalnoofcharges
</TD></TR>

NXT;

if (_sumarray ($totalevidence) > 0) $report2 .= <<<NXT
<TR><TD class="tdsheader2">
Evidence impounded
</TD><TD>&nbsp;
</TD></TR>

NXT;
foreach ($totalevidence as $evdkey => $evd2) 
            if ($evd2) $report2 .= <<<NXT
<TR><TD class="tds">
$evdkey</TD>
<TD class="tds" align="right">
$evd2
</TD></TR>

NXT;

foreach ($totalspec as $_spec => $__spec)
                             if ($__spec)
                               {$report2 .= <<<NXT
<TR><TD class="tds">
$_spec</TD>
<TD class="tds" align="right">
$__spec
</TD></TR>

NXT;
                               } 

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

$scvfile = fopen ("$_docspath/arrestsstats.csv", "w");
fwrite ($scvfile, $CSV);
fclose ($scvfile);

/* ---------------------------------------------------------------- */


?>

