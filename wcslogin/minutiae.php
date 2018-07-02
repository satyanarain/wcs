<?php 
@session_start();

$sqltable = "minutiae";
$query = <<<CREATE
CREATE TABLE $sqltable (
	id int UNSIGNED primary key not null auto_increment,
	SUSPECT BIGINT UNSIGNED,
	R1 TEXT,
	R2 TEXT,
	R3 TEXT,
	R4 TEXT,
	R5 TEXT,
	L1 TEXT,
	L2 TEXT,
	L3 TEXT,
	L4 TEXT,
	L5 TEXT
                                        )
CREATE;

$minutiaeTsql = new mySQLclass();
$minutiaeTsql->_sqlLookup ($query);
if ($minutiaeTsql->sql_result === false) { if ($minutiaeTsql->_sqlerrNo () != 1050) $minutiaeTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$_mainportal_ = 'minutiaeAdministration';

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */


$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ();

$GLOBALS['requiredfields'][$sqltable] = array ();

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', {$_POST['record']}, '', '', '', '', '', '', '', '', '', '')
NXT;

$GLOBALS['tablehandlings'][$sqltable] = array (
                                                                          );


$GLOBALS['tablelabels'][$sqltable] = array (
                                                                    );

$GLOBALS['tableheaders'][$sqltable] = <<<NXT
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

function _onEdit_minutiae ($table, $rec, $asstable, $assrec)
{ 
}

function _onInsertNew_minutiae ($table, $rec, $asstable, $assrec)
{ 
}

/* ---------------------------------------------------------------- */

?>

