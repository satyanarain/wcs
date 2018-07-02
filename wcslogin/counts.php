<?php 
@session_start();

$last = $first + $count - 1;

$chunk = "&nbsp;";
if ($first > _first_) 
   { $_first = _first_;
      $chunk .= <<<NXT
&nbsp;<INPUT type="BUTTON" class="btn" value="<<"
onclick="document.showtable.first.value = $_first; 
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="< -"
onclick="document.showtable.first.value = $first - $max; 
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
NXT;
   }

if (($count < $total) && ($last < $total)) 
   $chunk .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="+ >"
onclick="document.showtable.first.value = $first + $max; 
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
NXT;

$close = 'Close';
switch ($_SESSION['selLang']) {
	case "FR":
		$close = 'Fermer';
		break;
	case "ES":
		$close = 'Cerca';
		break;
}


if (($table == 'evidencetable') || ($table == 'offenderstable') || ($table == 'defendantstable') ||  ($table == 'sentencetable') || ($table == 'verdictstable')) $chunk .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="$close"
onclick="
document.getElementById('newtablebgr').style.visibility = 'hidden';
parent.document.getElementById('pickrecordframe').src = '';
parent.document.getElementById('pickrecordtable').style.visibility = 'hidden';
parent.document.getElementById('edittable').style.visibility='hidden';
parent.document.showtable.submit();
">
NXT;


//if ($total == 0) $showing = "No records found"; else $showing = "Showing $first to $last of $total entries";

$shownorecordEN = 'No records found';
$showrecordsEN = 'Showing ' . $first . ' to ' . $last . ' of ' . $total . ' entries';
$shownorecordFR = 'Aucun enregistrement trouvé';
$showrecordsFR = 'Résultats ' . $first . ' à ' . $last . ' sur ' . $total; //Résultats 1 à 10 sur 20
$shownorecordES = 'No se encontrarón archivos';
$showrecordsES = 'Mostrando ' . $first . ' a ' . $last . ' de ' . $total; //Mostrando 1 a 10 de 20 entradas

switch ($_SESSION['selLang']) {
	case "FR":
		if ($total == 0) $showing = $shownorecordFR; else $showing = $showrecordsFR;
		break;
	case "ES":
		if ($total == 0) $showing = $shownorecordES; else $showing = $showrecordsES;
		break;
	default:
		if ($total == 0) $showing = $shownorecordEN; else $showing = $showrecordsEN;
}


$list = str_replace ("#SHOWING1#", $showing, $list);
$list = str_replace ("#SHOWING2#", "$chunk", $list);

if (($_POST['role'] == 'GOD') || ($GLOBALS['permissions'][$_POST['role']][$portal][$table] & _create_))
   { 
$addnew = '<INPUT type="button" value="Add NEW Entry" class="btn" onclick="_setNewFrame(550, 400); document.newrecord.submit();">';
switch ($_SESSION['selLang']) {
	case "FR":
		$addnew = '<INPUT type="button" value="Ajouter une nouvelle entrée" class="btn" onclick="_setNewFrame(550, 400); document.newrecord.submit();">';
		break;
	case "ES":
		$addnew = '<INPUT type="button" value="Agregar NUEVA entrada" class="btn" onclick="_setNewFrame(550, 400); document.newrecord.submit();">';
		break;
	default:
		$addnew = '<INPUT type="button" value="Add NEW Entry" class="btn" onclick="_setNewFrame(550, 400); document.newrecord.submit();">';
}
   
   
   $chunk = <<<NXT
<TD>
$addnew

NXT;
   } else $chunk = "";

if ($_POST['role'] == 'GOD') 
   { if ($chunk != '') $chunk .= "</TD><TD>"; 
      $chunk .= <<<NXT
<INPUT type="button" value="mySql query" class="btn" 
onclick="
document.getElementById('sqlquery').style.display='inline';
document.getElementById('sqlquerymsg').style.display='none';
document.showtable.SQLQUERY.value = '{$_POST['SQLQUERY']}';
">
</TD>
<TD>
<DIV ID="sqlquery" style="display: none;">
<INPUT type="TEXT" name="SQLQUERY" size="40" value="" class="btn">
</DIV>
</TD>
<TD>
<DIV ID="sqlquerymsg" style="color: #B0B0B0; display: inline;">
{$_POST['SQLQUERY']}
</DIV>
</TD>
NXT;
   }

if ($chunk) $chunk = <<<NXT
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
$chunk
</TR>
</TABLE>

NXT;

?>

