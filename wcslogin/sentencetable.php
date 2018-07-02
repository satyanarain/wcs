<?php 
@session_start();

$sqltable = "sentencetable";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
ASSTABLE VARCHAR(24),
ASSREC INT UNSIGNED,

UWA_CASEID VARCHAR(24),

OPT1 VARCHAR(48),
QTY1 INT UNSIGNED,
UNIT1 VARCHAR(8),
REM1 VARCHAR(48),
OPT2 VARCHAR(48),
QTY2 INT UNSIGNED,
UNIT2 VARCHAR(8),
REM2 VARCHAR(48),
OPTED VARCHAR(8),
OPTEDREM VARCHAR(48),
PRISONNAME VARCHAR(48),
PRISONSTART BIGINT UNSIGNED,
PRISONEND BIGINT UNSIGNED
                                        )
CREATE;

$sentencetableTsql = new mySQLclass();
$sentencetableTsql->_sqlLookup ($query);
if ($sentencetableTsql->sql_result === false) { if ($sentencetableTsql->_sqlerrNo () != 1050) $sentencetableTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ('OPT1' => 0, 'QTY1' => 48, 'UNIT1' => 0, 'REM1' => 48, 'OPT2' => 0, 'QTY2' => 48, 'UNIT2' => 0, 'REM2' => 48);

$GLOBALS['requiredfields'][$sqltable] = array ('OPT1', 'QTY1', 'UNIT1');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['asstable']}', {$_POST['assrec']}, '', '{$_POST['NEW_OPT1']}', '{$_POST['NEW_QTY1']}', '{$_POST['NEW_UNIT1']}', '{$_POST['NEW_REM1']}', '{$_POST['NEW_OPT2']}', '{$_POST['NEW_QTY2']}', '{$_POST['NEW_UNIT2']}', '{$_POST['NEW_REM2']}', '', '', '', '', '')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'ASSTABLE' => 'AUTO', 
                                                                            'ASSREC' => 'AUTO', 
                                                                            'UWA_CASEID' => 'AUTO',
                                                                            'OPT1' => '_selectVerdict1("NEW_OPT1");',
																			'QTY1' => '_int(1, "", 120);',
																			'UNIT1' => '_select("NEW_UNIT1", "Days~Weeks~Months~Years~UGX");',
                                                                            'REM1' => 'EDIT',
                                                                            'OPT2' => '_selectVerdict2("NEW_OPT2");',
																			'QTY2' => '_int(1, "", 120);',
																			'UNIT2' => '_select("NEW_UNIT2", "Days~Weeks~Months~Years~UGX");',
                                                                            'REM2' => 'EDIT',
																			'OPTED' => '_select("NEW_OPTED", "Option1~Option2~Both");',
                                                                            'OPTEDREM' => 'EDIT',
																			'PRISONNAME' => 'EDIT',
																			'PRISONSTART' => '_pickDate("Prison Start Date", "UWA_CASEID");',
																			'PRISONEND' => '_pickDate("Release Date", "UWA_CASEID");'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'UWA_CASEID' => 'UWA Case ID',
                                                                            'OPT1' => 'Opt 1',
																			'QTY1' => 'Qty 1',
																			'UNIT1' => 'Unit 1',
                                                                            'REM1' => 'Remarks 1',
                                                                            'OPT2' => 'Opt 2',
																			'QTY2' => 'Qty 2',
																			'UNIT2' => 'Unit 2',
                                                                            'REM2' => 'Remarks 2',
																			'OPTED' => 'Opted',
                                                                            'OPTEDREM' => 'Specify',
																			'PRISONNAME' => 'Prison Name',
																			'PRISONSTART' => 'Start Date',
																			'PRISONEND' => 'Release Date'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'UWA_CASEID' => 'UWA Cas ID',
			'OPT1' => 'Option 1',
			'QTY1' => 'Quantité 1',
			'UNIT1' => 'Unité 1',
			'REM1' => 'Remarques 1',
			'OPT2' => 'Option 2',
			'QTY2' => 'Quantité 2',
			'UNIT2' => 'Unité 2',
			'REM2' => 'Remarques 2',
			'OPTED' => 'Opté',
			'OPTEDREM' => 'Spécifier',
			'PRISONNAME' => 'Prison Name',
			'PRISONSTART' => 'Date de début',
			'PRISONEND' => 'Date de sortie'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'UWA_CASEID' => 'UWA Caso ID',
			'OPT1' => 'Opción 1',
			'QTY1' => 'Cantidad 1',
			'UNIT1' => 'Unidad 1',
			'REM1' => 'Observaciones 1',
			'OPT2' => 'Opción 2',
			'QTY2' => 'Cantidad 2',
			'UNIT2' => 'Unidad 2',
			'REM2' => 'Observaciones 2',
			'OPTED' => 'Optado',
			'OPTEDREM' => 'Especificar',
			'PRISONNAME' => 'Nombre de la prisión',
			'PRISONSTART' => 'Fecha de inicio',
			'PRISONEND' => 'Fecha de lanzamiento'
		);
		break;
}
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SHOWREC#
#OPT1#
#QTY1#
#UNIT1#
#REM1#
#OPT2#
#QTY2#
#UNIT2#
#REM2#
#OPTED#
#OPTEDREM#
#PRISONNAME#
#PRISONSTART#
#PRISONEND#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#SHOWREC#
#OPT1#
#QTY1#
#UNIT1#
#REM1#
#OPT2#
#QTY2#
#UNIT2#
#REM2#
#OPTED#
#OPTEDREM#
#PRISONNAME#
#DATE#PRISONSTART#
#DATE#PRISONEND#
NXT;

$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#OPT1#
#QTY1#
#UNIT1#
#REM1#
#OPT2#
#QTY2#
#UNIT2#
#REM2#
#OPTED#
#OPTEDREM#
#PRISONNAME#
#DATE#PRISONSTART#
#DATE#PRISONEND#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

function _onInsertNew_sentencetable ($table, $rec, $asstable, $assrec)
{_setField ($asstable, 'SENTENCETABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);
      $inssql = new mySQLclass();
      $query = "SELECT * FROM $asstable WHERE id = $assrec";
      $inssql->_sqlLookup ($query);
      if ($inssql->sql_result === false) $inssql->_sqlerror ();
      $row = mysql_fetch_assoc ($inssql->sql_result);
      $caseNo = $row['UWA_CASEID'];
_setField ($table, 'UWA_CASEID', $rec, $caseNo, $_POST['id']);
}

/* ---------------------------------------------------------------- */

function _onEdit_sentencetable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'SENTENCETABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);
}

/* ---------------------------------------------------------------- */

function _onDelete_sentencetable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'SENTENCETABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);
}

/* ---------------------------------------------------------------- */


?>

