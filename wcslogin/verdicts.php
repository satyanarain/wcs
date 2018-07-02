<?php 
@session_start();

$sqltable = "verdicts";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
VERDICT_NAME	VARCHAR(100)
                                        )
CREATE;

$verdictsTsql = new mySQLclass();
$verdictsTsql->_sqlLookup ($query);
if ($verdictsTsql->sql_result === false) 
  { if ($verdictsTsql->_sqlerrNo () != 1050) $verdictsTsql->_sqlerror (); 
  } else { $verdictss = file ("allverdicttypes.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($verdictss as $verdict)
                            { //$verdict = strtolower ($verdict);
                               //$verdict = ucwords ($verdict);
                               $query = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '$verdict')
NXT;
                               $verdictsTsql->_sqlLookup ($query);
                               if ($verdictsTsql->sql_result === false) $verdictsTsql->_sqlerror ();
                            }
                mysql_free_result ($verdictsTsql->sql_result);
             }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ('VERDICT_NAME' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ('VERDICT_NAME' => 100);

$GLOBALS['requiredfields'][$sqltable] = array ('VERDICT_NAME');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['NEW_VERDICT_NAME']}')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'VERDICT_NAME' => 'EDIT'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'VERDICT_NAME' => 'Verdicts'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'VERDICT_NAME' => 'Nom du verdict'
        );
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'VERDICT_NAME' => 'Nombre del veredicto'
        );
		break;
}
																	
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#VERDICT_NAME#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#VERDICT_NAME#
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

