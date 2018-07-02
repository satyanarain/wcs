<?php 
@session_start();

$sqltable = "arrests";
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
	PARTICIPANTS VARCHAR(24),

	OFFICER_NAME VARCHAR(48),
	ARRESTDATE BIGINT UNSIGNED,
	ARRESTTIME VARCHAR(5),

	OUTPOST VARCHAR(24),
	OUTPOST_NAME VARCHAR(48),

	LOCATION_LAT VARCHAR(24),
	LOCATION_LON VARCHAR(24),
	UTM_Z VARCHAR(6),
	DATUM VARCHAR(6),

	LOCATION_NAME VARCHAR(48),
	DETECTION VARCHAR(48),

	OFFENCE VARCHAR(64),
	OFFENCE_LOCATION VARCHAR(24),

	EVIDENCETABLE BIGINT UNSIGNED,
	MOTIVATION VARCHAR(24),
	OFFENDERSTABLE BIGINT UNSIGNED

                                        )
CREATE;

$arrestsTsql = new mySQLclass();
$arrestsTsql->_sqlLookup ($query);
if ($arrestsTsql->sql_result === false) { if ($arrestsTsql->_sqlerrNo () != 1050) $arrestsTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$_mainportal_ = 'ArrestsAdministration';

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */


$GLOBALS['searchfields'][$sqltable] = array ( 'UWA_CASEID' => '%', 'POLICE_CASEID' => '%', 'OUTPOST' => '%', 'OFFICER_NAME' => '%', 'OFFENCE' => '%', 'LOCATION_NAME' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ('POLICE_CASEID' => 24, 'OFFICER_NAME' => 48, 'OUTPOST' => 0, 'OUTPOST_NAME' => 48, 'LOCATION_NAME' => 48, 'DETECTION' => 0, 'OFFENCE' => 0, 'OFFENCE_LOCATION' => 0, 'MOTIVATION' => 0);

$GLOBALS['requiredfields'][$sqltable] = array ( 'UWA_CASEID', 'ARRESTDATE', 'ARRESTTIME', 'OUTPOST', 'LOCATION_NAME', 'OFFICER_NAME', 'DETECTION', 'OFFENCE', 'OFFENCE_LOCATION', 'EVIDENCETABLE', 'OFFENDERSTABLE');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', $timestamp, 0, $timestamp, '{$_POST['id']}', 0, 0, '{$_POST['NEW_UWA_CASEID']}', '{$_POST['NEW_POLICE_CASEID']}', '', '{$_POST['NEW_OFFICER_NAME']}', 0, '', '{$_POST['NEW_OUTPOST']}', '{$_POST['NEW_OUTPOST_NAME']}', '', '', '', '', 
'{$_POST['NEW_LOCATION_NAME']}', '{$_POST['NEW_DETECTION']}', '{$_POST['NEW_CRIME_TYPE']}', '{$_POST['NEW_OFFENCE_LOCATION']}', 0, '{$_POST['NEW_MOTIVATION']}', 0)
NXT;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'REGISTERED' => 'AUTO', 
                                                                            'DELETED' => 'AUTO', 
                                                                            'NOTES' => 'UWA_CASEID',
                                                                            'DOCS' => 'UWA_CASEID',
                                                                            'UWA_CASEID' => 'AUTO',
                                                                            'POLICE_CASEID' => 'EDIT',
                                                                            'PARTICIPANTS' => '_selectChkMulti("NEW_PARTICIPANTS", "UPDF~Customs~Police~UWA~LC~Other");',
                                                                            'OFFICER_NAME' => 'EDIT',
                                                                            'ARRESTDATE' => '_pickDate("Date of arrest", "UWA_CASEID");', 
                                                                            'ARRESTTIME' => 'EDIT',
                                                                            'OUTPOST' => '_select("NEW_OUTPOST", "'.$outposts.'");',
                                                                            'OUTPOST_NAME' => 'EDIT',
                                                                            'LOCATION_LAT' => 'EDIT',
                                                                            'LOCATION_LON' => 'EDIT',
                                                                            'UTM_Z' => '_select("NEW_UTM_Z", "UTM35N~UTM35S~UTM36N~UTM36S~N/A");',
                                                                            'DATUM' => '_select("NEW_DATUM", "WGS84~ARC1960");',
                                                                            'LOCATION_NAME' => 'EDIT',
                                                                            'DETECTION' => '_select("NEW_DETECTION", "'.$howdetectedtypes.'");',
                                                                            'OFFENCE' => '_selectCrime("NEW_CRIME_TYPE");',
                                                                            'OFFENCE_LOCATION' => '_select("NEW_OFFENCE_LOCATION", "'.$outposts.'");',
                                                                            'EVIDENCETABLE' => 'UWA_CASEID',
                                                                            'MOTIVATION' => '_select("NEW_MOTIVATION", "'.$motivationtypes.'");',
                                                                            'OFFENDERSTABLE' => 'UWA_CASEID'
                                                                         );


$GLOBALS['tablelabels'][$sqltable] = array ( 'NOTES' => 'Notes', 
                                                                      'DOCS' => 'Doc.s', 
                                                                      'UWA_CASEID' => 'UWA Arrest ID',
                                                                      'POLICE_CASEID' => 'POLICE Case ID',
                                                                      'PARTICIPANTS' => 'Participants',
                                                                      'OFFICER_NAME' => 'Arresting officer',
                                                                      'ARRESTDATE' => 'Date', 
                                                                      'ARRESTTIME' => 'Time',
                                                                      'OUTPOST' => 'Station',
                                                                      'OUTPOST_NAME' => 'Outpost name',
                                                                      'LOCATION_LAT' => 'Location Lat.',
                                                                      'LOCATION_LON' => 'Location Lon.',
                                                                      'UTM_Z' => 'UTMz',
                                                                      'DATUM' => 'Datum',
                                                                      'LOCATION_NAME' => 'Location name',
                                                                      'DETECTION' => 'Detection',
                                                                      'OFFENCE' => 'Crime type',
                                                                      'OFFENCE_LOCATION' => 'Offence Loc.',
                                                                      'EVIDENCETABLE' => 'Evidence',
                                                                      'MOTIVATION' => 'Motivation',
                                                                      'OFFENDERSTABLE' => 'Offenders'
                                                                   );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'NOTES' => 'Remarques', 
			'DOCS' => 'Docs', 
			'UWA_CASEID' => 'UWA Arrêter ID',
			'POLICE_CASEID' => 'Cas POLICE ID',
			'PARTICIPANTS' => 'Participants',
			'OFFICER_NAME' => 'Officier d arrestation',
			'ARRESTDATE' => 'Datte', 
			'ARRESTTIME' => 'Temps',
			'OUTPOST' => 'Site',
			'OUTPOST_NAME' => 'prénom',
			'LOCATION_LAT' => 'Emplacement Lat.',
			'LOCATION_LON' => 'Emplacement Lon.',
			'UTM_Z' => 'UTMz',
			'DATUM' => 'Données',
			'LOCATION_NAME' => 'Nom de la localisation',
			'DETECTION' => 'Détection',
			'OFFENCE' => 'Type de crime',
			'OFFENCE_LOCATION' => 'Emplacement de l infraction',
			'EVIDENCETABLE' => 'Preuve',
			'MOTIVATION' => 'Motivation',
			'OFFENDERSTABLE' => 'Délinquants'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'NOTES' => 'Notas', 
			'DOCS' => 'Docs', 
			'UWA_CASEID' => 'UWA Arrestar ID',
			'POLICE_CASEID' => 'Caso POLICE ID',
			'PARTICIPANTS' => 'Participantes',
			'OFFICER_NAME' => 'Oficial de arresto',
			'ARRESTDATE' => 'Fecha', 
			'ARRESTTIME' => 'Hora',
			'OUTPOST' => 'Estación',
			'OUTPOST_NAME' => 'Nombre de Outpost',
			'LOCATION_LAT' => 'Ubicación Lat.',
			'LOCATION_LON' => 'Ubicación Lon.',
			'UTM_Z' => 'UTMz',
			'DATUM' => 'Dato',
			'LOCATION_NAME' => 'Nombre del lugar',
			'DETECTION' => 'Detección',
			'OFFENCE' => 'Tipo de crimen',
			'OFFENCE_LOCATION' => 'Localización del delito',
			'EVIDENCETABLE' => 'Evidencia',
			'MOTIVATION' => 'Motivación',
			'OFFENDERSTABLE' => 'Delincuentes'
		);
		break;
}
															   
																   
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SHOWREC#
#NOTES#
#DOCS#
#UWA_CASEID#
#POLICE_CASEID#
#ARRESTDATE#
#ARRESTTIME#
#LOCATION_NAME#
#OUTPOST#
#OFFENCE#
#EVIDENCETABLE#
#OFFENDERSTABLE#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#SHOWREC#
#NOTES#
#DOCS#
#UWA_CASEID#
#POLICE_CASEID#
#DATE#ARRESTDATE#
#ARRESTTIME#
#LOCATION_NAME#
#OUTPOST#
#OFFENCE#
#EVIDENCETABLE#
#OFFENDERSTABLE#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ('GENDER' => 'center');

$GLOBALS['noformats'][$sqltable] = array ('YOB' => 1);
$GLOBALS['suppresszeros'][$sqltable] = array ('YOB' => 1);
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#NOTES#
#DOCS#
#UWA_CASEID#
#POLICE_CASEID#
#PARTICIPANTS#
#OFFICER_NAME#
#DATE#ARRESTDATE#
#ARRESTTIME#
#OUTPOST#
#OUTPOST_NAME#
#LOCATION_LAT#
#LOCATION_LON#
#UTM_Z#
#DATUM#
#LOCATION_NAME#
#DETECTION#
#OFFENCE#
#OFFENCE_LOCATION#
#EVIDENCETABLE#
#MOTIVATION#
#OFFENDERSTABLE#
NXT;

/* ---------------------------------------------------------------- */

function _onEdit_arrests ($table, $rec, $asstable, $assrec)
{ 
}

function _onInsertNew_arrests ($table, $rec, $asstable, $assrec)
{ $ent = _getUserentity ($_POST['id']);
   $date = date ('Y-m-d', $GLOBALS['timestamp']);
   $ser = 1;
   while (_checkSerial ($table, 'UWA_CASEID', "$ent-{$_POST['id']}-$date-$ser")) $ser++;
   $serial = "$ent-{$_POST['id']}-$date-$ser";
   _setField ($table, 'UWA_CASEID', $rec, $serial, $_POST['id']);
}

/* ---------------------------------------------------------------- */

?>

