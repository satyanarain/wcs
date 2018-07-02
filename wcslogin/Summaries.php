<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';


$TxtL1 = "Select date-span for report:";
$TxtL2 = "&lt;- start date";
$TxtL3 = "end date -&gt;";
$TxtL4 = "Date span will be ignored if both dates are identical, or if start date is set to be later than end date, i.e.: Leave unchanged to summarize all data";
$TxtL5 = "Summary / report:";
$TxtL6 = "By Crime type:";
$TxtL7 = "CSV delimiter";
$TxtL8 = "Get summary";
$DropDown1 = "Cases summary";
$DropDown2 = "Pending Cases summary";
$DropDown3 = "Verdicts summary";
$DropDown4 = "Arrests summary";
$DropDown5 = "Sentences summary";
$DropDown6 = "ESRI Analysis";
$DropDown7 = "Trace impounded item";
$summarymanual = "summaries.manual.php";
switch ($_SESSION['selLang']) {
	case "FR":
		$TxtL1 = "Sélectionner la période pour le rapport:";
		$TxtL2 = "&lt;- date de début";
		$TxtL3 = "date de fin -&gt;";
		$TxtL4 = "L'intervalle de dates sera ignoré si les deux dates sont identiques ou si la date de début est définie comme étant postérieure à la date de fin, c'est-à-dire: laisser inchangé pour résumer toutes les données";
		$TxtL5 = "Rapport sommaire:";
		$TxtL6 = "Par type de crime:";
		$TxtL7 = "Délimiteur CSV";
		$TxtL8 = "Obtenir un résumé";
		$DropDown1 = "Résumé des cas";
		$DropDown2 = "Résumé des cas en attente";
		$DropDown3 = "Résumé des verdicts";
		$DropDown4 = "Résumé des arrestations";
		$DropDown5 = "Résumé de la peine";
		$DropDown6 = "Analyse ESRI";
		$DropDown7 = "Trace confisqué";
		$summarymanual = "summaries.manual_FR.php";
		break;
	case "ES":
		$TxtL1 = "Seleccione el intervalo de fecha para el informe:";
		$TxtL2 = "&lt;- fecha de inicio";
		$TxtL3 = "fecha final -&gt;";
		$TxtL4 = "El intervalo de fechas se ignorará si ambas fechas son idénticas o si la fecha de inicio se establece como posterior a la fecha de finalización, es decir: Dejar sin cambios para resumir todos los datos";
		$TxtL5 = "Informe resumido:";
		$TxtL6 = "Por tipo de delito:";
		$TxtL7 = "Delimitador CSV";
		$TxtL8 = "Obtener resumen";
		$DropDown1 = "Resumen de casos";
		$DropDown2 = "Resumen de casos pendientes";
		$DropDown3 = "Resumen de veredictos";
		$DropDown4 = "Resumen de las detenciones";
		$DropDown5 = "Resumen de oraciones";
		$DropDown6 = "Análisis ESRI";
		$DropDown7 = "Rastreo de un objeto embargado";
		$summarymanual = "summaries.manual_ES.php";
		break;
}

$stations = substr ($outposts, 0, -4);
$stations = _makeSelect ("STATION", "$stations");

$sel = _makeArrayFromTable ("crimes", "CRIME_NAME");
$crimetypes = implode ('~', $sel);
$crimetypes = _makeSelect ("CRIME", "$crimetypes");

$sel = _makeArrayFromTable ('courts', 'COURT_NAME');
$courts = implode ('~', $sel);
$courts = _makeSelect ("COURT", "$courts");

$stations = str_replace ('-select-', '(any)', $stations);
$crimetypes = str_replace ('-select-', '(any)', $crimetypes);
$courts = str_replace ('-select-', '(any)', $courts);

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein � e-mail: jan.kirstein\@jayksoft.com
Copyright � 2012 - 2013 Jan Kirstein. All rights reserved!
-->

$style


<BODY style="background: #F8F6F2;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<FORM name="dates" method="POST" action="StatsAdministration.php" target="reportframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="fromday" value="">
<INPUT type="HIDDEN" name="frommonth" value="">
<INPUT type="HIDDEN" name="fromyear" value="">
<INPUT type="HIDDEN" name="today" value="">
<INPUT type="HIDDEN" name="tomonth" value="">
<INPUT type="HIDDEN" name="toyear" value="">
<TR>
<TD width="220" height="220" align="center" Valign="top">
<Iframe name="fromdateframe" ID="fromdateframe" width="220" height="220" frameborder="0" SRC="calendar2.php?pday=fromday&pmon=frommonth&pyear=fromyear" allowTransparency="true"></Iframe>
</TD>

<TD width="70" align="center" Valign="top">
&nbsp;<BR>
<B>
$TxtL1
<DIV align="left">
$TxtL2
</DIV>
<DIV align="right">
$TxtL3
</DIV>
</B>
</TD>

<TD width="220" height="220" align="center" Valign="top">
<Iframe name="todateframe" ID="todateframe" width="220" height="220" frameborder="0" SRC="calendar2.php?pday=today&pmon=tomonth&pyear=toyear" allowTransparency="true">allowTransparency="true"></Iframe>
</TD>

<TD align="left" Valign="top" style="padding: 6 10 0 0;">
$TxtL4
<P>
<DIV style="background: #D8D8D8;"><B style="font-size: 10pt;">$TxtL5 &nbsp;</B>
<SELECT name="summary" class="btn"
onchange="document.dates.action = value;
if (value == 'TraceItem.php') document.getElementById('item').style.visibility = 'visible'; else document.getElementById('item').style.visibility = 'hidden';
if (value == 'StatsAdministration4.php') document.getElementById('area').style.visibility = 'visible'; else document.getElementById('area').style.visibility = 'hidden';
">
<OPTION value="StatsAdministration.php">$DropDown1
<OPTION value="StatsAdministration4.php">$DropDown2
<OPTION value="StatsAdministration3.php">$DropDown3
<OPTION value="StatsAdministration2.php">$DropDown4
<OPTION value="StatsAdministration5.php">$DropDown5
<OPTION value="StatsAdministration6.php">$DropDown6
<OPTION value="TraceItem.php">$DropDown7
</SELECT>
&nbsp;&nbsp;&nbsp;
<SPAN style="visibility: hidden;">
<INPUT type="TEXT" name="item" ID="item" value="(item)" class="btn">
</SPAN>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<SPAN style="visibility: hidden;">
<INPUT type="TEXT" name="area" ID="area" value="(area)" class="btn">
</SPAN>
</DIV>
<P>
<!--
<P>By Court: &nbsp;$courts

<P>By Station: &nbsp;$stations
-->

<P>$TxtL6 &nbsp;$crimetypes
<P>
$TxtL7
<SELECT name="separator" class="btn">
<OPTION value="<,>">&lt;,&gt;
<OPTION value="">&lt;Tab&gt;
<OPTION value="<;>">&lt;;&gt;
</SELECT>
&nbsp;&nbsp;&nbsp;
<INPUT type="SUBMIT" value="$TxtL8" style="font-weight: bold; font-size: 10pt;">
</TD>
</TR>

<TR>
<TD colspan="4" align="center" Valign="top" style="border-style: solid; border-width: 2 0 0 0;">
<Iframe name="reportframe" ID="reportframe" width="100%" height="100%" frameborder="0" SRC="$summarymanual" allowTransparency="true"></Iframe>
</TD>
</TR>

</FORM>
</TABLE>


</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

