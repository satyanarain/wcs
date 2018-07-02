<?php 
@session_start();

$sqltable = "verdictstable";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
ASSTABLE VARCHAR(24),
ASSREC INT UNSIGNED,

UWA_CASEID VARCHAR(24),

COURT_DATE BIGINT UNSIGNED,
COURT_NAME	VARCHAR(48),

VERDICT	VARCHAR(64),
SPEC	VARCHAR(64),
QTY INT UNSIGNED,
UNIT VARCHAR(8),
APPEALED	VARCHAR(4),
MOVED	VARCHAR(4),
COURT_TO	VARCHAR(48),
MOVED_DATE BIGINT UNSIGNED,
SENTENCETABLE BIGINT UNSIGNED

                                        )
CREATE;

$verdictstableTsql = new mySQLclass();
$verdictstableTsql->_sqlLookup ($query);
if ($verdictstableTsql->sql_result === false) { if ($verdictstableTsql->_sqlerrNo () != 1050) $verdictstableTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ('COURT_NAME' => 0, 'VERDICT' => 0, 'SPEC' => 48, 'QTY' => 8, 'UNIT' => 0, 'APPEALED' => 0);

$GLOBALS['requiredfields'][$sqltable] = array ('COURT_DATE', 'COURT_NAME', 'VERDICT');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['asstable']}', {$_POST['assrec']}, '', '', '{$_POST['NEW_COURT_NAME']}', '{$_POST['NEW_VERDICT_NAME']}', '{$_POST['NEW_SPEC']}', '{$_POST['NEW_QTY']}', '{$_POST['NEW_UNIT']}', '{$_POST['NEW_APPEALED']}', '', '', '', '')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'ASSTABLE' => 'AUTO', 
                                                                            'ASSREC' => 'AUTO', 
                                                                            'UWA_CASEID' => 'AUTO',
                                                                            'COURT_DATE' => '_pickDate("Court date", "UWA_CASEID");',
                                                                            'COURT_NAME' => '_selectCourt ("NEW_COURT_NAME");',
                                                                            'VERDICT' => '_selectVerdict("NEW_VERDICT_NAME");',
                                                                            'SPEC' => 'EDIT',
                                                                            'QTY' => '_int(1, "", 120);',
                                                                            'UNIT' => '_select("NEW_UNIT", "Days~Weeks~Months~Years~UGX");',
                                                                            'APPEALED' => '_selectChkMulti("NEW_APPEALED", "yes");',
																			'MOVED' => '_selectChkMulti("NEW_MOVED", "yes");',
																			'COURT_TO' => '_selectCourt1 ("NEW_COURT_TO");',
																			'MOVED_DATE' => '_pickDate("Moved Date", "UWA_CASEID");',
																			'SENTENCETABLE' => 'UWA_CASEID'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'UWA_CASEID' => 'UWA Case ID',
                                                                      'COURT_DATE' => 'Court Date',
                                                                      'COURT_NAME' => ' Court',
                                                                      'VERDICT' => 'Verdict',
                                                                      'SPEC' => 'Specify',
                                                                      'QTY' => 'qty',
                                                                      'UNIT' => 'Units',
                                                                      'APPEALED' => 'Appealed',
																	  'MOVED' => 'Moved',
																	  'COURT_TO' => 'New Court',
																	  'MOVED_DATE' => 'Moved Date',
																	  'SENTENCETABLE' => 'Sentences'
                                                                    );


switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'UWA_CASEID' => 'UWA Cas ID',
			'COURT_DATE' => 'Comparution',
			'COURT_NAME' => 'Nom du tribunal',
			'VERDICT' => 'Verdict',
			'SPEC' => 'Spécifier',
			'QTY' => 'Quantité',
			'UNIT' => 'Unité',
			'APPEALED' => 'Appelé',
			'MOVED' => 'Déplacé',
			'COURT_TO' => 'Nouvelle Cour',
			'MOVED_DATE' => 'Date de déménagement',
			'SENTENCETABLE' => 'Peine'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'UWA_CASEID' => 'UWA Caso ID',
			'COURT_DATE' => 'Fecha de corte',
			'COURT_NAME' => 'Nombre del Tribunal',
			'VERDICT' => 'Veredicto',
			'SPEC' => 'Especificar',
			'QTY' => 'Cantidad',
			'UNIT' => 'Unidad',
			'APPEALED' => 'Apelado',
			'MOVED' => 'Movido',
			'COURT_TO' => 'Nueva corte',
			'MOVED_DATE' => 'Fecha de mudanza',
			'SENTENCETABLE' => 'Frases'
		);
		break;
}

																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SHOWREC#
#COURT_DATE#
#COURT_NAME#
#VERDICT#
#SPEC#
#QTY#
#UNIT#
#APPEALED#
#MOVED#
#COURT_TO#
#MOVED_DATE#
#SENTENCETABLE#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#SHOWREC#
#DATE#COURT_DATE#
#COURT_NAME#
#VERDICT#
#SPEC#
#QTY#
#UNIT#
#APPEALED#
#MOVED#
#COURT_TO#
#DATE#MOVED_DATE#
#SENTENCETABLE#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

function _onInsertNew_verdictstable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'VERDICTSTABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);

      $inssql = new mySQLclass();
      $query = "SELECT * FROM $asstable WHERE id = $assrec";
      $inssql->_sqlLookup ($query);
      if ($inssql->sql_result === false) $inssql->_sqlerror ();
      $row = mysql_fetch_assoc ($inssql->sql_result);
      $asst = $row['ASSTABLE'];
      $assr = $row['ASSREC'];

      $query = "SELECT * FROM $asst WHERE id = $assr";
      $inssql->_sqlLookup ($query);
      if ($inssql->sql_result === false) $inssql->_sqlerror ();
      $row = mysql_fetch_assoc ($inssql->sql_result);
      $asst = $row['ASSTABLE'];
      $assr = $row['ASSREC'];
      $caseNo = $row['UWA_CASEID'];

_setField ($table, 'UWA_CASEID', $rec, $caseNo, $_POST['id']);
}

/* ---------------------------------------------------------------- */

function _onEdit_verdictstable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'VERDICTSTABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);

//$deb = fopen ("tmp.txt", "w");

   $edsql = new mySQLclass();

   $query = "SELECT * FROM $table WHERE id = $rec";
   $edsql->_sqlLookup ($query);
   if ($edsql->sql_result === false) $edsql->_sqlerror ();
   $row = mysql_fetch_assoc ($edsql->sql_result);
   $caseId = $row['UWA_CASEID'];
   $asst = $row['ASSTABLE'];
   $assr = $row['ASSREC'];

//fwrite ($deb, "IcId: $caseId \n");
   $query = "SELECT * FROM $asst WHERE id = $assr";
   $edsql->_sqlLookup ($query);
   if ($edsql->sql_result === false) $edsql->_sqlerror ();
   $row = mysql_fetch_assoc ($edsql->sql_result);
   $asst = $row['ASSTABLE'];
   $assr = $row['ASSREC'];

   $query = "SELECT * FROM $table WHERE UWA_CASEID = '$caseId'";
   $edsql->_sqlLookup ($query);
   if ($edsql->sql_result === false) $edsql->_sqlerror ();

   $startdate = 999999999999;
   $enddate = 0;
   $stillopen = false;
   $defs = array ();

   while ($row = mysql_fetch_assoc ($edsql->sql_result))
            { $datetmp = $row['COURT_DATE'];
               $recid = "R_{$row['ASSREC']}";
               if (! isset (${$recid})) 
                 { ${$recid} = array ('startdate' => 999999999999, 'enddate' => 0, 'pending' => 0);
                    array_push ($defs, $recid);
//fwrite ($deb, "{$row['ASSTABLE']} / {$row['ASSREC']} � $datetmp / $startdate / $enddate / $pending\n");
                 }
               if (($datetmp) && ($datetmp < ${$recid}['startdate'])) ${$recid}['startdate'] = $datetmp;
               if (($row['VERDICT'] != 'Pending') && (! $row['APPEALED']) && ($datetmp > ${$recid}['enddate'])) ${$recid}['enddate'] = $datetmp;
               if ((($row['VERDICT'] == 'Pending') || ($row['APPEALED'])) && ($datetmp >= ${$recid}['enddate'])) ${$recid}['pending'] = $datetmp; else ${$recid}['pending'] = 0;
            }
   foreach ($defs as $rid)
                { if (${$rid}['startdate'] < $startdate) $startdate = ${$rid}['startdate'];
                   if (${$rid}['enddate'] > $enddate) $enddate = ${$rid}['enddate'];
                   if (${$rid}['pending'] >= $enddate) $stillopen = true;
//fwrite ($deb, "RECid = $rid � {${$rid}['startdate']} / {${$rid}['enddate']} / {${$rid}['pending']}\n");
                }

/*
$dd = print_r ($defs, true);
fwrite ($deb, $dd);
fwrite ($deb, "\n$startdate / $enddate / $stillopen\n");
*/

   if ($startdate != 999999999999)  _setField ($asst, 'COURTDATE', $assr, $startdate, $_POST['id']);
//fwrite ($deb, "_setField ($asst, 'COURTDATE', $assr, $startdate, {$_POST['id']});\n");
   if (! $stillopen)  _setField ($asst, 'ENDEDDATE', $assr, $enddate, $_POST['id']); else _setField ($asst, 'ENDEDDATE', $assr, '', $_POST['id']);
//fwrite ($deb, "_setField ($asst, 'ENDEDDATE', $assr, $enddate, {$_POST['id']}); else _setField ($asst, 'ENDEDDATE', $assr, '', {$_POST['id']});\n");

//fclose ($deb);
}

/* ---------------------------------------------------------------- */

function _onDelete_verdictstable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'VERDICTSTABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);
}

/* ---------------------------------------------------------------- */


?>

