<?php 
@session_start();


$_docspath = "{$GLOBALS['docspath']}_users/{$_POST['id']}";

$extractfields = array ();
$extractformats = array ();
$extractsubtables = array ();

/* ---------------------------------------------------------------- */

$extracttables = array ('suspects', 'arrests', 'cases');

/* ---------------------------------------------------------------- */

$extractfields ['suspects'] = array ('SUSPECT_NAME', 'SUSPECT_ID', 'SUSPECT_PASSPORT_NO', 'SUSPECT_DR_LICENCE', 'SUSPECT_VOTERS_CARD', 'SUSPECT_NATIONALITY', 'WANTED', 'WANTED_SINCE', 'OFFENCE', 'TEL', 'YOB', 'GENDER', 'PARISH', 'LC1_NAME', 'HOME_LAT', 'HOME_LON', 'UTM_Z', 'DATUM', 'WIVES', 'CHILDREN', 'OTHER_DEPENDANTS', 'ETHNICITY', 'EDUCATION', 'OCCUPATION', 'OTHER_MEANS', 'MONTHLY_INCOME');

$extractformats ['suspects'] = array ('WANTED_SINCE' => 'DATE', 'PARISH' => 'TABLE#parishes#P_2010_NAME', 'SUSPECT_NATIONALITY' => 'TABLE2#countries#CC#COUNTRY_NAME');

/* ---------------------------------------------------------------- */

$extractfields ['arrests'] = array ('UWA_CASEID', 'POLICE_CASEID', 'OFFICER_NAME', 'ARRESTDATE', 'ARRESTTIME', 'OUTPOST', 'OUTPOST_NAME', 'LOCATION_LAT', 'LOCATION_LON', 'UTM_Z', 'DATUM', 'LOCATION_NAME', 'DETECTION', 'OFFENCE', 'OFFENCE_LOCATION', 'MOTIVATION');

$extractformats ['arrests'] = array ('ARRESTDATE' => 'DATE');

$extractsubtables ['arrests'] = array ('EVIDENCETABLE' => 'evidencetable', 'OFFENDERSTABLE' => 'offenderstable');

/* ---------------------------------------------------------------- */

$extractfields ['cases'] = array ('UWA_CASEID', 'POLICE_CASEID', 'CRIME_TYPE', 'COURTDATE', 'ENDEDDATE', 'UWA_PROSECUTOR', 'STATE_PROSECUTOR', 'DEFENCE_LAWYER', 'MAGISTRATE');

$extractformats ['cases'] = array ('COURTDATE' => 'DATE', 'ENDEDDATE' => 'DATE');

$extractsubtables ['cases'] = array ('DEFENDANTSTABLE' => 'defendantstable', 'VERDICTSTABLE' => 'verdictstable');

/* ---------------------------------------------------------------- */

$extractfields ['evidencetable'] = array ('EVIDENCE', 'SPECIES', 'LABEL', 'QTY', 'EST_VALUE', 'UNIT', 'EVIDENCE_NO', 'STATUS', 'LOCATION');

/* ---------------------------------------------------------------- */

$extractfields ['offenderstable'] = array ('UWA_CASEID', 'OFFENDER', 'PARISH', 'HOME_LC1_NAME', 'HOME_LAT', 'HOME_LON', 'UTM_Z', 'DATUM', 'ACTION', 'INFORMANT');

$extractformats ['offenderstable'] = array ('OFFENDER' => 'TABLE#suspects#SUSPECT_NAME', 'PARISH' => 'TABLE#parishes#P_2010_NAME');

/* ---------------------------------------------------------------- */

$extractfields ['defendantstable'] = array ('DEFENDANT', 'CRIME');

$extractformats ['defendantstable'] = array ('DEFENDANT' => 'TABLE#suspects#SUSPECT_NAME');

$extractsubtables ['defendantstable'] = array ();

/* ---------------------------------------------------------------- */

$extractfields ['verdictstable'] = array ('UWA_CASEID', 'COURT_DATE', 'COURT_NAME', 'VERDICT', 'SPEC', 'QTY', 'UNIT', 'APPEALED');

$extractformats ['verdictstable'] = array ('COURT_DATE' => 'DATE');

/* ---------------------------------------------------------------- */

function _xlateField ($value, $table, $field)
{ $format = $GLOBALS['extractformats'] [$table][$field];
   if (($format == 'DATE') && ($_POST['XLATE_DATES'])) { if (! $value) return ''; else return date ($GLOBALS['Dformat'], $value); }
   if ((substr ($format, 0, 6) == 'TABLE#') && ($_POST['XLATE_NAMES'])) 
      { list ($_dummy, $_table, $_returnfield) = explode ('#', $format); 
        return _getField ($_table, $value, $_returnfield, '');
      }
   if ((substr ($format, 0, 6) == 'TABLE2') && ($_POST['XLATE_NATIONALITY'])) 
      { list ($_dummy, $_table, $_searchfield, $_returnfield) = explode ('#', $format); 
        return _getField2 ($_table, $_searchfield, $value, $_returnfield, '');
      }
return false;
}

/* ---------------------------------------------------------------- */

?>

