<?php 
@session_start();

include_once 'std.php';

include 'style.php';


/* ---------------------------------------------------------------- */

$parentday = $_GET['pday'];
if (! $parentday) $parentday = $_POST['pday'];
$parentmonth = $_GET['pmon'];
if (! $parentmonth) $parentmonth = $_POST['pmon'];
$parentyear = $_GET['pyear'];
if (! $parentyear) $parentyear = $_POST['pyear'];

/* ---------------------------------------------------------------- */

if (isset ($_POST['year'])) 
   { if (($_POST['year'] == $GLOBALS['startyear']) && ($_POST['month'] < $GLOBALS['startmonth'])) $_POST['month'] = $GLOBALS['startmonth'];
      $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $_POST['month'], $_POST['year']);
      if ($_POST['day'] > $daysinmonth) $_POST['day'] = $daysinmonth;
      $cal = getdate (mktime (12, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']));
   } else { $cal = getdate ($GLOBALS['timestamp']);
                 $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $cal['mon'], $cal['year']);
             }

//foreach ($_POST as $key => $value) $pms .= "$key => $value<BR>";
   
$now = getdate ($GLOBALS['timestamp']);
/*
"seconds" Numeric representation of seconds 0 to 59 
"minutes" Numeric representation of minutes 0 to 59 
"hours" Numeric representation of hours 0 to 23 
"mday" Numeric representation of the day of the month 1 to 31 
"wday" Numeric representation of the day of the week 0 (for Sunday) through 6 (for Saturday) 
"mon" Numeric representation of a month 1 through 12 
"year" A full numeric representation of a year, 4 digits Examples: 1999 or 2003 
"yday" Numeric representation of the day of the year 0 through 365 
"weekday" A full textual representation of the day of the week Sunday through Saturday 
"month" A full textual representation of a month, such as January or March January through December 
0 Seconds since the Unix Epoch, similar to the values returned by time() and used by date().  System Dependent, typically -2147483648 through 2147483647.  

int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
*/

$sunrise = array ();
$sunset = array ();

for ($i=1;$i<=$daysinmonth;$i++)
     { $suninfo = date_sun_info (mktime (12, 0, 0, $cal['mon'], $i, $cal['year']), 0.32, 32.58);
        foreach ($suninfo as &$sometime) $sometime += $UTCoffset;
        array_push ($sunrise, date ("H:i:s", $suninfo['sunrise']));
        array_push ($sunset, date ("H:i:s", $suninfo['sunset'])); 
     }

$Jsunrise = implode ("','", $sunrise);
$Jsunrise = "var _sunrise = new Array ('" . $Jsunrise . "');";
$Jsunset = implode ("','", $sunset);
$Jsunset = "var _sunset = new Array ('" . $Jsunset . "');";

$calJava = <<<NXT
<SCRIPT language="JavaScript">
$Jsunrise
$Jsunset

function _setSunInfo (_date)
{ document.getElementById('sunrise').innerHTML = _sunrise[_date];
   document.getElementById('sunset').innerHTML = _sunset[_date];
}

var dim = $daysinmonth;
var today = {$now['mday']};
var nowyear = {$now['year']};
var calyear = {$cal['year']};
var nowmonth = {$now['mon']};
var calmonth = {$cal['mon']};

var selected = {$cal['mday']};

function _setDayMarker (_date)
{ for (var i=1;i<=dim;i++)
        { document.getElementById('cd' + i).style.borderColor='#A0A0A0';
           document.getElementById('cd' + i).style.background='#F7F6F0';
           document.getElementById('cd' + i).style.color='#000000';
           document.getElementById('cd' + i).style.fontWeight='normal';
        }
   if ((nowyear == calyear) && (nowmonth == calmonth))
      { document.getElementById('cd' + today).style.borderColor='#0000A0';
         document.getElementById('cd' + today).style.color='#802040';
         document.getElementById('cd' + today).style.fontWeight='bold';
      }
   document.getElementById('cd' + _date).style.borderColor='#000000';
   if ((_date == today) && (nowyear == calyear) && (nowmonth == calmonth))
      { document.getElementById('cd' + _date).style.background='#0000A0';
         document.getElementById('cd' + _date).style.color='#F7F6F2';
         document.getElementById('cd' + _date).style.fontWeight='bold';
      } else { document.getElementById('cd' + _date).style.background='#706000';
                   document.getElementById('cd' + _date).style.color='#F7F6F2';
                   document.getElementById('cd' + _date).style.fontWeight='bold';
                }
   document.calform.day.value = _date;
parent.document.dates.$parentday.value = _date;
   selected = _date;
   _setSunInfo (_date);
}
</SCRIPT>

NXT;

$calendar = "";
$thefirst = getdate(mktime (12, 0, 0, $cal['mon'], 1, $cal['year']));
if ($thefirst['wday'] == 0) $thefirst['wday'] = 7;
$r = 1;
if ($thefirst['wday'] > 1) for ($i=1;$i<$thefirst['wday'];$i++) 
   { $calendar .= "<TD>&nbsp;</TD>";
      $r++;
   } 

for ($i=1;$i<=$daysinmonth;$i++)
     { $calendar .= <<<NXT
<TD class="cc" align="center" ID="cd$i" 
onclick="_setDayMarker ($i);">$i</TD>
NXT;
        if (($r % 7 == 0) && ($i < $daysinmonth)) $calendar .= "\n</TR><TR>\n";
        $r++;
     }
while ($r % 7 != 1) 
         { $calendar .= "<TD>&nbsp;</TD>";
            $r++;
         }

/* ---------------------------------------------------- */

$yearback = <<<NXT
<INPUT type="BUTTON" class="btn10" value="<" 
onclick="
document.calform.year.value = Number(document.calform.year.value) - 1;
document.calform.submit();
">

NXT;

if ($cal['year'] == $GLOBALS['startyear']) $yearback = "";

$yearforth = <<<NXT
<INPUT type="BUTTON" class="btn10" value=">"
onclick="
document.calform.year.value = Number(document.calform.year.value) + 1;
document.calform.submit();
">

NXT;

$monthback = <<<NXT
<INPUT type="BUTTON" class="btn10" value="<" 
onclick="
if (document.calform.month.value == 1)
   { document.calform.month.value = 12;
      document.calform.year.value = Number(document.calform.year.value) - 1;
   } else document.calform.month.value = Number(document.calform.month.value) - 1;
document.calform.submit();
">

NXT;

if (($cal['year'] == $GLOBALS['startyear']) && ($cal['mon'] <= $GLOBALS['startmonth'])) $monthback = "";

$monthforth = <<<NXT
<INPUT type="BUTTON" class="btn10" value=">"
onclick="
if (document.calform.month.value == 12)
   { document.calform.month.value = 1;
      document.calform.year.value = Number(document.calform.year.value) + 1;
   } else document.calform.month.value = Number(document.calform.month.value) + 1;
document.calform.submit();
">

NXT;


$calendar = <<<NXT

$calJava

<STYLE>
.wd { background: #2020A0; color: #FFFFFF; font-family: Arial; border-style: solid; border-color: #A0A0A0; border-width: 1; }
.wds { background: #883000; color: #FFFFFF; font-family: Arial; border-style: solid; border-color: #A0A0A0; border-width: 1; }
.cce { }
.cc { cursor: default; border-style: solid; border-color: #A0A0A0; border-width: 1; font-family: Arial; }
</STYLE>

<TABLE ID="calendar" cellspacing="2" cellpadding="2" border="0" style="font-size: 8pt; border-style: ridge; border-color: #000080; border-width: 2; background: #F7F6F0;">
<FORM name="calform" method="POST" action="calendar2.php">
<INPUT type="HIDDEN" name="year" value="{$cal['year']}">
<INPUT type="HIDDEN" name="month" value="{$cal['mon']}">
<INPUT type="HIDDEN" name="day" value="{$cal['mday']}">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="{$_POST['table']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="field" value="{$_POST['field']}">
<INPUT type="HIDDEN" name="pday" value="$parentday">
<INPUT type="HIDDEN" name="pmon" value="$parentmonth">
<INPUT type="HIDDEN" name="pyear" value="$parentyear">
</FORM>
<TR>
<TD colspan="4" style="background: #0000A0; color: #F7F6F2; border-style: solid; border-color: #A0A0A0; border-width: 1;">
$monthback
$monthforth
<B><SPAN style="font-size: 10pt;">{$cal['month']}</SPAN>
</TD>
<TD colspan="3" style="background: #0000A0; color: #F7F6F2; border-style: solid; border-color: #A0A0A0; border-width: 1;">
$yearback
$yearforth
<B><SPAN style="font-size: 10pt;">{$cal['year']}</SPAN>
</TD>
</TR>

<TR>
<TD colspan="7" style="padding: 0 2 0 2;">
Sunrise: <SPAN ID="sunrise">$sunrise2day</SPAN>&nbsp;&nbsp;&nbsp;Sunset: <SPAN ID="sunset">$sunset2day</SPAN>
</TD>
</TR>

<TR>
<TD class="wd" width="18">Mon</TD><TD class="wd" width="18">Tue</TD><TD class="wd" width="18">Wed</TD><TD class="wd" width="18">Thu</TD><TD class="wd" width="18">Fri</TD><TD class="wd" width="18">Sat</TD><TD class="wds" width="18">Sun</TD>
<TR>
$calendar
</TR>
</TABLE>

<SCRIPT language="JavaScript">
_setDayMarker ({$cal['mday']});
parent.document.dates.$parentday.value = "{$cal['mday']}";
parent.document.dates.$parentmonth.value = "{$cal['mon']}";
parent.document.dates.$parentyear.value = "{$cal['year']}";
_setSunInfo ({$cal['mday']});
</SCRIPT>

NXT;


/* ---------------------------------------------------------------- */

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
$calendar 
</TD>
</TR>
</TABLE>
</BODY>
</HTML>

NXT;


echo $page;

?>

