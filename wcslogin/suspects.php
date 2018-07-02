<?php 
@session_start();

$sqltable = "suspects";
$query = <<<CREATE
CREATE TABLE $sqltable (
	id int UNSIGNED primary key not null auto_increment,
	REGISTERED BIGINT UNSIGNED,
	DELETED BIGINT UNSIGNED,
	CHANGED BIGINT UNSIGNED,
	CHANGEDBY INT UNSIGNED,
	NOTES BIGINT UNSIGNED,
	DOCS BIGINT UNSIGNED,
	PROFILE BIGINT UNSIGNED,

	SUSPECT_NAME VARCHAR(48),
	SUSPECT_ID VARCHAR(48),
	SUSPECT_PASSPORT_NO VARCHAR(48),
	SUSPECT_DR_LICENCE VARCHAR(48),
	SUSPECT_VOTERS_CARD VARCHAR(48),

	SUSPECT_NATIONALITY VARCHAR(3),
	WANTED VARCHAR(24),
	WANTED_SINCE BIGINT UNSIGNED,
	OFFENCE VARCHAR(48),

	TEL VARCHAR(16),
	YOB INT UNSIGNED,
	GENDER VARCHAR(2),
	PARISH BIGINT UNSIGNED,
	LC1_NAME VARCHAR(46),

	HOME_LAT VARCHAR(24),
	HOME_LON VARCHAR(24),
	UTM_Z VARCHAR(6),
	DATUM VARCHAR(6),

	MARRIED VARCHAR(2),
	HUSBANDS INT UNSIGNED,
	WIVES INT UNSIGNED,
	CHILDREN INT UNSIGNED,
	OTHER_DEPENDANTS INT UNSIGNED,
	ETHNICITY VARCHAR(48),
	EDUCATION VARCHAR(14),
	OCCUPATION VARCHAR(48),
	OTHER_MEANS TEXT,
	MONTHLY_INCOME INT UNSIGNED

                                        )
CREATE;

$suspectsTsql = new mySQLclass();
$suspectsTsql->_sqlLookup ($query);
if ($suspectsTsql->sql_result === false) { if ($suspectsTsql->_sqlerrNo () != 1050) $suspectsTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

//include_once 'fields.php';
$_mainportal_ = 'SuspectsAdministration';

/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */


$GLOBALS['searchfields'][$sqltable] = array ( 'SUSPECT_NAME' => '%', 'LC1_NAME' => '%', 'SUSPECT_ID' => '%', 'SUSPECT_PASSPORT_NO' => '%', 'SUSPECT_DR_LICENCE' => '%', 'SUSPECT_VOTERS_CARD' => '%', 'SUSPECT_NATIONALITY' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ( 'SUSPECT_NAME' => 48, 'SUSPECT_ID' => 48, 'SUSPECT_PASSPORT_NO' => 48, 'SUSPECT_DR_LICENCE' => 48, 'SUSPECT_VOTERS_CARD' => 48, 'GENDER' => 0, 'EDUCATION' => 0, 'SUSPECT_NATIONALITY' => 0, 'OFFENCE' => 0, 
'TEL' => 16, 'YOB' => 0, 'LC1_NAME' => 46, 'WIVES' => 0, 'CHILDREN' => 0, 'OTHER_DEPENDANTS' => 0, 'ETHNICITY' => 48, 'OCCUPATION' => 48, 'MONTHLY_INCOME' => 0);

$GLOBALS['requiredfields'][$sqltable] = array ( 'SUSPECT_NAME', 'LC1_NAME', 'PARISH', 'GENDER', 'SUSPECT_NATIONALITY', 'SUSPECT_NATIONALITY');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', $timestamp, 0, $timestamp, '{$_POST['id']}', 0, 0, 0, '{$_POST['NEW_SUSPECT_NAME']}', '{$_POST['NEW_SUSPECT_ID']}', '{$_POST['NEW_SUSPECT_PASSPORT_NO']}', '{$_POST['NEW_SUSPECT_DR_LICENCE']}', '{$_POST['NEW_SUSPECT_VOTERS_CARD']}', '{$_POST['NEW_SUSPECT_NATIONALITY']}', '', '', '{$_POST['NEW_CRIME_TYPE']}', '{$_POST['NEW_TEL']}', '{$_POST['NEW_YOB']}', '{$_POST['NEW_GENDER']}', '', '{$_POST['NEW_LC1_NAME']}', '', '', '', '', 
'{$_POST['NEW_MARRIED']}', '{$_POST['NEW_HUSBANDS']}', '{$_POST['NEW_WIVES']}', '{$_POST['NEW_CHILDREN']}', '{$_POST['NEW_OTHER_DEPENDANTS']}', '{$_POST['NEW_ETHNICITY']}', '{$_POST['NEW_EDUCATION']}', '{$_POST['NEW_OCCUPATION']}', '', '{$_POST['NEW_MONTHLY_INCOME']}')
NXT;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'REGISTERED' => 'AUTO', 
                                                                            'DELETED' => 'AUTO', 
                                                                            'NOTES' => 'SUSPECT_NAME',
                                                                            'DOCS' => 'SUSPECT_NAME',
                                                                            'PROFILE' => 'SUSPECT_NAME',
                                                                            'SUSPECT_NAME' => 'EDIT', 
                                                                            'SUSPECT_ID' => 'EDIT',
                                                                            'SUSPECT_PASSPORT_NO' => 'EDIT',
                                                                            'SUSPECT_DR_LICENCE' => 'EDIT',
                                                                            'SUSPECT_VOTERS_CARD' => 'EDIT',
                                                                            'SUSPECT_NATIONALITY' => '_selectCountry("NEW_SUSPECT_NATIONALITY");',

                                                                            'WANTED' => '_selectChkMulti("NEW_WANTED", "UPDF~Customs~Police~UWA~LC~Interpol~Other");',
                                                                            'WANTED_SINCE' => '_pickDate("Wanted since", "UWA_CASEID");',
                                                                            'OFFENCE' => '_selectCrime("NEW_CRIME_TYPE");',

                                                                            'TEL' => 'EDIT', 
                                                                            'YOB' => '_int(1920, "", 4);', 
                                                                            'GENDER' => '_select("NEW_GENDER", "M~F");',
                                                                            'LC1_NAME' => 'EDIT',
                                                                            'PARISH' => '_pickRecordId("parishes","");',

                                                                            'HOME_LAT' => 'EDIT',
                                                                            'HOME_LON' => 'EDIT',
                                                                            'UTM_Z' => '_select("NEW_UTM_Z", "UTM35N~UTM35S~UTM36N~UTM36S~N/A");',
                                                                            'DATUM' => '_select("NEW_DATUM", "WGS84~ARC1960");',
																			'MARRIED' => '_select("NEW_MARRIED", "Y~N");',
                                                                            'HUSBANDS' => '_int(0, "", 1);',
																			'WIVES' => '_int(0, "", 1);', 
                                                                            'CHILDREN' => '_int(0, "", 2);',
                                                                            'OTHER_DEPENDANTS' => '_int(0, "", 2);',
                                                                            'ETHNICITY' => 'EDIT',
                                                                            'EDUCATION' => '_pickEducation();',
                                                                            'OCCUPATION' => 'EDIT',
                                                                            'OTHER_MEANS' => '_text("OTHER_MEANS",30,4,24);',
                                                                            'MONTHLY_INCOME' => '_int(0, "", 8);'
                                                                         );


$GLOBALS['tablelabels'][$sqltable] = array ( 'REGISTERED' => 'Record first entered', 
                                                                      'SUSPECT_NAME' => 'Name', 
                                                                      'SUSPECT_ID' => 'ID',
                                                                      'SUSPECT_PASSPORT_NO' => 'Passport No.',
                                                                      'SUSPECT_DR_LICENCE' => 'Driving License No.',
                                                                      'SUSPECT_VOTERS_CARD' => 'Voters card No.',

                                                                      'SUSPECT_NATIONALITY' => 'Nationality',
                                                                      'WANTED' => 'Wanted',
                                                                      'WANTED_SINCE' => 'W-date',
                                                                      'OFFENCE' => 'Offence',

                                                                      'TEL' => 'Tel.', 
                                                                      'YOB' => 'YOB', 
                                                                      'GENDER' => 'Gnd',
                                                                      'LC1_NAME' => 'Village (LC1)',
                                                                      'PARISH' => 'Parish',
                                                                      'NOTES' => 'Notes', 
                                                                      'DOCS' => 'Doc.s', 
                                                                      'PROFILE' => 'Profile', 

                                                                      'HOME_LAT' => 'Home Lat.',
                                                                      'HOME_LON' => 'Home Lon.',
                                                                      'UTM_Z' => 'UTMz',
                                                                      'DATUM' => 'Datum',
                                                                      'MARRIED' => 'Married',
																	  'HUSBANDS' => 'Husbands',
																	  'WIVES' => 'Wives',
                                                                      'CHILDREN' => 'Chld.',
                                                                      'OTHER_DEPENDANTS' => 'Depnd.',
                                                                      'ETHNICITY' => 'Ethnicity',
                                                                      'EDUCATION' => 'Education',
                                                                      'OCCUPATION' => 'Occupation',
                                                                      'OTHER_MEANS' => 'Other means of income',
                                                                      'MONTHLY_INCOME' => 'Monthly income (UGX)'
                                                                   );

																   
switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 'REGISTERED' => 'Record first entered', 
			'SUSPECT_NAME' => 'Nom', 
			'SUSPECT_ID' => 'ID',
			'SUSPECT_PASSPORT_NO' => 'Numéro de passeport',
			'SUSPECT_DR_LICENCE' => 'Numéro de permis de conduire',
			'SUSPECT_VOTERS_CARD' => 'Numéro de carte d électeur',
			'SUSPECT_NATIONALITY' => 'Nationalité',
			'WANTED' => 'Recherché par',
			'WANTED_SINCE' => 'Recherché depuis',
			'OFFENCE' => 'Infraction',
			'TEL' => 'Téléphone', 
			'YOB' => 'Année de naissance', 
			'GENDER' => 'Le genre',
			'LC1_NAME' => 'Village (LC1)',
			'PARISH' => 'Paroisse',
			'NOTES' => 'Remarques', 
			'DOCS' => 'Docs', 
			'PROFILE' => 'Profil', 
			'HOME_LAT' => 'Accueil Lat.',
			'HOME_LON' => 'Accueil Lon.',
			'UTM_Z' => 'UTMz',
			'DATUM' => 'Données',
			'MARRIED' => 'Marié',
			'HUSBANDS' => 'Maris',
			'WIVES' => 'épouses',
			'CHILDREN' => 'Enfants',
			'OTHER_DEPENDANTS' => 'Dépendances',
			'ETHNICITY' => 'Ethnicité',
			'EDUCATION' => 'Éducation',
			'OCCUPATION' => 'Occupation',
			'OTHER_MEANS' => 'Autres moyens de revenu',
			'MONTHLY_INCOME' => 'Revenu mensuel (UGX)'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 'REGISTERED' => 'Record first entered', 
			'SUSPECT_NAME' => 'Nombre', 
			'SUSPECT_ID' => 'ID',
			'SUSPECT_PASSPORT_NO' => 'Número de pasaporte',
			'SUSPECT_DR_LICENCE' => 'Número de licencia de conducir',
			'SUSPECT_VOTERS_CARD' => 'Número de la tarjeta del votante',
			'SUSPECT_NATIONALITY' => 'Nacionalidad',
			'WANTED' => 'Buscado por la',
			'WANTED_SINCE' => 'Buscado desde',
			'OFFENCE' => 'Ofensa',
			'TEL' => 'Teléfono', 
			'YOB' => 'Año de nacimiento', 
			'GENDER' => 'Género',
			'LC1_NAME' => 'Pueblo (LC1)',
			'PARISH' => 'Parroquia',
			'NOTES' => 'Notas', 
			'DOCS' => 'Docs', 
			'PROFILE' => 'Perfil', 
			'HOME_LAT' => 'Casa Lat.',
			'HOME_LON' => 'Casa Lon.',
			'UTM_Z' => 'UTMz',
			'DATUM' => 'Dato',
			'MARRIED' => 'Casado',
			'HUSBANDS' => 'Maridos',
			'WIVES' => 'Esposas',
			'CHILDREN' => 'Niños',
			'OTHER_DEPENDANTS' => 'Dependientes',
			'ETHNICITY' => 'Etnicidad',
			'EDUCATION' => 'Educación',
			'OCCUPATION' => 'Ocupación',
			'OTHER_MEANS' => 'Otros medios de ingresos',
			'MONTHLY_INCOME' => 'Ingreso mensual (UGX)'
		);
		break;
}
																   
																   
																   
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SHOWREC#
#NOTES#
#DOCS#
#PROFILE#
#SORT#SUSPECT_NAME#
#SORT#SUSPECT_NATIONALITY#
#WANTED#
#WANTED_SINCE#
#YOB#
#GENDER#
#SUSPECT_ID#
#PARISH#
#LC1_NAME#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#SHOWREC#
#NOTES#
#DOCS#
#PROFILE#
#SUSPECT_NAME#
#FTTABLE#SUSPECT_NATIONALITY#countries;CC;COUNTRY_NAME;--;;;;#
#WANTED#
#DATE#WANTED_SINCE#
#YOB#
#GENDER#
#SUSPECT_ID#
#TABLE#PARISH#parishes;P_2010_NAME;--;;;;#
#LC1_NAME#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ('GENDER' => 'center');

$GLOBALS['noformats'][$sqltable] = array ('YOB' => 1);
$GLOBALS['suppresszeros'][$sqltable] = array ('YOB' => 1);
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

$GLOBALS['pickheaders'][$sqltable] = <<<NXT
#SORT#SUSPECT_NAME#
#SUSPECT_NATIONALITY#
#WANTED#
#WANTED_SINCE#
#OFFENCE#
#TEL#
#YOB#
#GENDER#
#SUSPECT_ID#
#SUSPECT_PASSPORT_NO#
#SUSPECT_DR_LICENCE#
#SUSPECT_VOTERS_CARD#
NXT;

$GLOBALS['pickdisplays'][$sqltable] = <<<NXT
#SUSPECT_NAME#
#FTTABLE#SUSPECT_NATIONALITY#countries;CC;COUNTRY_NAME;--;;;;#
#WANTED#
#DATE#WANTED_SINCE#
#OFFENCE#
#TEL#
#YOB#
#GENDER#
#SUSPECT_ID#
#SUSPECT_PASSPORT_NO#
#SUSPECT_DR_LICENCE#
#SUSPECT_VOTERS_CARD#
NXT;

/* ---------------------------------------------------------------- */

$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#NOTES#
#DOCS#
#PROFILE#
#SUSPECT_NAME#
#WANTED#
#DATE#WANTED_SINCE#
#OFFENCE#
#TEL#
#YOB#
#GENDER#
#SUSPECT_ID#
#SUSPECT_PASSPORT_NO#
#SUSPECT_DR_LICENCE#
#SUSPECT_VOTERS_CARD#
#FTTABLE#SUSPECT_NATIONALITY#countries;CC;COUNTRY_NAME;--;;;;#
#TABLE#PARISH#parishes;P_2010_NAME;--;;;;#
#LC1_NAME#
#HOME_LAT#
#HOME_LON#
#UTM_Z#
#DATUM#
#MARRIED#
#HUSBANDS#
#WIVES#
#CHILDREN#
#OTHER_DEPENDANTS#
#ETHNICITY#
#EDUCATION#
#OCCUPATION#
#OTHER_MEANS#
#MONTHLY_INCOME#
NXT;

/* ---------------------------------------------------------------- */

function _onEdit_suspects ($table, $rec, $asstable, $assrec)
{ 
}

function _onInsertNew_suspects ($table, $rec, $asstable, $assrec)
{ 
}

/* ---------------------------------------------------------------- */

?>

