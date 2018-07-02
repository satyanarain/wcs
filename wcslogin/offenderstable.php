<?php 
@session_start();

$sqltable = "offenderstable";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
ASSTABLE VARCHAR(24),
ASSREC INT UNSIGNED,

UWA_CASEID VARCHAR(24),

OFFENDER BIGINT UNSIGNED,
PROFILE BIGINT UNSIGNED,

PARISH BIGINT UNSIGNED,
HOME_LC1_NAME VARCHAR(46),
HOME_LAT VARCHAR(24),
HOME_LON VARCHAR(24),
UTM_Z VARCHAR(6),
DATUM VARCHAR(6),

ACTION	VARCHAR(64),
INFORMANT	VARCHAR(3)
                                        )
CREATE;

$offenderstableTsql = new mySQLclass();
$offenderstableTsql->_sqlLookup ($query);
if ($offenderstableTsql->sql_result === false) { if ($offenderstableTsql->_sqlerrNo () != 1050) $offenderstableTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ();

$GLOBALS['requiredfields'][$sqltable] = array ('OFFENDER', 'ACTION', 'PARISH', 'HOME_LC1_NAME');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['asstable']}', {$_POST['assrec']}, '', '', '', '', '', '', '', '', '', '', '')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'ASSTABLE' => 'AUTO', 
                                                                            'ASSREC' => 'AUTO', 

                                                                            'UWA_CASEID' => 'AUTO',

                                                                            'OFFENDER' => '_pickRecordId("suspects","");',

                                                                            'PROFILE' => 'SUSPECT_NAME',

                                                                            'PARISH' => '_pickRecordId("parishes","");',
                                                                            'HOME_LC1_NAME' => 'EDIT',
                                                                            'HOME_LAT' => 'EDIT',
                                                                            'HOME_LON' => 'EDIT',
                                                                            'UTM_Z' => '_select("NEW_UTM_Z", "UTM35N~UTM35S~UTM36N~UTM36S~N/A");',
                                                                            'DATUM' => '_select("NEW_DATUM", "WGS84~ARC1960");',

                                                                            'ACTION' => '_select("NEW_EVIDENCE", "'.$actiontypes.'");',
                                                                            'INFORMANT' => '_selectChkMulti("NEW_PARTICIPANTS", "yes");'
                                                                          );
// OBJECTTYPE list duplicated in 'new.record.php' !!!

$GLOBALS['tablelabels'][$sqltable] = array ( 'UWA_CASEID' => 'UWA Arrest ID',
                                                                      'OFFENDER' => 'Offender',

                                                                      'PROFILE' => 'Profile', 

                                                                      'HOME_LC1_NAME' => 'Village (LC1)',
                                                                      'HOME_LAT' => 'Home Lat.',
                                                                      'HOME_LON' => 'Home Lon.',
                                                                      'UTM_Z' => 'UTMz',
                                                                      'DATUM' => 'Datum',

                                                                      'PARISH' => 'Parish',

                                                                      'ACTION' => 'Action taken',
                                                                      'INFORMANT' => 'Informant'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'UWA_CASEID' => 'UWA Arrêter ID',
			'OFFENDER' => 'Délinquant',
			'PROFILE' => 'Profil', 
			'HOME_LC1_NAME' => 'Village (LC1)',
			'HOME_LAT' => 'Accueil Lat.',
			'HOME_LON' => 'Accueil Lon.',
			'UTM_Z' => 'UTMz',
			'DATUM' => 'Données',
			'PARISH' => 'Paroisse',
			'ACTION' => 'Action prise',
			'INFORMANT' => 'Informateur'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'UWA_CASEID' => 'UWA Arrestar ID',
			'OFFENDER' => 'Delincuente',
			'PROFILE' => 'Perfil', 
			'HOME_LC1_NAME' => 'Pueblo (LC1)',
			'HOME_LAT' => 'Casa Lat.',
			'HOME_LON' => 'Casa Lon.',
			'UTM_Z' => 'UTMz',
			'DATUM' => 'Dato',
			'PARISH' => 'Parroquia',
			'ACTION' => 'Acción tomada',
			'INFORMANT' => 'Informante'
		);
		break;
}
																
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#UWA_CASEID#
#OFFENDER#
#PROFILE#
#PARISH#
#HOME_LC1_NAME#
#HOME_LAT#
#HOME_LON#
#UTM_Z#
#DATUM#
#ACTION#
#INFORMANT#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#UWA_CASEID#
#TABLE#OFFENDER#suspects;SUSPECT_NAME;--;;;;#
#PROFILE#
#TABLE#PARISH#parishes;P_2010_NAME;--;;;;#
#HOME_LC1_NAME#
#HOME_LAT#
#HOME_LON#
#UTM_Z#
#DATUM#
#ACTION#
#INFORMANT#
NXT;

$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#UWA_CASEID#
#TABLE#OFFENDER#suspects;SUSPECT_NAME;--;;;;#
#TABLE#PARISH#parishes;P_2010_NAME;--;;;;#
#HOME_LC1_NAME#
#HOME_LAT#
#HOME_LON#
#UTM_Z#
#DATUM#
#ACTION#
#INFORMANT#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

function _onInsertNew_offenderstable ($table, $rec, $asstable, $assrec)
{ _setField ($asstable, 'OFFENDERSTABLE', $assrec, $GLOBALS['timestamp'], $_POST['id']);

      $inssql = new mySQLclass();
      $query = "SELECT * FROM $table WHERE id = $rec";
      $inssql->_sqlLookup ($query);
      if ($inssql->sql_result === false) $inssql->_sqlerror ();
      $row = mysql_fetch_assoc ($inssql->sql_result);
      $_rec = $row['OFFENDER'];
      $asst = $row['ASSTABLE'];
      $assr = $row['ASSREC'];

   _setField ('suspects', 'WANTED', $_rec, '', $_POST['id']);

/* set UWA case no */

$caseNo = _getField ($asst, $assr, 'UWA_CASEID', '');
_setField ($table, 'UWA_CASEID', $rec, $caseNo, $_POST['id']);

/* update home-location info */

}

/* ---------------------------------------------------------------- */

function _onEdit_offenderstable ($table, $rec, $asstable, $assrec)
{ _onInsertNew_offenderstable ($table, $rec, $asstable, $assrec);
   $fieldstoset = array ('HOME_LAT' => 'HOME_LAT', 'HOME_LON' => 'HOME_LON', 'UTM_Z' => 'UTM_Z', 'DATUM' => 'DATUM', 'LC1_NAME' => 'HOME_LC1_NAME', 'PARISH' => 'PARISH');
   $offender = _getField ($table, $rec, 'UWA_CASEID', '');
   $sql = new mySQLclass();
   $query = "SELECT * FROM $table WHERE id = $rec";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror ();
   $row = mysql_fetch_assoc ($sql->sql_result);
   $offender = $row['OFFENDER'];
   if ($offender)
      { if ((! $row['HOME_LAT']) && (! $row['HOME_LON']) && (! $row['UTM_Z']) && (! $row['DATUM']) && (! $row['HOME_LC1_NAME']) && (! $row['PARISH']))
            foreach ($fieldstoset as $setfrom => $setin)
                         { $value = _getField ('suspects', $offender, $setfrom, '');
                            if ($value) _setField ($table, $setin, $rec, $value, $_POST['id']);
                         }

         foreach ($fieldstoset as $setin => $setfrom)
                      if ($row[$setfrom]) _setField ('suspects', $setin, $offender, $row[$setfrom], $_POST['id']);
      }

   

}

/* ---------------------------------------------------------------- */

function _onDelete_offenderstable ($table, $rec, $asstable, $assrec)
{ _onInsertNew_offenderstable ($table, $rec, $asstable, $assrec);
}

/* ---------------------------------------------------------------- */


?>

