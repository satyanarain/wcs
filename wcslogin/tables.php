<?php 
@session_start();

$sqltable = "tables";
$query = <<<CREATE
CREATE TABLE $sqltable (
	id int UNSIGNED primary key not null auto_increment,
	DELETED BIGINT UNSIGNED,
	TABLENAME VARCHAR(46),
	LAST_ACTIVITY BIGINT UNSIGNED,
	USER BIGINT UNSIGNED
                                        )
CREATE;

$tablesTsql = new mySQLclass();
$tablesTsql->_sqlLookup ($query);
if ($tablesTsql->sql_result === false) { if ($tablesTsql->_sqlerrNo () != 1050) $tablesTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */


$GLOBALS['requiredfields'][$sqltable] = array ( 'TABLENAME');

$GLOBALS['newrecfields'][$sqltable] = array ( 'TABLENAME' => 46);

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `tables` VALUES ('', 0, '{$_POST['NEW_TABLENAME']}', 0, 0)
NXT;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'DELETED' => 'AUTO',
                                                                            'TABLENAME' => 'EDIT', 
                                                                            'LAST_ACTIVITY' => 'AUTO',
                                                                            'USER' => 'AUTO'
                                                                          );

$GLOBALS['tablelabels'][$sqltable] = array ( 'TABLENAME' => 'Table', 
                                                                      'LAST_ACTIVITY' => 'Last edited',
                                                                      'USER' => 'Edited by' 
                                                                    );

$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#SORT#TABLENAME#
#SORT#LAST_ACTIVITY#
#SORT#USER#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#TABLENAME#
#TIME#LAST_ACTIVITY#
#TABLE#USER#users;REAL_NAME;Maestro;LAST_ACTIVITY;;;#
NXT;

/* ---------------------------------------------------------------- */



?>

