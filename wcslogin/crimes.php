<?php 
@session_start();

$sqltable = "crimes";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
CRIME_NAME	VARCHAR(100)
                                        )
CREATE;

$crimetypeTsql = new mySQLclass();
$crimetypeTsql->_sqlLookup ($query);
if ($crimetypeTsql->sql_result === false) 
  { if ($crimetypeTsql->_sqlerrNo () != 1050) $crimetypeTsql->_sqlerror (); 
  } else { $crimetypes = file ("allcrimetypes.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($crimetypes as $crimetype)
                            { //$crimetype = strtolower ($crimetype);
                               //$crimetype = ucwords ($crimetype);
                               $query = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '$crimetype')
NXT;
                               $crimetypeTsql->_sqlLookup ($query);
                               if ($crimetypeTsql->sql_result === false) $crimetypeTsql->_sqlerror ();
                            }
                mysql_free_result ($crimetypeTsql->sql_result);
             }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ('CRIME_NAME' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ('CRIME_NAME' => 100);

$GLOBALS['requiredfields'][$sqltable] = array ('CRIME_NAME');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['NEW_CRIME_NAME']}')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'CRIME_NAME' => 'EDIT'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'CRIME_NAME' => 'Crimes'
                                                                    );

																	
switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'CRIME_NAME' => 'la criminalité'
        );
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'CRIME_NAME' => 'Crimen'
        );
		break;
}
																	
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#CRIME_NAME#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#CRIME_NAME#
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

