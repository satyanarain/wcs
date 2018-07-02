<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

$table = $_POST['table'];

include_once "$table.php";
include_once 'fields.php';		// for the _checkFields() & _addSlashes () functions

/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */

$sql = new mySQLclass();
                               
/*
$deb = fopen ("tmp.txt", "w");
$rr = print_r ($fieldlengths, true);
fwrite ($deb, $rr);
fclose ($deb);
*/

$list = "";
$newfields = implode (";", $GLOBALS['newrecfields'][$table]);
foreach ($GLOBALS['newrecfields'][$table] as $field => $len) 
             { $name = "NEW_$field";
                if ($len > 0) 
                   { $htmlfield = <<<NXT
<INPUT type="TEXT" class="btn" size="24" maxlength="$len" name="$name" value="{$_POST["NEW_$name"]}">
NXT;
                   }
else { $handling = $GLOBALS['tablehandlings'][$table][$field];
         $function = substr ($handling, 0, strpos ($handling, '('));
         $function = trim ($function, "\x00..\x20");
         if ($function == '_select') 
            { $funky = str_replace ('_select', '_makeSelect', $handling);
              eval ("\$htmlfield = $funky");
            }
         if ($function == '_pickEducation') $htmlfield = $GLOBALS['edlevelsselect'];
         if ($function == '_selectCountry') $htmlfield = _makeSelectFromTable ('countries', 'CC', 'COUNTRY_NAME', 'NEW_SUSPECT_NATIONALITY');
         if ($function == '_selectCourt') $htmlfield = _makeSelectFromTable ('courts', 'COURT_NAME', 'COURT_NAME', 'NEW_COURT_NAME');
         if ($function == '_selectCourt1') $htmlfield = _makeSelectFromTable ('courts', 'COURT_NAME', 'COURT_NAME', 'NEW_COURT_TO');
         if ($function == '_selectSpecies') $htmlfield = _makeSelectFromTable ('species', 'SPECIES_NAME', 'SPECIES_NAME', 'NEW_SPECIES_NAME');
         if ($function == '_selectVerdict') $htmlfield = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_VERDICT_NAME');
         if ($function == '_selectVerdict1') $htmlfield = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_OPT1');
         if ($function == '_selectVerdict2') $htmlfield = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_OPT2');
         if ($function == '_selectCrime') $htmlfield = _makeSelectFromTable ('crimes', 'CRIME_NAME', 'CRIME_NAME', 'NEW_CRIME_TYPE');
         if ($field == 'APPEALED') $htmlfield = _makeSelect ('NEW_APPEALED', 'yes');
         if ($function == '_int') $htmlfield = <<<NXT
<INPUT type="TEXT" class="btn" size="12" maxlength="8" name="$name" value="{$_POST["NEW_$name"]}">
NXT;
     }

                if (in_array ($field, $GLOBALS['requiredfields'][$table])) $req = '<B style="color: #A00000;"> *</B>'; else $req = "";
                $list .= <<<NXT
<TR><TD align="right">{$GLOBALS['tablelabels'][$table][$field]}$req</TD><TD>$htmlfield</TD></TR>

NXT;
             }


if (isset ($_POST['asstable'])) $asstable = $_POST['asstable']; else $asstable = "";
if (isset ($_POST['assrec'])) $assrec = $_POST['assrec']; else $assrec = "";

$addnew = '<TR><TD colspan="2" class="th">Add NEW entry</TD></TR>';
$addEntry = 'ADD entry';
$cancel = 'Cancel';
switch ($_SESSION['selLang']) {
	case "FR":
		$addnew = '<TR><TD colspan="2" class="th">Ajouter une nouvelle entrée</TD></TR>';
		$addEntry = 'Ajouter une entrée';
		$cancel = 'Annuler';
		break;
	case "ES":
		$addnew = '<TR><TD colspan="2" class="th">Agregar NUEVA entrada</TD></TR>';
		$addEntry = 'Agregar entrada';
		$cancel = 'Cancelar';
		break;
}


$list = <<<NXT
<TABLE cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="newentry" method="POST" action="new.record.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="fields" value="$newfields">
<INPUT type="HIDDEN" name="asstable" value="$asstable">
<INPUT type="HIDDEN" name="assrec" value="$assrec">
$addnew
$list
<TR><TD align="center"><INPUT type="SUBMIT" Value='$addEntry' class="btn" onclick="document.getElementById('viewtable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='$cancel' onclick="parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;

/* ---------------------------------------------------------------- */

$load = "";

$newvalues = false;
foreach ($GLOBALS['newrecfields'][$table] as $field => $len) 
             { $name = "NEW_$field";
                if ($_POST[$name]) $newvalues = true;
             }

if ($newvalues) $required = <<<NXT
<DIV align="center" style="background: #802000; color: #F7F6F2;">
<B>One or more required fields are not set / filled !
</DIV>
<P>
NXT;
    else $required = "";

if (_checkFieldsNewRec ($table) ||$newvalues)
   { //_addSlashes ();
      $query = $GLOBALS['insertnew'][$table];
      $sql->_sqlLookup ($query);
      if ($sql->sql_result === false) 
          $sql->_sqlerror (); 
          else { $newID = mysql_insert_id();
                     $func = "_onInsertNew_$table";
                     if (function_exists ($func)) $func ($table, $newID, $asstable, $assrec);
                     $query = "UPDATE tables SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}' WHERE TABLENAME = '$table'";
                     $sql->_sqlLookup ($query);
                     $list = "<B>DONE";
                     if (isset ($GLOBALS['recorddisplays'][$table])) $tableview = "parent.document.showtable.showrec.value = '$newID';"; else $tableview = "";
                     $load = <<<NXT
onload="
$tableview
parent.document.getElementById('newtable').style.visibility = 'hidden'; 
parent.document.showtable.submit();"
NXT;
                 }
   } else { $list = <<<NXT
$required
$list
NXT;
             }

/* ---------------------------------------------------------------- */

$meta = '<META http-equiv=Content-Type content="text/html; charset=utf-8">';

$page = <<<NXT
<HTML>
<HEAD>

$meta
<META http-equiv="expires" content="0">
</HEAD>

$style

<BODY style="margin: 8;" $load>
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
<DIV ID="viewtable">
$list 
</DIV>
</TD>
</TR>
</TABLE>
</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

