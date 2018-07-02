<?php 
@session_start();

$sqltable = "cases";
$query = <<<CREATE
CREATE TABLE $sqltable (
	id int UNSIGNED primary key not null auto_increment,
	REGISTERED BIGINT UNSIGNED,
	DELETED BIGINT UNSIGNED,
	CHANGED BIGINT UNSIGNED,
	CHANGEDBY INT UNSIGNED,
	NOTES BIGINT UNSIGNED,
	DOCS BIGINT UNSIGNED,
	UWA_CASEID VARCHAR(24),
	POLICE_CASEID VARCHAR(24),
	POLICE_CASE_ID VARCHAR(24),
	CRIME_TYPE VARCHAR(48),
	COURTDATE BIGINT UNSIGNED,
	ENDEDDATE BIGINT UNSIGNED,
	CASESTATUS VARCHAR(2),
	UWA_PROSECUTOR VARCHAR(48),
	STATE_PROSECUTOR VARCHAR(48),
	DEFENCE_LAWYER VARCHAR(48),
	MAGISTRATE VARCHAR(48),
	DEFENDANTSTABLE BIGINT UNSIGNED
                                        )
CREATE;

$casesTsql = new mySQLclass();
$casesTsql->_sqlLookup ($query);
if ($casesTsql->sql_result === false) { if ($casesTsql->_sqlerrNo () != 1050) $casesTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$_mainportal_ = 'CasesAdministration';

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */


$GLOBALS['searchfields'][$sqltable] = array ( 'UWA_CASEID' => '%', 'POLICE_CASEID' => '%', 'UWA_PROSECUTOR' => '%', 'STATE_PROSECUTOR' => '%', 'MAGISTRATE' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ('POLICE_CASEID' => 24, 'CRIME_TYPE' => 0, 'UWA_PROSECUTOR' => 48, 'STATE_PROSECUTOR' => 48, 'DEFENCE_LAWYER' => 48, 'MAGISTRATE' => 48);

$GLOBALS['requiredfields'][$sqltable] = array ( 'UWA_CASEID', 'MAGISTRATE', 'DEFENDANTSTABLE', 'CRIME_TYPE');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', $timestamp, 0, $timestamp, '{$_POST['id']}', 0, 0, '{$_POST['NEW_UWA_CASEID']}', '{$_POST['NEW_POLICE_CASEID']}', '{$_POST['NEW_POLICE_CASE_ID']}', '{$_POST['NEW_CRIME_TYPE']}', '', '', 
'{$_POST['NEW_CASESTATUS']}', '{$_POST['NEW_UWA_PROSECUTOR']}', '{$_POST['NEW_STATE_PROSECUTOR']}', '{$_POST['NEW_DEFENCE_LAWYER']}', '{$_POST['NEW_MAGISTRATE']}', 0)
NXT;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'REGISTERED' => 'AUTO', 
                                                                            'DELETED' => 'AUTO', 
                                                                            'OWNERENTITY' => 'AUTO',
                                                                            'NOTES' => 'UWA_CASEID',
                                                                            'DOCS' => 'UWA_CASEID',
                                                                            'UWA_CASEID' => 'AUTO',
                                                                            'POLICE_CASEID' => 'EDIT',
																			'POLICE_CASE_ID' => 'EDIT',
                                                                            'CRIME_TYPE' => '_selectCrime("NEW_CRIME_TYPE");',
                                                                            'COURTDATE' => 'AUTO',
                                                                            'ENDEDDATE' => 'AUTO',
																			'CASESTATUS' => 'AUTO',
																			'UWA_PROSECUTOR' => 'EDIT',
                                                                            'STATE_PROSECUTOR' => 'EDIT',
                                                                            'DEFENCE_LAWYER' => 'EDIT',
                                                                            'MAGISTRATE' => 'EDIT',
                                                                            'DEFENDANTSTABLE' => 'UWA_CASEID'
                                                                         );


$GLOBALS['tablelabels'][$sqltable] = array ( 'NOTES' => 'Notes', 
                                                                      'DOCS' => 'Doc.s', 
                                                                      'UWA_CASEID' => 'UWA Case ID',
                                                                      'POLICE_CASEID' => 'COURT case no.',
																	  'POLICE_CASE_ID' => 'Police Case ID',
                                                                      'CRIME_TYPE' => 'Crime type',
                                                                      'COURTDATE' => 'Start date',
                                                                      'ENDEDDATE' => 'Finalized',
																	  'CASESTATUS' => 'Status',
                                                                      'UWA_PROSECUTOR' => 'UWA prosecutor',
                                                                      'STATE_PROSECUTOR' => 'State prosecutor',
                                                                      'DEFENCE_LAWYER' => 'Defense lawyer',
                                                                      'MAGISTRATE' => 'Judicial Officer',
                                                                      'DEFENDANTSTABLE' => 'Accused',
                                                                      'VERDICTSTABLE' => 'Verdicts'
                                                                   );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'NOTES' => 'Remarques', 
			'DOCS' => 'Docs', 
			'UWA_CASEID' => 'UWA Cas ID',
			'POLICE_CASEID' => 'Numéro du dossier de la cour',
			'POLICE_CASE_ID' => 'Cas POLICE ID',
			'CRIME_TYPE' => 'Type de crime',
			'COURTDATE' => 'Date de début', 
			'ENDEDDATE' => 'Finalisé',
			'CASESTATUS' => 'Statut',
			'UWA_PROSECUTOR' => 'UWA procureur',
			'STATE_PROSECUTOR' => 'Procureur de l État',
			'DEFENCE_LAWYER' => 'Avocat de la défense',
			'MAGISTRATE' => 'Fonctionnaire judiciaire',
			'DEFENDANTSTABLE' => 'Accusé',
			'VERDICTSTABLE' => 'Verdicts'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'NOTES' => 'Notas', 
			'DOCS' => 'Docs', 
			'UWA_CASEID' => 'UWA Caso ID',
			'POLICE_CASEID' => 'Número del caso judicial',
			'POLICE_CASE_ID' => 'Caso POLICE ID',
			'CRIME_TYPE' => 'Tipo de crimen',
			'COURTDATE' => 'Fecha de inicio',
			'ENDEDDATE' => 'Finalizado',
			'CASESTATUS' => 'Estado',
			'UWA_PROSECUTOR' => 'UWA fiscal',
			'STATE_PROSECUTOR' => 'Fiscal del Estado',
			'DEFENCE_LAWYER' => 'Abogado defensor',
			'MAGISTRATE' => 'Oficial Judicial',
			'DEFENDANTSTABLE' => 'Acusado',
			'VERDICTSTABLE' => 'Veredictos'
		);
		break;
}

																   
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SHOWREC#
#NOTES#
#DOCS#
#UWA_CASEID#
#POLICE_CASE_ID#
#CRIME_TYPE#
#COURTDATE#
#ENDEDDATE#
#CASESTATUS#
#DEFENDANTSTABLE#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#SHOWREC#
#NOTES#
#DOCS#
#UWA_CASEID#
#POLICE_CASE_ID#
#CRIME_TYPE#
#DATE#COURTDATE#
#DATE#ENDEDDATE#
#CASESTATUS#
#DEFENDANTSTABLE#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ('GENDER' => 'center');

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */


$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#NOTES#
#DOCS#
#UWA_CASEID#
#POLICE_CASEID#
#POLICE_CASE_ID#
#CRIME_TYPE#
#DATE#COURTDATE#
#UWA_PROSECUTOR#
#STATE_PROSECUTOR#
#DEFENCE_LAWYER#
#MAGISTRATE#
#DEFENDANTSTABLE#
NXT;

/* ---------------------------------------------------------------- */

function _onEdit_cases ($table, $rec, $asstable, $assrec)
{ 
}

function _onInsertNew_cases ($table, $rec, $asstable, $assrec)
{ $ent = _getUserentity ($_POST['id']);
   $date = date ('Y-m-d', $GLOBALS['timestamp']);
   $ser = 1;
   while (_checkSerial ($table, 'UWA_CASEID', "$ent-{$_POST['id']}-$date-$ser")) $ser++;
   $serial = "$ent-{$_POST['id']}-$date-$ser";
   _setField ($table, 'UWA_CASEID', $rec, $serial, $_POST['id']);
}

/* ---------------------------------------------------------------- */

?>

