<?php 
@session_start();

$errorlevel = error_reporting ();
error_reporting (E_ERROR | E_PARSE);

function __autoload($class_name) {
    require_once $class_name . '.php';
}

/* ------------------------------------------------------------------------ */

include 'style.php';

include 'settings.php';

$browser = getenv('HTTP_USER_AGENT');
if (strpos ($browser, "Firefox") > -1)
   { $cursor = "pointer";
   } else { $cursor = "hand";
             }

foreach ($_POST as $field => &$postedvalue) 
            { $postedvalue = trim ($postedvalue, "\x00..\x20");
               if ((substr ($field, 0, 4) == 'NEW_') && (substr ($field, -5) == '_NAME')) 
                  { //$postedvalue = strtolower ($postedvalue);
                     //$postedvalue = ucwords ($postedvalue);
                  }
               if (substr ($field, 0, 4) == 'NEW_') 
                  { if (! get_magic_quotes_gpc())
                        { $postedvalue = str_replace ( "'" , "\\'" , $postedvalue);
                           $postedvalue = str_replace ( "\"" , "\\\"" , $postedvalue);
                        }
                     $postedvalue = nl2br ( $postedvalue, false);
                     $postedvalue = str_replace ( "\n" , "" , $postedvalue);
                     $postedvalue = str_replace ( "\r" , "" , $postedvalue);
                  }
   }

/* ------------------------------------------------------------------------ */

function _getUserentity ($id)
{ if ($id == 0) return 'OMNI';
   $sql = new mySQLclass();
   $query = "SELECT * FROM users WHERE id = '$id'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result !== false) 
      { $row = mysql_fetch_assoc ($sql->sql_result);
         return $row['ENTITY'];
      } else { $sql->_sqlerror ();
                    return false;
                 }
}

function _checkSerial ($table, $column, $serial)
{ $sql = new mySQLclass();
   $query = "SELECT * FROM $table WHERE $column = '$serial'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result !== false) 
      { if (mysql_num_rows ($sql->sql_result) > 0) return true; else return false;
      } else { $sql->_sqlerror ();
                    return false;
                 }
}

/* ------------------------------------------------------------------------ */

function _timg ($_params) 
{ $params = str_replace (' ', '_', $_params);
   $params = str_replace ('=', '-', $params);
   $params = str_replace ('&', '+', $params);
   $params = str_replace ('.', '_', $params);
   $params = str_replace (',', '_', $params);
   $params = str_replace (array ('/', ':', ';'), '', $params);
   $file = "timgs/$params.png";
   if (file_exists ($file)) 
      { return <<<NXT
<IMG src="timgs/$params.png" border="0">
NXT;
      } else { return <<<NXT
<IMG src="timg.php?$_params" border="0">
NXT;
                }
}
$_timg = '_timg';

/* ------------------------------------------------------------------------ */

function _loggedIn ($id)
{ if (($id == 0) && ($_POST['role'] == 'GOD')) return true;
   $query = "SELECT * FROM users WHERE id = '$id'";
   $sql = new mySQLclass();
   $sql->_sqlLookup ($query);
   if ($row = mysql_fetch_assoc ($sql->sql_result))
      { if ((($row['LAST_ACTIVITY'] + $GLOBALS['timeout'] >= $GLOBALS['timestamp']) && ($row['DELETED'] <= 0)) || ($row['ROLE'] == 'GOD'))
           { $query = "UPDATE users SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}' WHERE id = '{$row['id']}'";
              $sql->_sqlLookup ($query);
              return true;
           } else { return false;
                     } 
      } else { return false;
                }
}

/* ------------------------------------------------------------------------ */

define("_view_", 8);
define("_edit_", 4);
define("_create_", 2);
define("_delete_", 1);

define("_notesimage0_", '<IMG src="images/nts00.png">');
define("_notesimage1_", '<IMG src="images/nts1.png">');
define("_notesimage2_", '<IMG src="images/nts2.png">');
define("_notesimage3_", '<IMG src="images/nts3.png">');
define("_notesimage4_", '<IMG src="images/nts4.png">');

define("_docsimage0_", '<IMG src="images/docs0.png">');
define("_docsimage1_", '<IMG src="images/docs1.png">');
define("_docsimage2_", '<IMG src="images/docs2.png">');
define("_docsimage3_", '<IMG src="images/docs3.png">');
define("_docsimage4_", '<IMG src="images/docs4.png">');

define("_recimage0_", '<IMG src="images/rec0.png">');
define("_recimage1_", '<IMG src="images/rec1.png">');

define("_tblimage0_", '<IMG src="images/tbl0.png">');
define("_tblimage1_", '<IMG src="images/tbl1.png">');
define("_tblimage2_", '<IMG src="images/tbl2.png">');

define("_gisimage0_", '<IMG src="images/gis0.png">');
define("_gisimage1_", '<IMG src="images/gis1.png">');
define("_gisimage2_", '<IMG src="images/gis2.png">');

define("_evidenceimage0_", '<IMG src="images/evidence0.png">');
define("_evidenceimage1_", '<IMG src="images/evidence1.png">');
define("_evidenceimage2_", '<IMG src="images/evidence2.png">');

define("_offendersimage0_", '<IMG src="images/offenders0.png">');
define("_offendersimage1_", '<IMG src="images/offenders1.png">');
define("_offendersimage2_", '<IMG src="images/offenders2.png">');

define("_defendantsimage0_", '<IMG src="images/defendants0.png">');
define("_defendantsimage1_", '<IMG src="images/defendants1.png">');
define("_defendantsimage2_", '<IMG src="images/defendants2.png">');

define("_sentenceimage0_", '<IMG src="images/sentence0.png">');
define("_sentenceimage1_", '<IMG src="images/sentence1.png">');
define("_sentenceimage2_", '<IMG src="images/sentence2.png">');


define("_profileimage_", '<IMG src="images/raps.png">');

define("_verdictsimage_", '<IMG src="images/verdicts.png">');

define("_casestatus1_", '<IMG src="images/casestatus1.png">');
define("_casestatus2_", '<IMG src="images/casestatus2.png">');

define ("_ugx_", '<SPAN style="font-family: Tfont; padding: 2 0 0 2;">¤</SPAN>');
$UGX = _ugx_;

$roles = array();
$portals = array();
$portallabels = array();
$permissions = array();
$conditions = array();
$langlabels = array();
if (isset($_POST['SEL_LANG'])) $_SESSION['selLang'] = $_POST['SEL_LANG'];
$rolesfile = file ($rootpath . '_uwa_conf/roles.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
switch ($_SESSION['selLang']) {
	case "FR":
		$rolesfile = file ($rootpath . '_uwa_conf/roles_FR.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		break;
	case "ES":
		$rolesfile = file ($rootpath . '_uwa_conf/roles_ES.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		break;
	default:
		$rolesfile = file ($rootpath . '_uwa_conf/roles.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
//$rolesfile = file ($rootpath . '_uwa_conf/roles.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($rolesfile as &$rolesthing)
            { $rolesegments = explode ("\t", $rolesthing);
               $empty = true;
               foreach ($rolesegments as &$rolesitem) 
                           { $rolesitem = trim ($rolesitem, "\x00..\x20");
                              if ($rolesitem != '') $empty = false;
                           }
               if ($empty) continue;
               if ($rolesegments[0] != '') 
                  { $roleidentifier = $rolesegments[0];
                     ${$roleidentifier} = array();
                     array_push ($roles, $roleidentifier);
                     continue;
                  }
               if ($rolesegments[1] != '') 
                  { $portaldentifier = $rolesegments[1];
                     ${$portaldentifier} = array();
                     if (! in_array($portaldentifier, $portals)) 
                       { array_push ($portals, $portaldentifier);
                          array_push ($portallabels, $rolesegments[2]);
                       }
                     continue;
                  }
               if ($rolesegments[2] != '') 
                  { $tableidentifier = $rolesegments[2];
                  }
               $_permissions = 0;
               if ($rolesegments[3] != '') $_permissions += _view_;
               if ($rolesegments[4] != '') $_permissions += _edit_;
               if ($rolesegments[5] != '') $_permissions += _create_;
               if ($rolesegments[6] != '') $_permissions += _delete_;
               $permissions[$roleidentifier][$portaldentifier][$tableidentifier] = $_permissions;
               if ((isset ($rolesegments[7])) && ($rolesegments[7] != '')) $conditions[$roleidentifier][$portaldentifier][$tableidentifier] = $rolesegments[7];
            }

/* ------------------------------------------------------------------------ */

$ERRORS = array ();

/* ------------------------------------------------------------------------ */

$tableheaders = array ();
$tabledisplays = array ();
$tablehandlings = array ();
$requiredfields = array ();

$tablenames = array ('users' => 'Users', 'tables' => 'Data tables', 'suspects' => 'Suspects', 'arrests' => 'Arrests', 'evidencetable' => 'Evidence', 'offenderstable' => 'Offenders', 'defendantstable' => 'Accused', 'sentencetable' => 'Sentence', 'cases' => 'Court cases', 'courts' => 'Courts', 'verdictstable' => 'Verdicts');

/* ---------------------------------------------------------------- */

$roleselect = "";
foreach ($GLOBALS['roles'] as $role)
             { if ($role != 'REFERENCE') $roleselect .= <<<NXT
<OPTION value="$role">$role

NXT;
             } 
$roleselect = <<<NXT
<SELECT name="NEW_ROLE" class="btn">
<OPTION value="">-select-
$roleselect
</SELECT>
NXT;

/* ---------------------------------------------------------------- */

$entities = array ('HQ', 'QECA', 'QECA-QENP', 'QECA-RMNP', 'QECA-Ishasha', 'MFCA', 'MFCA-MFNP', 'MFCA-EMWR', 'MFCA-BWR', 'MFCA-AWR', 'KVCA', 'KVCA-KVNP', 'BMCA', 'BMCA-BINP', 'BMCA-MGNP', 'MECA', 'MECA-MENP', 'MECA-PUWR', 'MECA-MBWR', 'KCA', 'KCA-KNP', 'KCA-KWR', 'KCA-SNP', 'KCA-TSWR', 'LMNP', 'LMCA-Ranches' , 'Not associated with PA');

$entitiesselect = "";
foreach ($GLOBALS['entities'] as $entity)
             { $entitiesselect .= <<<NXT
<OPTION value="$entity">$entity

NXT;
             } 
$entitiesselect = <<<NXT
<SELECT name="NEW_ENTITY" class="btn">
<OPTION value="">-select-
$entitiesselect
</SELECT>
NXT;


/* ---------------------------------------------------------------- */

$edlevels = array ('none', 'P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'S1', 'S2', 'S3', 'S4', 'S5', 'S6', 'University', 'Vocational');

$edlevelsselect = "";
foreach ($GLOBALS['edlevels'] as $edlevel)
             { $edlevelsselect .= <<<NXT
<OPTION value="$edlevel">$edlevel

NXT;
             } 
$edlevelsselect = <<<NXT
<SELECT name="NEW_EDUCATION" class="btn">
<OPTION value="">-select-
$edlevelsselect
</SELECT>
NXT;


/* ---------------------------------------------------------------- */

function _makePath ($rootpath, $path)
{ $currentdir = getcwd ();
   if (! chdir ($rootpath)) return false; 
   $insofar = $rootpath;
   $dirs = explode ("/", $path);
   foreach ($dirs as $dir)
                { mkdir ("$insofar$dir");
                   if (! chdir ("$insofar/$dir"))  
                      { chdir ($currentdir);
                         return false; 
                      } else $insofar = "$insofar$dir/";
                }
   chdir ($currentdir);
return true;
}

/* ---------------------------------------------------------------- */

// $values = string of values separated by '~'
function _makeSelect ($name, $values)
{ $selarr = explode ("~", $values);
   foreach ($selarr as $item)
             { $select .= <<<NXT
<OPTION value="$item">$item

NXT;
             } 
   $select = <<<NXT
<SELECT name="$name" class="btn">
<OPTION value="">-select-
$select
</SELECT>
NXT;
return $select;
}

/* ---------------------------------------------------------------- */

function _makePath2 ($path)
{ $currentdir = getcwd ();
   $dirs = explode ("/", $path);
   foreach ($dirs as $dir)
                { mkdir ("$dir");
                   if (! chdir ("$dir"))  
                      { chdir ($currentdir);
                         return false; 
                      }
                }
   chdir ($currentdir);
return true;
}

/* ---------------------------------------------------------------- */

function _makeArray ($arrayname, $table, $keyfield, $valuefield)
{ $sql = new mySQLclass();
   $query = "SELECT * FROM $table";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror (); 
   $GLOBALS[$arrayname] = array ();
   while ($row = mysql_fetch_assoc ($sql->sql_result))
            { $key = $row[$keyfield];
               $value = $row[$valuefield];
               $GLOBALS[$arrayname][$key] = $value;
            }
}


/* ---------------------------------------------------------------- */

$_outposts = $entities;
array_push ($_outposts, 'Airport', 'Border post', 'N/A');
$outposts = implode ('~', $_outposts);

$evidencelocations = "$outposts~Police~Court store";

/* ---------------------------------------------------------------- */

$perpetrationtypes = <<<NXT
Meat hunting
Trophy hunting
Ivory poaching-Elephants killed
Ivory poaching-Tusks (No)
Ivory poaching-Raw ivory
Ivory poaching-Semi-worked
Ivory poaching-Carved ivory
Harvesting-Grass thatch
Harvesting-Firewood
Harvesting-Climbers (rope)
Harvesting-Rattan cane
Harvesting-building poles
Harvesting-planks
Harvesting-trees
Farming-Maize
Farming-Wheat
Farming-Beans
Farming-Potatoes
Farming-Irish
Farming-Peas
Farming-Banana
Grazing-Goats
Grazing-Sheep
Grazing-Cattle
Fishing
NXT;

$perpetrationtypes = str_replace ("\n", "~", $perpetrationtypes);

/* ---------------------------------------------------------------- */

$evidencetypes = <<<NXT
Firearms
Spears
Bows
Arrows
Machetes
Hand saws
Power saws
Axes
Bicycles
Motorbike
Vehicles
Jerrycans
Tents
Blankets
Basins
Plates & cutlery
Fishing nets
Fish traps
Boats
Animal traps
Domestic dogs
Hand hoes
Charcoal
Panga on machete
Other items
Other evidence
NXT;

$evidencetypes2 = str_replace ("\n", "~", $evidencetypes);
$evidencetypes = str_replace ("\n", "~", $evidencetypes);

/* ---------------------------------------------------------------- */

$evidencetypes = $perpetrationtypes . "~" . $evidencetypes;

/* ---------------------------------------------------------------- */

/*-----------------Commented, as fetched from table----------------
$species = <<<NXT
Elephants
Hippopotamus
Giraffe
Zebra
Uganda Kob
Topi
Eland
Hartebeest
Waterbuck
Oribi
Roan antelope
Black-fronted duiker
Yellow-backed duiker
Weyns duiker
Bush duiker
Giant Forest Hog
Warthog
Bushpig
Crocodile
Lion
Leopard
Spotted hyaena
Chimpanzee
Mountain gorilla
Red Colobus
Guereza colobus
Angolan colobus
Python
Chamaeleons
Tortoises
Bird(s)-
Catfish
Lungfish
Tilapia
Nile Perch
Other species-
NXT;

$species = str_replace ("\n", "~", $species);
------------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

$howdetectedtypes = <<<NXT
Routine patrol by rangers
Routine security check
Arrest in accordance with warrant
Ambush patrol based on intelligence
House visit based on intelligence
Observed during non-duty activities
Contacted by security agencies
Intelligence-led patrol
Other
NXT;

$howdetectedtypes = str_replace ("\n", "~", $howdetectedtypes);

/* ---------------------------------------------------------------- */

$motivationtypes = <<<NXT
Monetary
Sustenance
Cultural
Other
NXT;

$motivationtypes = str_replace ("\n", "~", $motivationtypes);

/* ---------------------------------------------------------------- */

$actiontypes = <<<NXT
Released - presumed innocent
Released with warning
Released on Police bond
In custody pending investigation
Charged
Address mark
Caution and release
NXT;

$actiontypes = str_replace ("\n", "~", $actiontypes);

/* ---------------------------------------------------------------- */

/*-----------------Commented, as fetched from table----------------

$verdicts = <<<NXT
Pending
Withdrawn by prosecutor
Dismissed by court
Acquitted
Cautioned
Fined
Prison term
Community service
other-
NXT;

$verdicts = str_replace ("\n", "~", $verdicts);

------------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

/*-----------------Commented, as fetched from table----------------

$crimetypes = <<<NXT
Hunting for meat
Hunting for trophies
Ivory poaching
Illegal harvesting
Illegal farming
Illegal grazing
Illegal fishing
Illegal possesion
Illegal entry
Possesion of firearms
Accessory to crime
Other illegal activities
NXT;

$crimetypes = str_replace ("\n", "~", $crimetypes);

------------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

function _makeSelectFromTable ($table, $valuecolumn, $labelcolumn, $varname)
{ $sql = new mySQLclass();
   $query = "SELECT $valuecolumn, $labelcolumn from $table WHERE DELETED = 0";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror (); 
      else { $select = <<<NXT
<SELECT name="$varname" class="btn">
<OPTION value="">-select-

NXT;
                 while ($row = mysql_fetch_assoc ($sql->sql_result)) $select .= <<<NXT
<OPTION value="{$row[$valuecolumn]}">{$row[$labelcolumn]}

NXT;
                 $select .= <<<NXT
</SELECT>

NXT;
              }
return $select;
}

function _makeArrayFromTable ($table, $valuecolumn)
{ $sql = new mySQLclass();
   $query = "SELECT $valuecolumn from $table WHERE DELETED = 0";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror (); 
      else { $select = array (); 
                while ($row = mysql_fetch_assoc ($sql->sql_result)) $select[] = $row[$valuecolumn];
              }
return $select;
}

/* ---------------------------------------------------------------- */

function _today ($timestp)
{ $timestamparr = getdate ($timestp);
   $timestamparr2day = getdate ($GLOBALS['timestamp']);
   if ($timestamparr['mday'] == $timestamparr2day['mday']) return true; else return false;
}

function _deleteAllowed ()
{ if (file_exists ("{$GLOBALS['confpath']}/lastdeletedate.txt"))
     { $ldld = file ("{$GLOBALS['confpath']}/lastdeletedate.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        list ($date, $deletes) = explode ("\t", $ldld[0]);
        if (_today ($date))
          { if ($deletes < 5)
               { $deletes = $deletes + 1;
                  $fop = fopen ("{$GLOBALS['confpath']}/lastdeletedate.txt", "w");
                  fwrite ($fop, "{$GLOBALS['timestamp']}\t$deletes\n");
                  fclose ($fop);
                  return true;
               } else return false;
          } else { $fop = fopen ("{$GLOBALS['confpath']}/lastdeletedate.txt", "w");
                     fwrite ($fop, "{$GLOBALS['timestamp']}\t1\n");
                     fclose ($fop);
                     return true;
                   }
     } else { $fop = fopen ("{$GLOBALS['confpath']}/lastdeletedate.txt", "w");
                fwrite ($fop, "{$GLOBALS['timestamp']}\t1\n");
                fclose ($fop);
                return true;
              }
}

/* ---------------------------------------------------------------- */

?>

