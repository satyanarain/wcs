<?php 
@session_start();

$sqltable = "defendantstable";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
ASSTABLE VARCHAR(24),
ASSREC INT UNSIGNED,
DEFENDANT BIGINT UNSIGNED,
PROFILE BIGINT UNSIGNED,

CRIME VARCHAR(64),

VERDICTSTABLE BIGINT UNSIGNED
                                        )
CREATE;

$defendantstableTsql = new mySQLclass();
$defendantstableTsql->_sqlLookup ($query);
if ($defendantstableTsql->sql_result === false) { if ($defendantstableTsql->_sqlerrNo () != 1050) $defendantstableTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ();

$GLOBALS['requiredfields'][$sqltable] = array ('DEFENDANT');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['asstable']}', {$_POST['assrec']}, '', 0, '', '')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'ASSTABLE' => 'AUTO', 
                                                                            'ASSREC' => 'AUTO', 
                                                                            'DEFENDANT' => '_pickRecordId("suspects","");',
                                                                            'PROFILE' => 'SUSPECT_NAME',
                                                                            'CRIME' => '_selectCrime("NEW_CRIME_TYPE");',
                                                                            'VERDICTSTABLE' => 'UWA_CASEID'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'DEFENDANT' => 'Name',
                                                                      'PROFILE' => 'Profile', 
                                                                      'CRIME' => 'Crime',
                                                                      'VERDICTSTABLE' => 'Verdicts'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'DEFENDANT' => 'Nom', 
			'PROFILE' => 'Profil', 
			'CRIME' => 'la criminalité',
			'VERDICTSTABLE' => 'Verdicts'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'DEFENDANT' => 'Nombre',
			'PROFILE' => 'Perfil', 
			'CRIME' => 'Crimen',
			'VERDICTSTABLE' => 'Veredictos'
		);
		break;
}

																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#DEFENDANT#
#PROFILE#
#CRIME#
#VERDICTSTABLE#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#TABLE#DEFENDANT#suspects;SUSPECT_NAME;--;;;;#
#PROFILE#
#CRIME#
#VERDICTSTABLE#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

function _onInsertNew_defendantstable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'DEFENDANTSTABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);
}

/* ---------------------------------------------------------------- */

function _onEdit_defendantstable ($table, $rec, $asstable, $assrec)
{ _onInsertNew_defendantstable ($table, $rec, $asstable, $assrec);
   $nameId = _getField ($table, $rec, 'DEFENDANT', '');
   $crime = _getField ($table, $rec, 'CRIME', '');
   if (($nameId) && ($crime)) _setField ('suspects', 'OFFENCE', $nameId, $crime, $_POST['id']);
}

/* ---------------------------------------------------------------- */

function _onDelete_defendantstable ($table, $rec, $asstable, $assrec)
{ _onInsertNew_defendantstable ($table, $rec, $asstable, $assrec);
}

/* ---------------------------------------------------------------- */


?>

