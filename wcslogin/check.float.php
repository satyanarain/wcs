<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

function _boundsString ($min, $max)
{ $bounds = "value";
   if ($min != '') $bounds = "$min <= $bounds";
   if ($max != '') $bounds = "$bounds <= $max";
return "($bounds)";
}

/* ---------------------------------------------------------------- */

$value = "{$_POST['NEW_VALUE']}";
$min = floatval ($_POST['min']);
$max = floatval ($_POST['max']);
$size = floatval ($_POST['len']);
$decimals = floatval ($_POST['decimals']);

$error = false;

$EditFieldTxt = "Edit field:";
$SavingNewValueTxt = "Saving new value";
$NewValueTxt = "New value:";
$SaveBtn = "Save";
$CancelBtn = "Cancel";
switch ($_SESSION['selLang']) {
	case "FR":
		$EditFieldTxt = "Modifier le champ:";
		$SavingNewValueTxt = "Enregistrer une nouvelle valeur";
		$NewValueTxt = "Nouvelle valeur:";
		$SaveBtn = "Sauvegarder";
		$CancelBtn = "Annuler";
		break;
	case "ES":
		$EditFieldTxt = "Editar campo:";
		$SavingNewValueTxt = "Guardar un nuevo valor";
		$NewValueTxt = "Nuevo valor:";
		$SaveBtn = "Salvar";
		$CancelBtn = "Cancelar";
		break;
}   


if (strlen ($value) > $size) 
   { $error = true;
      $errormsg = "Number entry too long (max. length = $size)";
   }

if (! $error)
   { if ( (substr_count ($value , '.') > 1) || (substr_count ($value , '-') > 1) || ((substr_count ($value , '-') == 1) && (substr ($value, 0, 1) != '-')) ) $error = true;
      if ((strpos ($value, ".") !== false) && (strpos ($value, ".") > ($size - $decimals)))  $error = true;
      for ($i=0;$i<strlen($value);$i++) 
           { $r = ord (substr ($value, $i, 1));
              if (! ( ($r == 45) || ($r ==ord ('.')) || (($r >= 48) && ($r <= 57)) ) ) $error = true;
           }
      if ($error) $errormsg = "Incorrect number format";
   }

if (! $error)
   { $val = floatval ($value);
      if ( ($_POST['min'] != '') && ($val < $min) ) $error = true;
      if ( ($_POST['max'] != '') && ($val > $max) ) $error = true;
      if ($error) $errormsg = "Number out of bounds " . _boundsString ($_POST['min'], $_POST['max']);
   }
      
/* ---------------------------------------------------------------- */

if (! $error) 
   { 

   
   $load = "onload=\"document.editfield.submit();\"";
      $list = <<<NXT
<TABLE cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="editfield" method="POST" action="edit.field.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="NEW_VALUE" value="$val">
<TR><TD class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['SavingNewValueTxt']}</TD></TR>
</FORM>
</TABLE>

NXT;

$page = <<<NXT
<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein • e-mail: jan.kirstein\@jayksoft.com
Copyright © 2012 Jan Kirstein. All rights reserved!
-->

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
die();
   }

/* ---------------------------------------------------------------- */

$list = <<<NXT
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
<TR><TD colspan="2" class="th">{$GLOBALS['EditFieldTxt']} {$GLOBALS['tablelabels'][$_POST['table']][$_POST['field']]}</TD></TR>
<TR><TD align="right">{$GLOBALS['NewValueTxt']}</TD><TD><INPUT type="TEXT" class="btn" size="20" maxlength="$size" name="NEW_VALUE" value="$value"></TD></TR>

<TR><TD align="center"><INPUT type="SUBMIT" Value='{$GLOBALS['SaveBtn']}' class="btn" onclick="document.getElementById('edittable').style.visibility = 'hidden';"></TD><TD align="center"><INPUT type="BUTTON" class="btn" Value='{$GLOBALS['CancelBtn']}' onclick="document.getElementById('viewtable').style.visibility = 'hidden'; parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; parent.document.getElementById('edittable').style.visibility='hidden';"></TD></TR>
</FORM>
</TABLE>

NXT;

$list = <<<NXT
<DIV align="center" style="background: #802000; color: #F7F6F2;">
<B>Error! $errormsg
</DIV>
<P>
$list

NXT;

$page = <<<NXT
<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein • e-mail: jan.kirstein\@jayksoft.com
Copyright © 2012 Jan Kirstein. All rights reserved!
-->

$style

<BODY>
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

?>

