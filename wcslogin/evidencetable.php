<?php 
@session_start();

$sqltable = "evidencetable";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
ASSTABLE VARCHAR(24),
ASSREC INT UNSIGNED,

EVIDENCE VARCHAR(64),
SPECIES VARCHAR(48),
LABEL VARCHAR(48),
QTY INT UNSIGNED,
EST_VALUE INT UNSIGNED,
UNIT VARCHAR(8),
EVIDENCE_NO VARCHAR(24),
STATUS VARCHAR(24),
LOCATION VARCHAR(40),
TRANS_DATE INT UNSIGNED,
TRANS_TO VARCHAR(40)
                                        )
CREATE;

$evidencetableTsql = new mySQLclass();
$evidencetableTsql->_sqlLookup ($query);
if ($evidencetableTsql->sql_result === false) { if ($evidencetableTsql->_sqlerrNo () != 1050) $evidencetableTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ();

$GLOBALS['requiredfields'][$sqltable] = array ('EVIDENCE', 'QTY', 'EVIDENCE_NO', 'STATUS', 'UNIT', 'LOCATION');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['asstable']}', {$_POST['assrec']}, '', '', '', '', 0, '', '{$_POST['NEW_EVIDENCE_NO']}', '', '', '', '')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'ASSTABLE' => 'AUTO', 
                                                                            'ASSREC' => 'AUTO', 
                                                                            'EVIDENCE' => '_select("NEW_EVIDENCE", "'.$evidencetypes.'");',
                                                                            'SPECIES' => '_selectSpecies("NEW_SPECIES_NAME");',
                                                                            'LABEL' => 'EDIT',
                                                                            'QTY' => '_int(1, "", 3);',
                                                                            'EST_VALUE' => '_int(1, "", 12);',
                                                                            'UNIT' => '_select("NEW_UNIT", "No~Ha~Kg");',
                                                                            'EVIDENCE_NO' => 'AUTO',
                                                                            'STATUS' => '_select("NEW_STATUS", "Impounded~Destroyed~Returned~Auctioned~-other-");',
                                                                            'LOCATION' => '_select("NEW_LOCATION", "'.$evidencelocations.'");',
																			'TRANS_DATE' => '_pickDate("Transfer Date", "UWA_CASEID");',
																			'TRANS_TO' => 'EDIT'
                                                                          );
// OBJECTTYPE list duplicated in 'new.record.php' !!!

$GLOBALS['tablelabels'][$sqltable] = array ( 'EVIDENCE' => 'Item / evidence',
                                                                      'SPECIES' => 'Species',
                                                                      'LABEL' => 'Serial / Spec.',
                                                                      'QTY' => 'qty',
                                                                      'EST_VALUE' => 'Value',
                                                                      'UNIT' => 'Units',
                                                                      'EVIDENCE_NO' => 'Evidence No.',
                                                                      'STATUS' => 'Status',
                                                                      'LOCATION' => 'Location',
																	  'TRANS_DATE' => 'Transfer Date',
																	  'TRANS_TO' => 'Transferred To'
                                                                    );

/*																	
switch ($_SESSION['selLang']) {
	case "FR":
		break;
	case "ES":
		break;
}
*/
switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'EVIDENCE' => 'Item / Preuve',
			'SPECIES' => 'Espèce',
			'LABEL' => 'En série / spécification',
			'QTY' => 'Quantité',
			'EST_VALUE' => 'Valeur',
			'UNIT' => 'Unité',
			'EVIDENCE_NO' => 'Numéro de preuve',
			'STATUS' => 'Statut',
			'LOCATION' => 'Emplacement',
			'TRANS_DATE' => 'Date de transfert',
			'TRANS_TO' => 'Transféré à'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'EVIDENCE' => 'Item / Evidencia',
			'SPECIES' => 'Especies',
			'LABEL' => 'De serie / Especificación',
			'QTY' => 'Cantidad',
			'EST_VALUE' => 'Valor',
			'UNIT' => 'Unidades',
			'EVIDENCE_NO' => 'Número de evidencia',
			'STATUS' => 'Estado',
			'LOCATION' => 'Estación',
			'TRANS_DATE' => 'Fecha de transferencia',
			'TRANS_TO' => 'Transferido a'
		);
		break;
}

																	
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#EVIDENCE_NO#
#EVIDENCE#
#SPECIES#
#LABEL#
#QTY#
#UNIT#
#EST_VALUE#
#STATUS#
#LOCATION#
#DATE#TRANS_DATE#
#TRANS_TO#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#EVIDENCE_NO#
#EVIDENCE#
#SPECIES#
#LABEL#
#QTY#
#UNIT#
#EST_VALUE#
#STATUS#
#LOCATION#
#DATE#TRANS_DATE#
#TRANS_TO#
NXT;

$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#EVIDENCE_NO#
#EVIDENCE#
#SPECIES#
#LABEL#
#QTY#
#UNIT#
#EST_VALUE#
#STATUS#
#LOCATION#
#DATE#TRANS_DATE#
#TRANS_TO#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

function _onInsertNew_evidencetable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'EVIDENCETABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);
   $serial = _getField ($table, $rec, 'EVIDENCE_NO', '');
   if (! $serial) 
      { $ser = _getField ($asstable, $assrec, 'UWA_CASEID', '');
         $ser2 = 1;
         while (_checkSerial ($table, 'EVIDENCE_NO', "$ser-$ser2")) $ser2++;
         $serial = "$ser-$ser2";
         _setField ($table, 'EVIDENCE_NO', $rec, $serial, $_POST['id']);
      }
}

/* ---------------------------------------------------------------- */

function _onEdit_evidencetable ($table, $rec, $asstable, $assrec)
{ _onInsertNew_evidencetable ($table, $rec, $asstable, $assrec);
   $newrec = new mySQLclass();
   $query = "SELECT * FROM $table WHERE id = '$rec'";
   $newrec->_sqlLookup ($query);
   if ($newrec->sql_result === false) 
      {$newrec->_sqlerror (); 
      }
   if ($row = mysql_fetch_assoc ($newrec->sql_result))
      { if ($row['STATUS'] == 'Returned') _setField ($table, 'LOCATION', $rec, 'N/A', $_POST['id']);
      }
}

/* ---------------------------------------------------------------- */

function _onDelete_evidencetable ($table, $rec, $asstable, $assrec)
{ _onInsertNew_evidencetable ($table, $rec, $asstable, $assrec);
}

/* ---------------------------------------------------------------- */


?>

