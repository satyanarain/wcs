<?php 
@session_start();

$sqltable = "users";
$query = <<<CREATE
CREATE TABLE $sqltable (
	id int UNSIGNED primary key not null auto_increment,
	REAL_NAME VARCHAR(24),
	USERNAME VARCHAR(24),
	PASSWORD VARCHAR(24),
	USEREMAIL VARCHAR(50),
	ENTITY VARCHAR(24),
	ROLE VARCHAR(24),
	REGISTERED BIGINT UNSIGNED,
	DELETED BIGINT UNSIGNED,
	CHANGED BIGINT UNSIGNED,
	CHANGEDBY INT UNSIGNED,
	LAST_ACTIVITY BIGINT UNSIGNED,
	LAST_LOGIN BIGINT UNSIGNED,
	NUMSEPARATORS VARCHAR(2),
	DATEFORMAT VARCHAR(12),
	TIMEFORMAT VARCHAR(12),
	TRIES_NUM INT(1)
                                        )
CREATE;

$usersTsql = new mySQLclass();
$usersTsql->_sqlLookup ($query);
if ($usersTsql->sql_result === false) { if ($usersTsql->_sqlerrNo () != 1050) $usersTsql->_sqlerror (); }

/* ---------------------------------------------------------------- */

$GLOBALS['searchfields'][$sqltable] = array ( 'REAL_NAME' => '%', 'USERNAME' => '%', 'PASSWORD' => '%', 'USEREMAIL' => '%', 'ROLE' => '_incl_;roles');

$GLOBALS['newrecfields'][$sqltable] = array ( 'REAL_NAME' => 24, 'USERNAME' => 24, 'PASSWORD' => 24, 'USEREMAIL' => 50);

$GLOBALS['requiredfields'][$sqltable] = array ( 'REAL_NAME', 'USERNAME', 'PASSWORD', 'USEREMAIL', 'ENTITY', 'ROLE', 'NUMSEPARATORS', 'DATEFORMAT', 'TIMEFORMAT');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `users` VALUES ('', '{$_POST['NEW_REAL_NAME']}', '{$_POST['NEW_USERNAME']}', '{$_POST['NEW_PASSWORD']}', '{$_POST['NEW_USEREMAIL']}', '{$_POST['NEW_ENTITY']}', '{$_POST['NEW_ROLE']}', $timestamp, 0, $timestamp, '{$_POST['id']}', 0, 0, '-.', 'd/m-Y', 'H:i', 0)
NXT;

$GLOBALS['tablehandlings'][$sqltable] = array ( 'id' => 'AUTO',
                                                                        'REAL_NAME' => 'EDIT', 
                                                                        'USERNAME' => 'EDIT', 
                                                                        'PASSWORD' => 'EDIT', 
																		'USEREMAIL' => 'EDIT', 
                                                                        'ENTITY' => '_pickEntity();', 
                                                                        'ROLE' => '_pickRole();', 
                                                                        'REGISTERED' => 'AUTO', 
                                                                        'DELETED' => 'AUTO', 
                                                                        'LAST_ACTIVITY' => 'AUTO',
                                                                        'LAST_LOGIN' => 'AUTO',
                                                                        'NUMSEPARATORS' => 'EDIT',
                                                                        'DATEFORMAT' => 'EDIT',
                                                                        'TIMEFORMAT' => 'EDIT'
                                                                      );

$GLOBALS['tablelabels'][$sqltable] = array ( 'id' => 'rec.',
                                                                  'REAL_NAME' => 'Formal name', 
                                                                  'USERNAME' => 'Username', 
                                                                  'PASSWORD' => 'Password', 
																  'USEREMAIL' => 'Email', 
                                                                  'ENTITY' => 'Unit', 
                                                                  'ROLE' => 'Role', 
                                                                  'REGISTERED' => 'Registered', 
                                                                  'DELETED' => 'Deleted', 
                                                                  'LAST_ACTIVITY' => 'Last activity',
                                                                  'LAST_LOGIN' => 'Last login',
                                                                  'NUMSEPARATORS' => 'Sep.',
                                                                  'DATEFORMAT' => 'Date',
                                                                  'TIMEFORMAT' => 'Time'
                                                                );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels'][$sqltable] = array ( 'id' => 'rec.',
			'REAL_NAME' => 'Nom officiel', 
			'USERNAME' => 'Nom d utilisateur', 
			'PASSWORD' => 'Mot de passe', 
			'USEREMAIL' => 'Email', 
			'ENTITY' => 'Unité', 
			'ROLE' => 'Rôle', 
			'REGISTERED' => 'Inscrit', 
			'DELETED' => 'Supprimé', 
			'LAST_ACTIVITY' => 'Dernière Activité',
			'LAST_LOGIN' => 'Dernière connexion',
			'NUMSEPARATORS' => 'Séparateurs',
			'DATEFORMAT' => 'Datte',
			'TIMEFORMAT' => 'Temps'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels'][$sqltable] = array ( 'id' => 'rec.',
			'REAL_NAME' => 'Nombre formal', 
			'USERNAME' => 'Nombre de usuario', 
			'PASSWORD' => 'Contraseña', 
			'USEREMAIL' => 'Email', 
			'ENTITY' => 'Unidad', 
			'ROLE' => 'Papel', 
			'REGISTERED' => 'Registrado', 
			'DELETED' => 'Eliminado', 
			'LAST_ACTIVITY' => 'Última actividad',
			'LAST_LOGIN' => 'Último acceso',
			'NUMSEPARATORS' => 'Separadores',
			'DATEFORMAT' => 'Fecha',
			'TIMEFORMAT' => 'Hora'
		);
		break;
}

																
$GLOBALS['tableheaders'][$sqltable] = <<<NXT
#id#
#SORT#REAL_NAME#
#USERNAME#
#PASSWORD#
#USEREMAIL#
#SORT#ENTITY#
#SORT#ROLE#
#SORT#LAST_LOGIN#
#NUMSEPARATORS#
#DATEFORMAT#
#TIMEFORMAT#
NXT;

$GLOBALS['tabledisplays'][$sqltable] = <<<NXT
#id#
#REAL_NAME#
#USERNAME#
#PASSWORD#
#USEREMAIL#
#ENTITY#
#ROLE#
#DATETIME#LAST_LOGIN#
#NUMSEPARATORS#
#DATEFORMAT#
#TIMEFORMAT#
NXT;

$GLOBALS['tablealigns'][$sqltable] = array ('NUMSEPARATORS' => 'center' );

/* ---------------------------------------------------------------- */

$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#id#
#REAL_NAME#
#USERNAME#
#PASSWORD#
#USEREMAIL#
#ENTITY#
#ROLE#
#NUMSEPARATORS#
#DATEFORMAT#
#TIMEFORMAT#
NXT;


?>

