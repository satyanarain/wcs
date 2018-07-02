<?php 
@session_start();

$sqltable = "species";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
SPECIES_NAME	VARCHAR(100)
                                        )
CREATE;

$speciesTsql = new mySQLclass();
$speciesTsql->_sqlLookup ($query);
if ($speciesTsql->sql_result === false) 
  { if ($speciesTsql->_sqlerrNo () != 1050) $speciesTsql->_sqlerror (); 
  } else { $species = file ("allspecies.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($species as $specie)
                            { //$specie = strtolower ($specie);
                               //$specie = ucwords ($specie);
                               $query = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '$specie')
NXT;
                               $speciesTsql->_sqlLookup ($query);
                               if ($speciesTsql->sql_result === false) $speciesTsql->_sqlerror ();
                            }
                mysql_free_result ($speciesTsql->sql_result);
             }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ('SPECIES_NAME' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ('SPECIES_NAME' => 100);

$GLOBALS['requiredfields'][$sqltable] = array ('SPECIES_NAME');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['NEW_SPECIES_NAME']}')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'SPECIES_NAME' => 'EDIT'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'SPECIES_NAME' => 'Species'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'SPECIES_NAME' => 'Nom de l espèce'
        );
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'SPECIES_NAME' => 'Nombre de la especie'
        );
		break;
}
																	
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SPECIES_NAME#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#SPECIES_NAME#
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

