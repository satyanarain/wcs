<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';
include 'fields.php';		// for the _checkFields(), _addSlashes () & _setField ($table, $field, $id, $value) functions

/* ---------------------------------------------------------------- */

include "{$_POST['table']}.php";		

$handling = $GLOBALS['tablehandlings'][$_POST['table']][$_POST['field']];
$handling = str_replace ('"', "'", $handling);

if (isset ($_POST['asstable'])) $asstable = $_POST['asstable']; else $asstable = "";
if (isset ($_POST['assrec'])) $assrec = $_POST['assrec']; else $assrec = "";

$EditFieldTxt = "Edit field:";
$SavingNewValueTxt = "Saving new value";
$NotImplementedTxt = "Not implemented:";
$NewValueTxt = "New value:";
$SaveBtn = "Save";
$CancelBtn = "Cancel";
$CloseBtn = "Close";
$PickDateTxt = "Pick date";
$EducationTxt = "Education level:";
switch ($_SESSION['selLang']) {
	case "FR":
		$EditFieldTxt = "Modifier le champ:";
		$SavingNewValueTxt = "Enregistrer une nouvelle valeur";
		$NotImplementedTxt = "Pas mis en œuvre:";
		$NewValueTxt = "Nouvelle valeur:";
		$SaveBtn = "Sauvegarder";
		$CancelBtn = "Annuler";
		$CloseBtn = "Fermer";
		$PickDateTxt = "Choisir une date";
		$EducationTxt = "Niveau d'éducation";
		break;
	case "ES":
		$EditFieldTxt = "Editar campo:";
		$SavingNewValueTxt = "Guardar un nuevo valor";
		$NotImplementedTxt = "No se ha implementado:";
		$NewValueTxt = "Nuevo valor:";
		$SaveBtn = "Salvar";
		$CancelBtn = "Cancelar";
		$CloseBtn = "Cerca";
		$PickDateTxt = "Seleccione fecha";
		$EducationTxt = "Nivel de Educación";
		break;
}   


$list = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<TR><TD colspan="2" class="th">$EditFieldTxt {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">$NotImplementedTxt</TD><TD>$handling</TD></TR>
<TR><TD align="center" colspan="2"><INPUT type="BUTTON" class="btn" Value=$CloseBtn onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR></TABLE>
NXT;

/* ---------------------------------------------------------------- */

if ((isset ($_POST['year'])) && (isset ($_POST['month'])) && (isset ($_POST['day'])))
   $_POST['NEW_VALUE'] = mktime (12, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']);

if (isset ($_POST['NEW_VALUE'])) $fieldvalue = htmlentities ($_POST['NEW_VALUE'], ENT_QUOTES); 
  else { $fieldsql = new mySQLclass();
            $query = "SELECT * FROM {$_POST['table']} WHERE id = '{$_POST['record']}'";
            $fieldsql->_sqlLookup ($query);
            if ($fieldsql->sql_result === false) $fieldsql->_sqlerror (); 
            $row = mysql_fetch_assoc ($fieldsql->sql_result);
            $fieldvalue = htmlentities (urldecode ($row[$_POST['field']]), ENT_QUOTES);
            mysql_free_result ($fieldsql->sql_result);
          }


$load = "";

/* ---------------------------------------------------------------- */

if ($handling == 'EDIT')
{
$size = $_POST['size'];
$list = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="$asstable">
<INPUT type="HIDDEN" name="assrec" value="$assrec">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD><INPUT type="TEXT" class="btn" size="40" maxlength="$size" name="NEW_VALUE" value="$fieldvalue"></TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
}

/* ---------------------------------------------------------------- */

function _select ($name, $values)
{ $sel = _makeSelect ('NEW_VALUE', $values);
$sel = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$sel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $sel;
}

/* ---------------------------------------------------------------- */

function _selectCountry ($name)
{ $sel = _makeSelectFromTable ('countries', 'CC', 'COUNTRY_NAME', 'NEW_VALUE');
$sel = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$sel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $sel;
}

/* ---------------------------------------------------------------- */

function _selectCourt ($name)
{ $sel = _makeSelectFromTable ('courts', 'COURT_NAME', 'COURT_NAME', 'NEW_VALUE');
$sel = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$sel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $sel;
}

/* ---------------------------------------------------------------- */

function _selectCourt1 ($name)
{ $sel = _makeSelectFromTable ('courts', 'COURT_NAME', 'COURT_NAME', 'NEW_VALUE');
$sel = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$sel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $sel;
}

/* ---------------------------------------------------------------- */

function _selectSpecies ($name)
{ $selSpecies = _makeSelectFromTable ('species', 'SPECIES_NAME', 'SPECIES_NAME', 'NEW_VALUE');
$selSpecies = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$selSpecies</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $selSpecies;
}

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

function _selectCrime ($name)
{ $selCrime = _makeSelectFromTable ('crimes', 'CRIME_NAME', 'CRIME_NAME', 'NEW_VALUE');
$selCrime = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$selCrime</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $selCrime;
}

/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */

function _selectVerdict ($name)
{ $selVerdict = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_VALUE');
$selVerdict = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$selVerdict</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $selVerdict;
}

/* ---------------------------------------------------------------- */

function _selectVerdict1 ($name)
{ $selVerdict1 = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_VALUE');
$selVerdict1 = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$selVerdict1</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $selVerdict1;
}

/* ---------------------------------------------------------------- */

function _selectVerdict2 ($name)
{ $selVerdict2 = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_VALUE');
$selVerdict2 = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$selVerdict2</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $selVerdict2;
}

/* ---------------------------------------------------------------- */

function _selectVerdict3 ($name)
{ $selVerdict3 = _makeSelectFromTable ('verdicts', 'VERDICT_NAME', 'VERDICT_NAME', 'NEW_VALUE');
$selVerdict3 = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$selVerdict3</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="document.editfield.NEW_VALUE.value = '{$GLOBALS['fieldvalue']}';"
NXT;
return $selVerdict3;
}

/* ---------------------------------------------------------------- */

// value segment separated by '/'

function _selectChkMulti ($name, $values)
{ $possiblevalues = explode ('~', $values);
   $oldvalues = explode ('/', $GLOBALS['fieldvalue']);
   $sel = '';
   $jav = '';
   $i = 0;
   foreach ($possiblevalues as $possiblevalue)
                { $i++;
                   if (in_array ($possiblevalue, $oldvalues)) $chk = " checked"; else $chk = "";
                   $sel .= <<<NXT
<INPUT type="CHECKBOX" name="$name$i" value="$possiblevalue"$chk>$possiblevalue
<BR>
NXT;
  $jav .= <<<NXT
if (document.editfield.$name$i.checked) 
   { if (valcompile != '') 
         valcompile = valcompile + '/' + document.editfield.$name$i.value;
         else valcompile = document.editfield.$name$i.value;
   }
NXT;

                }
  $sel = <<<NXT
<SCRIPT language="JavaScript">
var valcompile = "";
function checkvalue ()
{ valcompile = "";
   $jav
   document.editfield.NEW_VALUE.value = valcompile;
}
</SCRIPT>
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="{$GLOBALS['fieldvalue']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>$sel</TD></TR>

<TR><TD align="center"><INPUT type="BUTTON" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="checkvalue (); document.editfield.submit(); document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;

return $sel;
}

/* ---------------------------------------------------------------- */

function _text ($name, $cols, $rows, $trunc)
{ $text = str_replace ("<br>", "\n", html_entity_decode ($GLOBALS['fieldvalue']));
   $sel = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD>
<TEXTAREA name="NEW_VALUE" cols="$cols" rows="$rows" class="btn">$text</TEXTAREA>
</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
return $sel;
}

/* ---------------------------------------------------------------- */

function _int ($min, $max, $size)
{ $int = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="check.int.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="min" value="$min">
<INPUT type="HIDDEN" name="max" value="$max">
<INPUT type="HIDDEN" name="len" value="$size">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD><INPUT type="TEXT" class="btn" size="20" maxlength="$size" name="NEW_VALUE" value="{$GLOBALS['fieldvalue']}"></TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>

NXT;
return $int;
}

/* ---------------------------------------------------------------- */

function _float ($size, $decimals, $min, $max)
{ $float = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="check.float.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="min" value="$min">
<INPUT type="HIDDEN" name="max" value="$max">
<INPUT type="HIDDEN" name="len" value="$size">
<INPUT type="HIDDEN" name="decimals" value="$decimals">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD><INPUT type="TEXT" class="btn" size="20" maxlength="$size" name="NEW_VALUE" value="{$GLOBALS['fieldvalue']}"></TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>

NXT;
return $float;
}

/* ---------------------------------------------------------------- */

function _pickDate($msg, $label)
{ $fieldsql = new mySQLclass();
   $query = "SELECT * FROM {$_POST['table']} WHERE id = '{$_POST['record']}'";
   $fieldsql->_sqlLookup ($query);
   if ($fieldsql->sql_result === false) $fieldsql->_sqlerror (); 
   $row = mysql_fetch_assoc ($fieldsql->sql_result);
   $fieldvalue = $row[$_POST['field']];
   $title = $label;
   foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
   mysql_free_result ($fieldsql->sql_result);
   if ($fieldvalue)
      { $cal = getdate ($fieldvalue);
         $currentdate = <<<NXT
<INPUT type="HIDDEN" name="year" value="{$cal['year']}">
<INPUT type="HIDDEN" name="month" value="{$cal['mon']}">
<INPUT type="HIDDEN" name="day" value="{$cal['mday']}">

NXT;
      } else $currentdate = "";
   $pr = <<<NXT
<TABLE width="100%" height="230" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="calform" method="POST" action="calendar.php" target="calframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
$currentdate
<TR><TD Valign="top">
<DIV class="th" style="padding: 0 2 0 2;">
{$GLOBALS['PickDateTxt']}
</DIV>
<P>
<B>$msg</B>
<P>
$title
<P>
<INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';">
</TD>
<TD>
<Iframe ID="calframe" name="calframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = "onload=\"document.calform.submit();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickRecordId ($fromtable, $SQLQUERY)
{ include_once "$fromtable.php";
   $pr = <<<NXT
<TABLE width="100%" height="230" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="parform" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<TR><TD>
<B>
Opening table for selection,
<BR>please wait...
<P>
&nbsp;
<P>
<INPUT type="BUTTON" class="btn" Value="Cancel" 
onclick="document.getElementById('viewtable').style.visibility = 'hidden'; 
parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
parent.document.getElementById('edittable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
NXT;
$GLOBALS['load'] = <<<NXT
onload="
parent.document.getElementById('pickrecordtable').style.visibility = 'visible';
parent.document.getElementById('pickrecordframe').src = 'pickrecord.php?id={$_POST['id']}&role={$_POST['role']}&picktable=$fromtable&SQLQUERY=$SQLQUERY';
"
NXT;
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickParish ($label)
{ $fieldsql = new mySQLclass();
   $query = "SELECT * FROM {$_POST['table']} WHERE id = '{$_POST['record']}'";
   $fieldsql->_sqlLookup ($query);
   if ($fieldsql->sql_result === false) $fieldsql->_sqlerror (); 
   $row = mysql_fetch_assoc ($fieldsql->sql_result);
   $fieldvalue = $row[$_POST['field']];
   $title = $label;
   foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
   mysql_free_result ($fieldsql->sql_result);
   $pr = <<<NXT
<TABLE width="100%" height="230" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="pickform" method="POST" action="pick.php" target="pickframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="match" value="">
<INPUT type="HIDDEN" name="picktable" value="parishes">
<INPUT type="HIDDEN" name="pickfield" value="P_2010_NAME">
<INPUT type="HIDDEN" name="returnfield" value="P_2010_NAME">
<INPUT type="HIDDEN" name="filter" value="D_2010_NAME,C_2006_NAME,S_2010_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<FORM name="parform" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<TR><TD>
<DIV class="th">
Pick parish
</DIV>
<P>
$title
<P>
<INPUT type="TEXT" class="btn" name="picked" value="" 
onkeyup="
if (value.length >= 1) 
   { document.pickform.match.value = value;
      document.pickform.submit();
   } else document.getElementById('pickframe').style.visibility = 'hidden';
">
<P>
<INPUT type="BUTTON" class="btn" Value="Cancel" onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
<Iframe name="pickframe" ID="pickframe" width="140" height="232" frameborder="0" SRC=""  
style="position: absolute; left: 204; top: 0; visibility: hidden;"></Iframe>
NXT;
$GLOBALS['load'] = "onload=\"document.getElementById('picked').focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickSubCounty ($label)
{ $fieldsql = new mySQLclass();
   $query = "SELECT * FROM {$_POST['table']} WHERE id = '{$_POST['record']}'";
   $fieldsql->_sqlLookup ($query);
   if ($fieldsql->sql_result === false) $fieldsql->_sqlerror (); 
   $row = mysql_fetch_assoc ($fieldsql->sql_result);
   $fieldvalue = $row[$_POST['field']];
   $title = $label;
   foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
   mysql_free_result ($fieldsql->sql_result);
   $pr = <<<NXT
<TABLE width="100%" height="230" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="pickform" method="POST" action="pick.php" target="pickframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="match" value="">
<INPUT type="HIDDEN" name="picktable" value="parishes">
<INPUT type="HIDDEN" name="pickfield" value="S_2010_NAME">
<INPUT type="HIDDEN" name="returnfield" value="S_2010_NAME">
<INPUT type="HIDDEN" name="filter" value="D_2010_NAME,C_2006_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<FORM name="parform" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="">
<INPUT type="HIDDEN" name="clear_fields" value="P_2010_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<TR><TD>
<DIV class="th">
Pick subcounty
</DIV>
<P>
$title
<P>
<INPUT type="TEXT" class="btn" name="picked" value="" 
onkeyup="
if (value.length >= 1) 
   { document.pickform.match.value = value;
      document.pickform.submit();
   } else document.getElementById('pickframe').style.visibility = 'hidden';
">
<P>
<INPUT type="BUTTON" class="btn" Value="Cancel" onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
<Iframe name="pickframe" ID="pickframe" width="140" height="232" frameborder="0" SRC=""  
style="position: absolute; left: 204; top: 0; visibility: hidden;"></Iframe>
NXT;
$GLOBALS['load'] = "onload=\"document.getElementById('picked').focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickCounty ($label)
{ $fieldsql = new mySQLclass();
   $query = "SELECT * FROM {$_POST['table']} WHERE id = '{$_POST['record']}'";
   $fieldsql->_sqlLookup ($query);
   if ($fieldsql->sql_result === false) $fieldsql->_sqlerror (); 
   $row = mysql_fetch_assoc ($fieldsql->sql_result);
   $fieldvalue = $row[$_POST['field']];
   $title = $label;
   foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
   mysql_free_result ($fieldsql->sql_result);
   $pr = <<<NXT
<TABLE width="100%" height="230" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="pickform" method="POST" action="pick.php" target="pickframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="match" value="">
<INPUT type="HIDDEN" name="picktable" value="parishes">
<INPUT type="HIDDEN" name="pickfield" value="C_2006_NAME">
<INPUT type="HIDDEN" name="returnfield" value="C_2006_NAME">
<INPUT type="HIDDEN" name="filter" value="D_2010_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<FORM name="parform" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="">
<INPUT type="HIDDEN" name="clear_fields" value="S_2010_NAME,P_2010_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<TR><TD>
<DIV class="th">
Pick county
</DIV>
<P>
$title
<P>
<INPUT type="TEXT" class="btn" name="picked" value="" 
onkeyup="
if (value.length >= 1) 
   { document.pickform.match.value = value;
      document.pickform.submit();
   } else document.getElementById('pickframe').style.visibility = 'hidden';
">
<P>
<INPUT type="BUTTON" class="btn" Value="Cancel" onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';">
</TD>
</TR>
</FORM>
<Iframe name="pickframe" ID="pickframe" width="140" height="232" frameborder="0" SRC=""  
style="position: absolute; left: 204; top: 0; visibility: hidden;"></Iframe>
NXT;
$GLOBALS['load'] = "onload=\"document.getElementById('picked').focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */


function _pickDistrict ($label)
{ $fieldsql = new mySQLclass();
   $query = "SELECT * FROM {$_POST['table']} WHERE id = '{$_POST['record']}'";
   $fieldsql->_sqlLookup ($query);
   if ($fieldsql->sql_result === false) $fieldsql->_sqlerror (); 
   $row = mysql_fetch_assoc ($fieldsql->sql_result);
   $fieldvalue = $row[$_POST['field']];
   $title = $label;
   foreach ($row as $identifier => $value) $title = str_replace ($identifier, $value, $title);
   mysql_free_result ($fieldsql->sql_result);
   $pr = <<<NXT
<TABLE width="100%" height="230" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="pickform" method="POST" action="pick.php" target="pickframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="match" value="">
<INPUT type="HIDDEN" name="picktable" value="parishes">
<INPUT type="HIDDEN" name="pickfield" value="D_2010_NAME">
<INPUT type="HIDDEN" name="returnfield" value="D_2010_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<FORM name="parform" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="">
<INPUT type="HIDDEN" name="clear_fields" value="C_2006_NAME,S_2010_NAME,P_2010_NAME">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
</FORM>
<TR><TD>
<DIV class="th">
Pick district
</DIV>
<P>
$title
<P>
<INPUT type="TEXT" class="btn" ID="picked" value="" 
onkeyup="
if (value.length >= 1) 
   { document.pickform.match.value = value;
      document.pickform.submit();
   } else document.getElementById('pickframe').style.visibility = 'hidden';
">
<P>
<INPUT type="BUTTON" class="btn" Value="Cancel" onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';">
</TD>
</TR>
</TABLE>
<Iframe name="pickframe" ID="pickframe" width="140" height="232" frameborder="0" SRC=""  
style="position: absolute; left: 204; top: 0; visibility: hidden;"></Iframe>
NXT;
$GLOBALS['load'] = "onload=\"document.getElementById('picked').focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickRole()
{ $rolesel = $GLOBALS['roleselect'];
   $rolesel = str_replace ("NEW_ROLE", "NEW_VALUE", $rolesel);
   $pr = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">New role:</TD><TD>$rolesel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = "onload=\"document.editfield.NEW_VALUE.value='{$GLOBALS['fieldvalue']}'; document.editfield.NEW_VALUE.focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickEntity()
{ $entitysel = $GLOBALS['entitiesselect'];
   $entitysel = str_replace ("NEW_ENTITY", "NEW_VALUE", $entitysel);
   $pr = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">New entiry:</TD><TD>$entitysel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = "onload=\"document.editfield.NEW_VALUE.value='{$GLOBALS['fieldvalue']}'; document.editfield.NEW_VALUE.focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

function _pickEducation()
{ $edlevelssel = $GLOBALS['edlevelsselect'];
   $edlevelssel = str_replace ("NEW_EDUCATION", "NEW_VALUE", $edlevelssel);
   $pr = <<<NXT
<TABLE ID="edittable" cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="asstable" value="{$GLOBALS['asstable']}">
<INPUT type="HIDDEN" name="assrec" value="{$GLOBALS['assrec']}">
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['EducationTxt']}</TD><TD>$edlevelssel</TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>
NXT;
$GLOBALS['load'] = "onload=\"document.editfield.NEW_VALUE.value='{$GLOBALS['fieldvalue']}'; document.editfield.NEW_VALUE.focus();\"";
return $pr;
}

/* ---------------------------------------------------------------- */

if (substr_count ($handling, '(') > 0)
  { $function = substr ($handling, 0, strpos ($handling, '('));
     $function = trim ($function, "\x00..\x20");
     if (function_exists ($function)) eval ("\$list = $handling");
  }

/* ---------------------------------------------------------------- */

if (isset ($_POST['NEW_VALUE']))
   { if ( ((in_array($_POST['field'], $GLOBALS['requiredfields'][$_POST['table']])) && ($_POST['NEW_VALUE'] != '')) || (! in_array($_POST['field'], $GLOBALS['requiredfields'][$_POST['table']])) )
         { if (substr ($_POST['field'], -5) == '_NAME')
                  { $postedvalue = strtolower ($postedvalue);
                     $postedvalue = ucwords ($postedvalue);
                  }
            _UnHTMLize_addSlashes ();
            if (_setField ($_POST['table'], $_POST['field'], $_POST['record'], $_POST['NEW_VALUE'], $_POST['id']) == true) 
               { if (isset ($_POST['clear_fields']) && ($_POST['clear_fields'] != ''))
                     $clears = explode (",", $_POST['clear_fields']);
                     foreach ($clears as &$clear)
                                  { $sql = new mySQLclass();
                                     $query = "UPDATE {$_POST['table']} SET $clear = '' WHERE id = '{$_POST['record']}'";
                                     $sql->_sqlLookup ($query);
                                  }
                     $func = "_onEdit_{$_POST['table']}";
                     if (function_exists ($func)) $func ($_POST['table'], $_POST['record'], $asstable, $assrec);
                     $list = "<B>DONE";
                     if (isset ($_POST['year']))
                       $load = "onload=\"parent.parent.document.getElementById('edittable').style.visibility='hidden'; parent.parent.document.showtable.submit();\"";
                       else $load = "onload=\"parent.document.getElementById('edittable').style.visibility='hidden'; parent.document.showtable.submit();\"";
               }
         } else { $list = <<<NXT
<DIV align="center" style="background: #802000; color: #F7F6F2;">
<B>Field is required and not set / filled !
</DIV>
<P>$list
NXT;
                   }
   }

/* ---------------------------------------------------------------- */

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

$style

<BODY $load>
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
<DIV ID="viewtable" height="100%">
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

