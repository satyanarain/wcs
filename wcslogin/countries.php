<?php 
@session_start();

$sqltable = "countries";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
CC	VARCHAR(3),
COUNTRY_NAME	VARCHAR(48)
                                        )
CREATE;

$countriesTsql = new mySQLclass();
$countriesTsql->_sqlLookup ($query);
if ($countriesTsql->sql_result === false) 
  { if ($countriesTsql->_sqlerrNo () != 1050) $countriesTsql->_sqlerror (); 
  } else { $countries = file ("allcountries.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($countries as $country)
                            { list ($dummy, $CC, $countryname) = explode ("\t", $country);
                               $countryname = strtolower ($countryname);
                               $countryname = ucwords ($countryname);
                               $query = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '$CC', '$countryname')
NXT;
                               $countriesTsql->_sqlLookup ($query);
                               if ($countriesTsql->sql_result === false) $countriesTsql->_sqlerror ();
                            }
                mysql_free_result ($countriesTsql->sql_result);
             }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ();

$GLOBALS['newrecfields'][$sqltable] = array ('CC', 'COUNTRY_NAME');

$GLOBALS['requiredfields'][$sqltable] = array ('CC', 'COUNTRY_NAME');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '', '')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'CC' => 'EDIT', 
                                                                            'COUNTRY_NAME' => 'EDIT'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'CC' => 'CC',
                                                                      'COUNTRY_NAME' => 'Country'
                                                                    );

$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#CC#
#COUNTRY_NAME#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#CC#
#COUNTRY_NAME#
NXT;


$GLOBALS['tablealigns'][$sqltable] = array ();

$GLOBALS['noformats'][$sqltable] = array ();
$GLOBALS['suppresszeros'][$sqltable] = array ();
$GLOBALS['truncates'][$sqltable] = array ();

$GLOBALS['prepends'][$sqltable] = array ();
$GLOBALS['apppends'][$sqltable] = array ();

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */


?>

