<?php 
@session_start();

$sqltable = "courts";
$query = <<<CREATE
CREATE TABLE $sqltable (
id int UNSIGNED primary key not null auto_increment,
DELETED BIGINT UNSIGNED,
CHANGED BIGINT UNSIGNED,
CHANGEDBY INT UNSIGNED,
COURT_NAME	VARCHAR(48),
COURT_GRADE VARCHAR (24)
                                        )
CREATE;

$courtsTsql = new mySQLclass();
$courtsTsql->_sqlLookup ($query);
if ($courtsTsql->sql_result === false) 
  { if ($courtsTsql->_sqlerrNo () != 1050) $courtsTsql->_sqlerror (); 
  } else { $courts = file ("allcourts.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($courts as $court)
                            { $court = strtolower ($court);
                               $court = ucwords ($court);
                               $query = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '$court')
NXT;
                               $courtsTsql->_sqlLookup ($query);
                               if ($courtsTsql->sql_result === false) $courtsTsql->_sqlerror ();
                            }
                mysql_free_result ($courtsTsql->sql_result);
             }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ('COURT_NAME' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ('COURT_NAME' => 48, 'COURT_GRADE' => 0);

$GLOBALS['requiredfields'][$sqltable] = array ('COURT_NAME', 'COURT_GRADE');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, {$GLOBALS['timestamp']}, {$_POST['id']}, '{$_POST['NEW_COURT_NAME']}', '{$_POST['NEW_COURT_GRADE']}')
NXT;

$GLOBALS['datablockwidth'][$sqltable] = 3;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'COURT_NAME' => 'EDIT',
												'COURT_GRADE' => '_select("NEW_COURT_GRADE", "Grade 2~Grade 1~Chief Magistrate~High Court~Supreme Court");'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'COURT_NAME' => 'Court',
											 'COURT_GRADE' => 'Grade'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'COURT_NAME' => 'Nom du tribunal',
			'COURT_GRADE' => 'Qualité'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 
			'COURT_NAME' => 'Nombre del Tribunal',
			'COURT_GRADE' => 'Grado'
		);
		break;
}
																	
																	
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#COURT_NAME#
#COURT_GRADE#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#COURT_NAME#
#COURT_GRADE#
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

