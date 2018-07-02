<?php 
@session_start();

$sqltable = "parishes";
$query = <<<CREATE
CREATE TABLE $sqltable (
	id int UNSIGNED primary key not null auto_increment,
	DELETED BIGINT UNSIGNED,
	D_2010_NAME VARCHAR(46),
	D_2006_NAME VARCHAR(46),
	C_2006_NAME VARCHAR(46),
	S_2010_NAME VARCHAR(46),
	S_2006_NAME VARCHAR(46),
	P_2010_NAME VARCHAR(46),
	P_2006_NAME VARCHAR(46),
	SUBREGION VARCHAR(46),
	AREA_AREA DECIMAL (20, 2),
	AREA_PARAMETER DECIMAL (20, 2)
                                        )
CREATE;

$parishesTsql = new mySQLclass();
$parishesTsql->_sqlLookup ($query);
if ($parishesTsql->sql_result === false) 
   { if ($parishesTsql->_sqlerrNo () != 1050) $parishesTsql->_sqlerror (); 
   } else { $index = file ('parishes 2010.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                array_shift ($index);
                $i = 0;
                foreach ($index as &$value)
                             { $value = trim ($value, "\x00..\x1F");
                                $value = addslashes ($value);
                                $data = explode ("\t", $value);
                                foreach ($data as &$field) 
                                             { $field = trim ($field, "\x00..\x1F");
                                                $field = strtolower ($field);
                                                $field = ucwords ($field);
                                             }
                                $record = implode ("','", $data);
                                $query = <<<NXT
INSERT INTO `$sqltable` VALUES ('','$record')
NXT;
                                $parishesTsql->_sqlLookup ($query);
                                if ($parishesTsql->sql_result === false) $parishesTsql->_sqlerror ();
                                $i++;
                             }
            }

/* ---------------------------------------------------------------------------*/

$GLOBALS['searchfields']['parishes'] = array ( 'D_2010_NAME' => '%', 'C_2006_NAME' => '%', 'S_2010_NAME' => '%', 'P_2010_NAME' => '%');

$GLOBALS['newrecfields'][$sqltable] = array ( 'P_2010_NAME' => 46);

$GLOBALS['requiredfields']['parishes'] = array ( 'D_2010_NAME', 'D_2006_NAME', 'C_2006_NAME', 'S_2010_NAME', 'S_2006_NAME', 'P_2010_NAME', 'P_2006_NAME', 'SUBREGION', '', '');

$GLOBALS['insertnew'][$sqltable] = <<<NXT
INSERT INTO `$sqltable` VALUES ('', 0, '', '', '', '', '', '{$_POST['NEW_P_2010_NAME']}', '', '', '', '')
NXT;

$GLOBALS['tablehandlings']['parishes'] = array ( 'DELETED' => 'AUTO',
                                                                             'D_2010_NAME' => 'EDIT', 
                                                                             'D_2006_NAME' => 'EDIT', 
                                                                             'C_2006_NAME' => 'EDIT', 
                                                                             'S_2010_NAME' => 'EDIT', 
                                                                             'S_2006_NAME' => 'EDIT', 
                                                                             'P_2010_NAME' => 'EDIT', 
                                                                             'P_2006_NAME' => 'EDIT', 
                                                                             'SUBREGION' => 'EDIT',
																			 'AREA_AREA' => '_float(20,2,1,99999999999999999999);',
																			 'AREA_PARAMETER' => '_float(20,2,1,99999999999999999999);'
                                                                          );

$GLOBALS['tablelabels']['parishes'] = array ( 'D_2010_NAME' => 'District (2010)',
                                                                       'D_2006_NAME' => 'District (2006)',
                                                                       'C_2006_NAME' => 'County (2006)',
                                                                       'S_2010_NAME' => 'Subcounty (2010)',
                                                                       'S_2006_NAME' => 'Subcounty (2006)', 
                                                                       'P_2010_NAME' => 'Parish (2010)',
                                                                       'P_2006_NAME' => 'Parish (2006)', 
                                                                       'SUBREGION' => 'Subregion',
																	   'AREA_AREA' => 'Area',
																	   'AREA_PARAMETER' => 'Parameter'
                                                                    );

switch ($_SESSION['selLang']) {
	case "FR":
		$GLOBALS['tablelabels']['parishes'] = array ( 
			'D_2010_NAME' => 'District (2010)',
			'D_2006_NAME' => 'District (2006)',
			'C_2006_NAME' => 'Comté (2006)',
			'S_2010_NAME' => 'Sous-société (2010)',
			'S_2006_NAME' => 'Sous-société (2006)', 
			'P_2010_NAME' => 'Paroisse (2010)',
			'P_2006_NAME' => 'Paroisse (2006)', 
			'SUBREGION' => 'Sous-région',
			'AREA_AREA' => 'Région',
			'AREA_PARAMETER' => 'Paramètre'
		);
		break;
	case "ES":
		$GLOBALS['tablelabels']['parishes'] = array ( 
			'D_2010_NAME' => 'Distrito (2010)',
			'D_2006_NAME' => 'Distrito (2006)',
			'C_2006_NAME' => 'Condado (2006)',
			'S_2010_NAME' => 'Subconjunto (2010)',
			'S_2006_NAME' => 'Subconjunto (2006)', 
			'P_2010_NAME' => 'Parroquia (2010)',
			'P_2006_NAME' => 'Parroquia (2006)', 
			'SUBREGION' => 'Subregión',
			'AREA_AREA' => 'Zona',
			'AREA_PARAMETER' => 'Parámetro'
		);
		break;
}
																	
																	
$GLOBALS['tableheaders']['parishes'] = <<<NXT
#SORT#D_2010_NAME#
#D_2006_NAME#
#SORT#C_2006_NAME#
#SORT#S_2010_NAME#
#S_2006_NAME#
#SORT#P_2010_NAME#
#P_2006_NAME#
#SUBREGION#
#AREA_AREA#
#AREA_PARAMETER#
NXT;

$GLOBALS['tabledisplays']['parishes'] = <<<NXT
#D_2010_NAME#
#D_2006_NAME#
#C_2006_NAME#
#S_2010_NAME#
#S_2006_NAME#
#P_2010_NAME#
#P_2006_NAME#
#SUBREGION#
#AREA_AREA#
#AREA_PARAMETER#
NXT;

/* ---------------------------------------------------------------- */

$GLOBALS['pickheaders']['parishes'] = <<<NXT
#SORT#D_2010_NAME#
#SORT#C_2006_NAME#
#SORT#S_2010_NAME#
#SORT#P_2010_NAME#
#SUBREGION#
#AREA_AREA#
#AREA_PARAMETER#
NXT;

$GLOBALS['pickdisplays']['parishes'] = <<<NXT
#D_2010_NAME#
#C_2006_NAME#
#S_2010_NAME#
#P_2010_NAME#
#SUBREGION#
#AREA_AREA#
#AREA_PARAMETER#
NXT;

/* ---------------------------------------------------------------- */


$GLOBALS['recorddisplays'][$sqltable] = <<<NXT
#D_2010_NAME#
#D_2006_NAME#
#C_2006_NAME#
#S_2010_NAME#
#S_2006_NAME#
#P_2010_NAME#
#P_2006_NAME#
#SUBREGION#
#AREA_AREA#
#AREA_PARAMETER#
NXT;


?>

